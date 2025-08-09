<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { getPlayers, Player, deletePlayer } from '@/api/players';
import { Button } from '@/components/ui/button';

const breadcrumbs = [
  {
    title: 'Players',
    href: route('players.index'),
  },
];

const players = ref<Player[]>([]);
const loading = ref(true);

const fetchPlayers = async () => {
  loading.value = true;
  try {
    players.value = await getPlayers();
  } catch {
  } finally {
    loading.value = false;
  }
};

const onDelete = async (id: number) => {
  if (!confirm('Are you sure you want to delete this player?')) return;
  try {
    await deletePlayer(id);
    await fetchPlayers();
  } catch {
  }
};

onMounted(async () => {
        await fetchPlayers();
    });
</script>

<template>
  <Head title="Players" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Players</h1>
      <Link :href="route('players.create')">
        <Button>Create Player</Button>
      </Link>
    </div>

    <table class="w-full table-auto border-collapse border border-gray-200">
      <thead>
        <tr class="bg-gray-100">
          <th class="border border-gray-300 px-4 py-2 text-left">First Name</th>
          <th class="border border-gray-300 px-4 py-2 text-left">Last Name</th>
          <th class="border border-gray-300 px-4 py-2 text-left">Country</th>
          <th class="border border-gray-300 px-4 py-2 text-left">Ranking</th>
          <th class="border border-gray-300 px-4 py-2 text-left">Points</th>
          <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="loading">
          <td colspan="6" class="text-center py-4">Loading...</td>
        </tr>
        <tr v-for="player in players" :key="player.id" class="hover:bg-gray-50">
          <td class="border border-gray-300 px-4 py-2">{{ player.first_name }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ player.last_name }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ player.country ?? '-' }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ player.rank ?? '-' }}</td>
          <td class="border border-gray-300 px-4 py-2">{{ player.points }}</td>
          <td class="border border-gray-300 px-4 py-2 text-center space-x-2">
            <Link :href="route('players.edit', { player: player.id })" v-if="player && player.id">
              <Button size="sm" variant="outline">Edit</Button>
            </Link>
            <Button size="sm" variant="destructive" @click="onDelete(player.id)">Delete</Button>
          </td>
        </tr>
        <tr v-if="!loading && players.length === 0">
          <td colspan="6" class="text-center py-4">No players found.</td>
        </tr>
      </tbody>
    </table>
  </AppLayout>
</template>
