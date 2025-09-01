<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { GamesPageProps, BreadcrumbItem, Game } from '@/types';
import { getTennisGame, assignPoint, startGame } from '@/api/matches';
import { Button } from '@/components/ui/button';
import AdminOnly from '@/components/AdminOnly.vue';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/' },
  { title: 'Simulate Match', href: '/simulate' },
];

const page = usePage<GamesPageProps>();
const upcomingGames = computed(() => page.props.games ?? []);
const selectedGameId = ref<number | null>(null);
const tennisGame = ref<Game | null>(null);
const error = ref<string | null>(null);

const formatPlayerName = (game: Game | null, side: 'player1' | 'player2' | 'winner') => {
  if (!game) return 'N/A';

  if (side === 'winner') {
    if (!game.winner) return '—';
    if (game.player1?.id === game.winner) return `${game.player1.first_name} ${game.player1.last_name}`;
    if (game.player2?.id === game.winner) return `${game.player2.first_name} ${game.player2.last_name}`;
    return 'N/A';
  }

  const player = game[side];
  return player ? `${player.first_name} ${player.last_name}` : 'N/A';
};


// assign point
const givePoint = async (player: 1 | 2) => {
  if (!tennisGame.value) return;
  try {
    const res = await assignPoint(tennisGame.value.id, player);
    tennisGame.value = res.data;
  } catch {
    error.value = 'Failed to assign point';
  }
};

// start game (admin only)
const startGameNow = async () => {
  if (!selectedGameId.value || !tennisGame.value) return;
  try {
    const res = await startGame(selectedGameId.value);
    tennisGame.value = res.data;
  } catch {
    error.value = 'Failed to start game';
  }
};
watch(selectedGameId, async (id) => {
  if (!id) {
    tennisGame.value = null;
    return;
  }

  try {
    const res = await getTennisGame(id);
    tennisGame.value = res.data;
  } catch (err: any) {
    error.value = err.message || 'Failed to fetch game';
  }
});
const canStartGame = computed(() => {
  if (!tennisGame.value) return false;
  if (!tennisGame.value.played_at) return false;
  return new Date() >= new Date(tennisGame.value.played_at);
});

</script>

<template>
    <Head title="Simulate Match" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-4xl mx-auto">

            <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">Simulate Match</h1>

            <!-- Game Selection -->
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

            <!-- Selected Game Details -->
            <div v-if="tennisGame" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <AdminOnly>
                    <div v-if="!canStartGame" class="col-span-full text-center mb-4">
                        <Button class="bg-green-500 hover:bg-green-600 text-gray-900" @click="startGameNow">
                            Start Game Now (Admin Override)
                        </Button>
                    </div>
                </AdminOnly>

                <div class="flex-1 border border-gray-300 dark:border-gray-700 rounded p-4 bg-white dark:bg-gray-900 text-center">
                    <div class="text-lg font-bold text-green-400">{{ formatPlayerName(tennisGame, 'player1') }}</div>
                    <div class="text-xl font-mono my-2 text-gray-800 dark:text-gray-200">
                        {{ tennisGame.match_status === 'ongoing' ? tennisGame.player1_points : '—' }}
                    </div>
                    <Button
                        class="w-full bg-green-500 hover:bg-green-600 text-gray-900"
                        @click="givePoint(1)"
                        :disabled="!canStartGame || tennisGame.game_over"
                    >
                        + Point
                    </Button>
                </div>

                <div class="flex-1 border border-gray-300 dark:border-gray-700 rounded p-4 bg-white dark:bg-gray-900 text-center">
                    <div class="text-lg font-bold text-yellow-400">{{ formatPlayerName(tennisGame, 'player2') }}</div>
                    <div class="text-xl font-mono my-2 text-gray-800 dark:text-gray-200">
                        {{ tennisGame.match_status === 'ongoing' ? tennisGame.player2_points : '—' }}
                    </div>
                    <Button
                        class="w-full bg-green-500 hover:bg-green-600 text-gray-900"
                        @click="givePoint(2)"
                        :disabled="!canStartGame || tennisGame.game_over"
                    >
                        + Point
                    </Button>
                </div>
            </div>

            <div v-if="tennisGame" class="text-center text-gray-800 dark:text-gray-200 mb-4">
                <div v-if="tennisGame.game_over" class="text-lg font-bold text-red-600 mb-2">
                    Game Over! Winner: {{ formatPlayerName(tennisGame, 'winner') }}
                </div>
                <div><strong class="text-green-400">Status:</strong> {{ tennisGame.match_status }}</div>
                <div><strong class="text-green-400">Scheduled:</strong> {{ tennisGame.played_at ? new Date(tennisGame.played_at).toLocaleString('en-GB', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:false}) : '—' }}</div>
            </div>

            <div v-if="error" class="text-center text-red-600">{{ error }}</div>
        </div>

        <GameEndModal
            v-if="tennisGame?.game_over"
            :game="tennisGame"
            :winner-name="formatPlayerName(tennisGame, 'winner')"
            @close="selectedGameId = null; tennisGame = null"
        />
    </AppLayout>
</template>

