<script setup lang="ts">
import { onMounted, onUnmounted, ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Command, CommandInput, CommandList, CommandEmpty, CommandGroup, CommandItem } from '@/components/ui/command';
import type { BreadcrumbItem, Player, Game } from '@/types';
import { getPlayers } from '@/api/players';

enum Point {
    Love = 0,
    Fifteen = 1,
    Thirty = 2,
    Forty = 3,
    Advantage = 4,
}

const PointLabels: Record<Point, string> = {
    [Point.Love]: 'Love',
    [Point.Fifteen]: 'Fifteen',
    [Point.Thirty]: 'Thirty',
    [Point.Forty]: 'Forty',
    [Point.Advantage]: 'Advantage',
};

const props = defineProps<{ game: Game }>();

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
    if (player1Ref.value && !player1Ref.value.contains(event.target as Node)) openPlayer1.value = false;
    if (player2Ref.value && !player2Ref.value.contains(event.target as Node)) openPlayer2.value = false;
};

const onFocusPlayer1 = () => { openPlayer1.value = true; openPlayer2.value = false; };
const onFocusPlayer2 = () => { openPlayer2.value = true; openPlayer1.value = false; };

const form = useForm({
    played_at: '',
    winner_id: props.game.winner_id,
    match_status: props.game.match_status,
    player1_id: props.game.player1_id,
    player2_id: props.game.player2_id,
    player1_points: props.game.player1_points,
    player2_points: props.game.player2_points,
});

const playedAtPlaceholder = computed(() => props.game.played_at.slice(0,16));

const selectPlayer1 = (player: Player) => { selectedPlayer1.value = player; form.player1_id = player.id; openPlayer1.value = false; };
const selectPlayer2 = (player: Player) => { selectedPlayer2.value = player; form.player2_id = player.id; openPlayer2.value = false; };

const filteredPlayers1 = computed(() =>
    players.value.filter(p => `${p.first_name} ${p.last_name}`.toLowerCase().includes(search1.value.toLowerCase()))
);
const filteredPlayers2 = computed(() =>
    players.value.filter(p => `${p.first_name} ${p.last_name}`.toLowerCase().includes(search2.value.toLowerCase()))
);

const submit = () => {
    const payload: any = { ...form };

    if (!form.played_at) delete payload.played_at;

    form.put(route('games.update', props.game.id), {onSuccess: () => alert('Game updated successfully!') });
};

const pointOptions = Object.values(Point)
    .filter(v => typeof v === 'number')
    .map(v => ({ value: v as number, label: PointLabels[v as Point] }));

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Games', href: route('games.index') },
    { title: `Edit Game #${props.game.id}`, href: '' },
];

onMounted(async () => {
    loadingPlayers.value = true;
    try {
        players.value = await getPlayers();
        selectedPlayer1.value = players.value.find(p => p.id === props.game.player1_id) || null;
        selectedPlayer2.value = players.value.find(p => p.id === props.game.player2_id) || null;
        search1.value = selectedPlayer1.value ? `${selectedPlayer1.value.first_name} ${selectedPlayer1.value.last_name}` : '';
        search2.value = selectedPlayer2.value ? `${selectedPlayer2.value.first_name} ${selectedPlayer2.value.last_name}` : '';
    } finally { loadingPlayers.value = false; }
    document.addEventListener('click', handleClickOff);
});

onUnmounted(() => document.removeEventListener('click', handleClickOff));
</script>

