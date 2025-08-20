<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import AdminOnly from '@/components/AdminOnly.vue'
import { type Game } from '@/types';

defineProps<{
  game: Game & any;
  winnerName: string | null;
}>();

const emit = defineEmits<{
  close: [];
}>();

const goDashboard = () => {
  router.visit('/dashboard')
}

const createNewGame = () => {
  router.visit('/games/create')
}

const closeModal = () => {
  emit('close');
}
</script>

<template>
  <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50" @click.self="closeModal">
    <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-lg p-6 w-full max-w-md">
      <h2 class="text-xl font-bold mb-4 text-green-400">Game Over! 🎾</h2>

      <div class="space-y-3 mb-6">
        <p class="text-gray-300">
          <span class="text-green-400 font-semibold">Winner:</span>
          <span class="font-semibold text-white">{{ winnerName || 'Unknown' }}</span>
        </p>

        <p class="text-gray-300">
          <span class="text-green-400 font-semibold">Final Score:</span>
          <span class="font-mono">{{ game.score?.player1 || '0' }} - {{ game.score?.player2 || '0' }}</span>
        </p>

        <p class="text-gray-300">
          <span class="text-green-400 font-semibold">Match:</span>
          {{ game.player1?.first_name }} {{ game.player1?.last_name }} vs {{ game.player2?.first_name }} {{ game.player2?.last_name }}
        </p>
      </div>

      <div class="flex justify-end gap-3">
        <Button
          variant="outline"
          class="border-gray-600 text-gray-300 hover:bg-gray-700"
          @click="goDashboard"
        >
          Return to Dashboard
        </Button>

        <AdminOnly>
          <Button
            class="bg-green-500 hover:bg-green-600 text-gray-900"
            @click="createNewGame"
          >
            Create New Game
          </Button>
        </AdminOnly>
      </div>
    </div>
  </div>
</template>
