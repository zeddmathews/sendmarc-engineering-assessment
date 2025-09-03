<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { getTennisGame, assignPoint, startGame } from '@/api/matches';
import type { GamesPageProps, BreadcrumbItem, Game } from '@/types';
import { Button } from '@/components/ui/button';
import AdminOnly from '@/components/AdminOnly.vue';
import GameEndModal from '@/components/GameEndModal.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/' },
    { title: 'Simulate Match', href: '/simulate' },
];

const page = usePage<GamesPageProps>();
const upcomingGames = computed(() => page.props.games ?? []);
const selectedGameId = ref<number | null>(null);
const tennisGame = ref<Game | null>(null);
const error = ref<string | null>(null);

const canStartGame = computed(() => {
    return selectedGameId.value && tennisGame.value?.match_status === 'upcoming' && !tennisGame.value.winner_id;
});

const winnerName = computed(() => {
    if (!tennisGame.value || !tennisGame.value.winner_id) {
        return '—';
    }

    const winnerId = tennisGame.value.winner_id;

    if (tennisGame.value.winner) {
        return `${tennisGame.value.winner.first_name} ${tennisGame.value.winner.last_name}`;
    }
    if (tennisGame.value.player1 && tennisGame.value.player1.id === winnerId) {
        return `${tennisGame.value.player1.first_name} ${tennisGame.value.player1.last_name}`;
    }
    if (tennisGame.value.player2 && tennisGame.value.player2.id === winnerId) {
        return `${tennisGame.value.player2.first_name} ${tennisGame.value.player2.last_name}`;
    }

    return 'N/A';
});

watch(selectedGameId, async (newId) => {
    tennisGame.value = newId ? await getTennisGame(newId) : null;
});

const formatPlayerName = (game: Game | null, side: 'player1' | 'player2' | 'winner') => {
    if (!game) return 'N/A';
    if (side === 'winner') {
        if (!game.winner) return '—';
        return `${game.winner.first_name} ${game.winner.last_name}`;
    }

    const player = game[side];
    return player ? `${player.first_name} ${player.last_name}` : 'N/A';
};

const givePoint = async (player: 'player1' | 'player2') => {
    if (!tennisGame.value) return;

    const playerId = tennisGame.value[player]?.id;
    if (!playerId) {
        error.value = 'Player ID not found';
        return;
    }
    try {
        const response = await assignPoint(tennisGame.value.id, playerId);
        tennisGame.value = response;
    } catch (err: any) {
        error.value = err.response?.data?.message || 'Failed to assign point.';
    }
};

const startSelectedGame = async () => {
    if (!selectedGameId.value) return;

    try {
        const response = await startGame(selectedGameId.value);
        tennisGame.value = response;
    } catch (err: any) {
        error.value = err.response?.data?.message || 'Failed to start game.';
    }
};
</script>

<template>
    <Head title="Simulate Match" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-4xl mx-auto">

            <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">Simulate Match</h1>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-semibold text-green-400">Select Upcoming Game:</label>

                <div v-if="upcomingGames.length === 0" class="w-full border border-gray-300 dark:border-gray-700 rounded-md p-4 bg-gray-800 text-center">
                    <p class="text-gray-400 mb-3">No upcoming games found.</p>
                    <AdminOnly>
                        <Link :href="route('games.create')">
                            <Button class="bg-green-500 hover:bg-green-600 text-gray-900">
                                Create New Game
                            </Button>
                        </Link>
                    </AdminOnly>
                </div>

                <select
                    v-else
                    v-model="selectedGameId"
                    class="w-full border border-gray-300 dark:border-gray-700 rounded-md p-2 bg-gray-800 text-gray-300"
                >
                    <option value="" disabled>Select a game</option>
                    <option
                        v-for="game in upcomingGames"
                        :key="game.id"
                        :value="game.id"
                    >
                        {{ formatPlayerName(game, 'player1') }} vs {{ formatPlayerName(game, 'player2') }} –
                        {{ game.played_at
                                ? new Date(game.played_at).toLocaleString('en-GB', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                })
                                : '—'
                        }}
                    </option>
                </select>
            </div>

            <div v-if="tennisGame" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <AdminOnly>
                    <div v-if="canStartGame" class="col-span-full text-center mb-4">
                        <Button class="bg-green-500 hover:bg-green-600 text-gray-900" @click="startSelectedGame">
                            Start Game Now (Admin Override)
                        </Button>
                    </div>
                </AdminOnly>

                <div class="flex-1 border border-gray-300 dark:border-gray-700 rounded p-4 bg-white dark:bg-gray-900 text-center">
                    <div class="text-lg font-bold text-green-400">{{ formatPlayerName(tennisGame, 'player1') }}</div>
                    <div class="text-xl font-mono my-2 text-gray-800 dark:text-gray-200">
                        {{ tennisGame.display_scores.player1 }}
                    </div>
                    <div v-if="tennisGame.match_status === 'ongoing'">
                        <Button
                            class="w-full bg-green-500 hover:bg-green-600 text-gray-900"
                            @click="givePoint('player1')"
                            :disabled="canStartGame"
                        >
                            + Point
                        </Button>
                    </div>
                </div>

                <div class="flex-1 border border-gray-300 dark:border-gray-700 rounded p-4 bg-white dark:bg-gray-900 text-center">
                    <div class="text-lg font-bold text-yellow-400">{{ formatPlayerName(tennisGame, 'player2') }}</div>
                    <div class="text-xl font-mono my-2 text-gray-800 dark:text-gray-200">
                        {{ tennisGame.display_scores.player2 }}
                    </div>
                    <div v-if="tennisGame.match_status === 'ongoing'">
                    <Button
                        class="w-full bg-green-500 hover:bg-green-600 text-gray-900"
                        @click="givePoint('player2')"
                        :disabled="canStartGame"
                    >
                        + Point
                    </Button>
                    </div>
                </div>
            </div>

            <div v-if="tennisGame" class="text-center text-gray-800 dark:text-gray-200 mb-4">
                <div v-if="tennisGame.winner_id" class="text-lg font-bold text-red-600 mb-2">
                    Game Over! Winner: {{ formatPlayerName(tennisGame, 'winner') }}
                </div>
                <div><strong class="text-green-400">Status:</strong> {{ tennisGame.match_status }}</div>
                <div><strong class="text-green-400">Scheduled:</strong> {{ tennisGame.played_at ? new Date(tennisGame.played_at).toLocaleString('en-GB', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:false}) : '—' }}</div>
            </div>

            <div v-if="error" class="text-center text-red-600">{{ error }}</div>
        </div>

        <GameEndModal
            v-if="tennisGame?.winner_id"
            :game="tennisGame"
            :winner-name="winnerName"
            @close="selectedGameId = null; tennisGame = null"
        />
    </AppLayout>
</template>