<template>
    <Head :title="`Edit Game #${props.game.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-lg mx-auto p-6 bg-white dark:bg-gray-900 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-6 text-green-400">Edit Game #{{ props.game.id }}</h1>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- Played At -->
                <div>
                    <label for="played_at" class="block text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Played At (date/time):</label>
                    <Input
                        id="played_at"
                        type="datetime-local"
                        v-model="form.played_at"
                        :placeholder="playedAtPlaceholder"
                        class="w-full dark:bg-gray-800 dark:text-gray-100"
                        :class="{ 'border-red-500': form.errors.played_at }"
                    />
                    <p v-if="form.errors.played_at" class="text-red-600 text-sm mt-1">{{ form.errors.played_at }}</p>
                </div>

                <!-- Player 1 -->
                <div ref="player1Ref">
                    <label class="block text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Player 1:</label>
                    <Command class="border rounded-md dark:border-gray-700">
                        <CommandInput
                            placeholder="Search player..."
                            :value="selectedPlayer1 ? `${selectedPlayer1.first_name} ${selectedPlayer1.last_name}` : search1"
                            @focus="onFocusPlayer1"
                            @input="(e: Event) => search1 = (e.target as HTMLInputElement).value"
                            class="dark:bg-gray-800 dark:text-gray-100"
                        />
                        <CommandList v-if="openPlayer1">
                            <CommandEmpty>No players found.</CommandEmpty>
                            <CommandGroup>
                                <CommandItem
                                    v-for="player in filteredPlayers1"
                                    :key="player.id"
                                    :value="player.id"
                                    @select="selectPlayer1(player)"
                                >
                                    {{ player.first_name }} {{ player.last_name }}
                                </CommandItem>
                            </CommandGroup>
                        </CommandList>
                    </Command>
                </div>

                <!-- Player 2 -->
                <div ref="player2Ref">
                    <label class="block text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Player 2:</label>
                    <Command class="border rounded-md dark:border-gray-700">
                        <CommandInput
                            placeholder="Search player..."
                            :value="selectedPlayer2 ? `${selectedPlayer2.first_name} ${selectedPlayer2.last_name}` : search2"
                            @focus="onFocusPlayer2"
                            @input="(e: Event) => search2 = (e.target as HTMLInputElement).value"
                            class="dark:bg-gray-800 dark:text-gray-100"
                        />
                        <CommandList v-if="openPlayer2">
                            <CommandEmpty>No players found.</CommandEmpty>
                            <CommandGroup>
                                <CommandItem
                                    v-for="player in filteredPlayers2"
                                    :key="player.id"
                                    :value="player.id"
                                    @select="selectPlayer2(player)"
                                >
                                    {{ player.first_name }} {{ player.last_name }}
                                </CommandItem>
                            </CommandGroup>
                        </CommandList>
                    </Command>
                </div>

                <!-- Match Status -->
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Match Status:</label>
                    <select v-model="form.match_status" class="w-full border rounded-md p-2 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700">
                        <option value="upcoming">Upcoming</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="tied">Tied</option>
                    </select>
                </div>

                <!-- Winner -->
                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Winner (optional):</label>
                    <select v-model="form.winner_id" class="w-full border rounded-md p-2 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700">
                        <option :value="null">N/A</option>
                        <option v-if="selectedPlayer1" :value="selectedPlayer1.id">{{ selectedPlayer1.first_name }} {{ selectedPlayer1.last_name }}</option>
                        <option v-if="selectedPlayer2" :value="selectedPlayer2.id">{{ selectedPlayer2.first_name }} {{ selectedPlayer2.last_name }}</option>
                    </select>
                </div>

                <!-- Points -->
                <div class="flex gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Player 1 Points:</label>
                        <select v-model.number="form.player1_points" class="w-full border rounded-md p-2 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700">
                            <option v-for="p in pointOptions" :key="p.value" :value="p.value">{{ p.label }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Player 2 Points:</label>
                        <select v-model.number="form.player2_points" class="w-full border rounded-md p-2 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700">
                            <option v-for="p in pointOptions" :key="p.value" :value="p.value">{{ p.label }}</option>
                        </select>
                    </div>
                </div>

                <Button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white">
                    {{ form.processing ? 'Updating...' : 'Update Game' }}
                </Button>
            </form>
        </div>
    </AppLayout>
</template>
