import { ref } from 'vue';
import { getGames } from '@/api/games';
import { assignPoint } from '@/api/matches';
import { Game } from '@/types';

export function useGames() {
  const games = ref<Game[]>([]);
  const loading = ref(false);
  const error = ref<string | null>(null);

  async function fetchGames() {
    loading.value = true;
    try {
      const data = await getGames();
      games.value = data;
    } catch (err: any) {
      error.value = err.message || 'Failed to load games';
    } finally {
      loading.value = false;
    }
  }

  async function awardPoint(gameId: number, playerId: number) {
    try {
      const { data } = await assignPoint(gameId, playerId);
      const index = games.value.findIndex(g => g.id === gameId);
      if (index !== -1) games.value[index] = data;
      return data;
    } catch (err: any) {
      throw new Error(err.message || 'Failed to award point');
    }
  }

  return {
    games,
    loading,
    error,
    fetchGames,
    awardPoint,
  };
}
