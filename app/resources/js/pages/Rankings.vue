<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import type { Player, BreadcrumbItem } from '@/types';
import { getPlayers } from '@/api/players';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Rankings', href: '/rank' },
];

const players = ref<Player[]>([]);
const loading = ref(true);

const fetchPlayers = async () => {
    loading.value = true;
    try {
        players.value = await getPlayers();
    } finally {
        loading.value = false;
    }
};

onMounted(fetchPlayers);

const rankedPlayers = computed(() =>
    [...players.value].sort((a, b) => (b.points ?? 0) - (a.points ?? 0))
);
</script>

<template>
    <Head title="Rankings" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4 rounded-xl overflow-x-auto">
            <div class="relative min-h-[80vh] rounded-xl border border-sidebar-border/70 dark:border-sidebar-border dark:bg-gray-900 p-4">
                <h1 class="text-2xl font-bold text-green-400 mb-4">Player Rankings</h1>

                <table class="w-full table-auto border-collapse border border-gray-700 dark:text-gray-100">
                    <thead>
                        <tr class="bg-gray-800 text-green-400">
                            <th class="border border-gray-700 px-4 py-2 text-left">Rank</th>
                            <th class="border border-gray-700 px-4 py-2 text-left">Player</th>
                            <th class="border border-gray-700 px-4 py-2 text-left">Country</th>
                            <th class="border border-gray-700 px-4 py-2 text-left">Points</th>
                            <th class="border border-gray-700 px-4 py-2 text-left">Games Won</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <td colspan="5" class="text-center py-4 text-gray-300">Loading...</td>
                        </tr>

                        <tr
                            v-for="(player, index) in rankedPlayers"
                            :key="player.id"
                            class="border-b border-gray-700 hover:bg-gray-700"
                        >
                            <td class="px-4 py-2 font-semibold text-green-400">{{ index + 1 }}</td>
                            <td class="px-4 py-2">{{ player.first_name }} {{ player.last_name }}</td>
                            <td class="px-4 py-2">{{ player.country ?? '-' }}</td>
                            <td class="px-4 py-2">{{ player.points }}</td>
                            <td class="px-4 py-2">{{ player.games_won }}</td>
                        </tr>

                        <tr v-if="!loading && rankedPlayers.length === 0">
                            <td colspan="5" class="text-center py-4 text-gray-300">No players found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
