<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { getGameById, createGame, Game } from '@/api/games';

// We'll start with a dummy game id or create a new game on mount
const game = ref<Game | null>(null);
const loading = ref(false);
const error = ref<string | null>(null);

// Example game id for testing — you can replace or fetch dynamically later
const testGameId = 1;

const fetchGame = async (id: number) => {
  loading.value = true;
  error.value = null;
  try {
    game.value = await getGameById(id);
  } catch {
    error.value = 'Failed to load game';
  } finally {
    loading.value = false;
  }
};

const createNewGame = async () => {
  loading.value = true;
  error.value = null;
  try {
    const newGame = await createGame({
        played_at: new Date().toISOString(),
        winner_id: null,
        match_status: 'ongoing',
        player1_id: 1,
        player2_id: 2,
    });
    game.value = newGame;
  } catch {
    error.value = 'Failed to create game';
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  // Try to fetch test game, if not exist create a new one
  await fetchGame(testGameId);
  if (!game.value) {
    await createNewGame();
  }
});
</script>

<template>
  <Head title="Gameplay" />

  <AppLayout :breadcrumbs="[{ title: 'Gameplay', href: route('game.play') }]">
    <div class="p-6">
      <h1 class="text-3xl font-bold mb-4">Gameplay</h1>

      <div v-if="loading" class="text-center text-gray-500">Loading game data...</div>
      <div v-if="error" class="text-red-600">{{ error }}</div>

      <div v-if="game" class="space-y-4">
        <p><strong>Game ID:</strong> {{ game.id }}</p>
        <p><strong>Played At:</strong> {{ new Date(game.played_at).toLocaleString() }}</p>
        <p><strong>Winner ID:</strong> {{ game.winner_id ?? 'N/A' }}</p>
        <p><strong>Match Status:</strong> {{ game.match_status ?? 'N/A' }}</p>
        <p><strong>Player 1:</strong> {{ game.player1 ? `${game.player1.first_name} ${game.player1.last_name}` : 'N/A' }}</p>
        <p><strong>Player 2:</strong> {{ game.player2 ? `${game.player2.first_name} ${game.player2.last_name}` : 'N/A' }}</p>
        <!-- Add more gameplay UI here later -->
      </div>
    </div>
  </AppLayout>
</template>
