<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Command, CommandInput, CommandList, CommandEmpty, CommandGroup, CommandItem } from '@/components/ui/command';
import type { BreadcrumbItem, Player } from '@/types';
import { getPlayers } from '@/api/players';

const players = ref<Player[]>([]);
const search1 = ref('');
const search2 = ref('');
const selectedPlayer1 = ref<Player | null>(null);
const selectedPlayer2 = ref<Player | null>(null);
const openPlayer1 = ref(false);
const openPlayer2 = ref(false);
const player1Ref = ref<HTMLElement | null>(null);
const player2Ref = ref<HTMLElement | null>(null);
const loadingPlayers = ref(false);

const handleClickOff = (event: MouseEvent) => {
    if (player1Ref.value && !player1Ref.value.contains(event.target as Node)) {
        openPlayer1.value = false;
    }
    if (player2Ref.value && !player2Ref.value.contains(event.target as Node)) {
        openPlayer2.value = false;
    }
};

const onFocusPlayer1 = () => {
    openPlayer1.value = true;
    openPlayer2.value = false;
};

const onFocusPlayer2 = () => {
    openPlayer2.value = true;
    openPlayer1.value = false;
};

const form = useForm({
    played_at: '',
    winner_id: null as number | null,
    match_status: 'upcoming',
    player1_id: null as number | null,
    player2_id: null as number | null,
});

const submit = () => {
    if (!form.player1_id || !form.player2_id) {
        alert('Please select 2 players');
        return;
    }
    if (form.player1_id === form.player2_id) {
        alert('Players must be different');
        return;
    }

    form.post(route('api.games.store'), {
        onSuccess: () => {
            alert('Game created successfully!');
            form.reset();
            selectedPlayer1.value = null;
            selectedPlayer2.value = null;
            search1.value = '';
            search2.value = '';
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Games', href: route('games.index') },
    { title: 'Create', href: '' },
];

onMounted(async () => {
    loadingPlayers.value = true;
    try {
        players.value = await getPlayers();
    } finally {
        loadingPlayers.value = false;
    }
    document.addEventListener('click', handleClickOff);
});

onUnmounted(() => document.removeEventListener('click', handleClickOff));

const filteredPlayers1 = computed(() =>
    players.value.filter((p) =>
        `${p.first_name} ${p.last_name}`.toLowerCase().includes(search1.value.toLowerCase())
    )
);

const filteredPlayers2 = computed(() =>
    players.value.filter((p) =>
        `${p.first_name} ${p.last_name}`.toLowerCase().includes(search2.value.toLowerCase())
    )
);

const selectPlayer1 = (player: Player) => {
    selectedPlayer1.value = player;
    form.player1_id = player.id;
    openPlayer1.value = false;
};

const selectPlayer2 = (player: Player) => {
    selectedPlayer2.value = player;
    form.player2_id = player.id;
    openPlayer2.value = false;
};
</script>

<template>
    <Head title="Create Game" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-lg mx-auto p-6 bg-white dark:bg-gray-900 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-6 text-green-500 dark:text-green-400">
                Create a New Game
            </h1>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="played_at" class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                        Played At (date/time):
                    </label>
                    <Input
                        id="played_at"
                        type="datetime-local"
                        v-model="form.played_at"
                        class="w-full dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700"
                        required
                        :class="{ 'border-red-500': form.errors.played_at }"
                    />
                    <p v-if="form.errors.played_at" class="text-red-600 text-sm mt-1">
                        {{ form.errors.played_at }}
                    </p>
                </div>

                <div ref="player1Ref">
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                        Player 1:
                    </label>
                    <Command class="border rounded-md dark:bg-gray-800 dark:border-gray-700">
                        <CommandInput
                            placeholder="Search player..."
                            class="dark:placeholder-gray-400 dark:text-gray-200"
                            :value="selectedPlayer1 ? `${selectedPlayer1.first_name} ${selectedPlayer1.last_name}` : search1"
                            @focus="onFocusPlayer1"
                            @input="(e: Event) => search1 = (e.target as HTMLInputElement).value"
                        />
                        <CommandList v-if="openPlayer1" class="dark:bg-gray-800">
                            <CommandEmpty class="dark:text-gray-400">No players found.</CommandEmpty>
                            <CommandGroup>
                                <CommandItem
                                    v-for="player in filteredPlayers1"
                                    :key="player.id"
                                    :value="player.id"
                                    @select="selectPlayer1(player)"
                                    class="dark:hover:bg-gray-700 dark:text-gray-200"
                                >
                                    {{ player.first_name }} {{ player.last_name }}
                                </CommandItem>
                            </CommandGroup>
                        </CommandList>
                    </Command>
                    <p v-if="form.errors.player1_id" class="text-red-600 text-sm mt-1">
                        {{ form.errors.player1_id }}
                    </p>
                </div>

                <div ref="player2Ref">
                    <label class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">
                        Player 2:
                    </label>
                    <Command class="border rounded-md dark:bg-gray-800 dark:border-gray-700">
                        <CommandInput
                            placeholder="Search player..."
                            class="dark:placeholder-gray-400 dark:text-gray-200"
                            :value="selectedPlayer2 ? `${selectedPlayer2.first_name} ${selectedPlayer2.last_name}` : search2"
                            @focus="onFocusPlayer2"
                            @input="(e: Event) => search2 = (e.target as HTMLInputElement).value"
                        />
                        <CommandList v-if="openPlayer2" class="dark:bg-gray-800">
                            <CommandEmpty class="dark:text-gray-400">No players found.</CommandEmpty>
                            <CommandGroup>
                                <CommandItem
                                    v-for="player in filteredPlayers2"
                                    :key="player.id"
                                    :value="player.id"
                                    @select="selectPlayer2(player)"
                                    class="dark:hover:bg-gray-700 dark:text-gray-200"
                                >
                                    {{ player.first_name }} {{ player.last_name }}
                                </CommandItem>
                            </CommandGroup>
                        </CommandList>
                    </Command>
                    <p v-if="form.errors.player2_id" class="text-red-600 text-sm mt-1">
                        {{ form.errors.player2_id }}
                    </p>
                </div>

                <Button
                    type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white dark:bg-green-600 dark:hover:bg-green-500"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Creating...' : 'Create Game' }}
                </Button>
            </form>
        </div>
    </AppLayout>
</template>
