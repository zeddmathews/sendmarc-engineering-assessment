<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Game } from '@/types';
import { getUpcomingGames, getTennisGame, assignPoint, startGame } from '@/api/matches';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Simulate Match', href: '/simulate-match' },
];

const upcomingGames = ref<Game[]>([]);
const selectedGameId = ref<number | undefined>(undefined);
const tennisGame = ref<Game & any | null>(null);
const loading = ref(false);
const error = ref<string | null>(null);

const page = usePage();
const isAdmin = computed(() => page.props.auth.user?.is_admin ?? false);

const player1Name = computed(() => tennisGame.value?.player1 ? `${tennisGame.value.player1.first_name} ${tennisGame.value.player1.last_name}` : '');
const player2Name = computed(() => tennisGame.value?.player2 ? `${tennisGame.value.player2.first_name} ${tennisGame.value.player2.last_name}` : '');

const player1Score = computed(() => tennisGame.value?.match_status === 'ongoing' ? tennisGame.value?.score?.player1 : '—');
const player2Score = computed(() => tennisGame.value?.match_status === 'ongoing' ? tennisGame.value?.score?.player2 : '—');
const gameOver = computed(() => tennisGame.value?.game_over ?? false);
const winnerName = computed(() => {
    if (!tennisGame.value?.winner) return null;
    if (tennisGame.value.player1?.id === tennisGame.value.winner) return player1Name.value;
    if (tennisGame.value.player2?.id === tennisGame.value.winner) return player2Name.value;
    return null;
});
const displayMatchStatus = computed(() => {
    if (!tennisGame.value?.match_status) return '';
    return tennisGame.value.match_status.charAt(0).toUpperCase() + tennisGame.value.match_status.slice(1);
});

const matchDateTime = computed(() => {
    if (!tennisGame.value) return '';
    const date = new Date(tennisGame.value.played_at);
    const y = date.getUTCFullYear();
    const m = String(date.getUTCMonth() + 1).padStart(2, '0');
    const d = String(date.getUTCDate()).padStart(2, '0');
    const h = String(date.getUTCHours()).padStart(2, '0');
    const min = String(date.getUTCMinutes()).padStart(2, '0');
    return `${d}-${m}-${y} ${h}:${min} UTC`;
});

const canStartGame = computed(() => {
    if (!tennisGame.value) return false;
    const now = new Date();
    const scheduled = new Date(tennisGame.value.played_at);
    return now >= scheduled;
});

const fetchUpcomingGames = async () => {
    try {
        const res = await getUpcomingGames();
        upcomingGames.value = res.data;
    } catch (err: any) {
        error.value = err.message || 'Failed to fetch upcoming games';
    }
};

const fetchGame = async (gameId: number) => {
    if (!gameId) return;
    loading.value = true;
    try {
        const res = await getTennisGame(gameId);
        tennisGame.value = res.data;
    } catch (err: any) {
        error.value = err.message || 'Failed to fetch game';
    } finally {
        loading.value = false;
    }
};

const pointFor = async (playerId: number) => {
    if (!selectedGameId.value || gameOver.value) return;
    try {
        const res = await assignPoint(selectedGameId.value, playerId);
        tennisGame.value = res.data;
    } catch (err: any) {
        error.value = err.message || 'Failed to assign point';
    }
};

const startGameNow = async () => {
    if (!selectedGameId.value) return;
    try {
        const res = await startGame(selectedGameId.value);
        tennisGame.value = res.data;
    } catch (err: any) {
        error.value = err.message || 'Failed to start game';
    }
};

watch(selectedGameId, (id) => {
    if (id !== undefined) fetchGame(id);
});

onMounted(() => {
    fetchUpcomingGames();
});
</script>

<template>
    <Head title="Simulate Match" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-2xl mx-auto p-4 space-y-6">

            <div>
                <label class="block mb-2 text-sm font-semibold">Select Upcoming Game:</label>
                <select v-model="selectedGameId" class="w-full border rounded-md p-2">
                    <option :value="undefined" disabled>Select a game</option>
                    <option
                        v-for="game in upcomingGames"
                        :key="game.id"
                        :value="game.id"
                    >
                        {{ game.player1?.first_name }} {{ game.player1?.last_name }} vs {{ game.player2?.first_name }} {{ game.player2?.last_name }}
                    </option>
                </select>
            </div>

            <div v-if="tennisGame" class="space-y-4">

                <div v-if="isAdmin && !canStartGame" class="text-center">
                    <Button class="bg-purple-500" @click="startGameNow">
                        Start Game Now (Admin Override)
                    </Button>
                </div>

                <div class="flex space-x-4">
                    <div class="flex-1 border rounded-md p-4 text-center">
                        <div class="text-lg font-bold">{{ player1Name }}</div>
                        <div class="text-xl font-mono my-2">{{ player1Score }}</div>
                        <Button
                            class="w-full bg-blue-500"
                            @click="tennisGame.player1?.id && pointFor(tennisGame.player1.id)"
                            :disabled="!canStartGame || gameOver"
                        >
                            +1 {{ player1Name }}
                        </Button>
                    </div>

                    <div class="flex-1 border rounded-md p-4 text-center">
                        <div class="text-lg font-bold">{{ player2Name }}</div>
                        <div class="text-xl font-mono my-2">{{ player2Score }}</div>
                        <Button
                            class="w-full bg-green-500"
                            @click="tennisGame.player2?.id && pointFor(tennisGame.player2.id)"
                            :disabled="!canStartGame || gameOver"
                        >
                            +1 {{ player2Name }}
                        </Button>
                    </div>
                </div>

                <div v-if="gameOver" class="mt-4 text-lg font-bold text-red-600 text-center">
                    Game Over! Winner: {{ winnerName }}
                </div>

                <div class="mt-4 text-center text-sm text-gray-700">
                    <div><strong>Status:</strong> {{ displayMatchStatus }}</div>
                    <div><strong>Scheduled:</strong> {{ matchDateTime }}</div>
                </div>
            </div>

            <div v-if="loading" class="text-center text-gray-500">Loading...</div>
            <div v-if="error" class="text-center text-red-600">{{ error }}</div>
        </div>
    </AppLayout>
</template>
