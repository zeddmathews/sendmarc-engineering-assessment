<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import type { Player, BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import AdminOnly from '@/components/AdminOnly.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Players', href: route('players.index') },
];

const players = ref<Player[]>([]);
const loading = ref(true);
const deleting = ref<{ [key: number]: boolean }>({});

const fetchPlayers = async () => {
    loading.value = true;
    try {
        const response = await fetch(route('api.players.index'), { headers: { Accept: 'application/json' } });
        const data = await response.json();
        players.value = data.data;
    } finally {
        loading.value = false;
    }
};

const onDelete = (id: number) => {
    if (!confirm('Are you sure you want to delete this player?')) return;

    deleting.value[id] = true;

    router.delete(route('players.destroy', id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            const index = players.value.findIndex(p => p.id === id);
            if (index !== -1) players.value.splice(index, 1);
        },
        onFinish: () => deleting.value[id] = false,
    });
};

onMounted(fetchPlayers);
</script>

<template>
    <Head title="Players" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-green-400">Players</h1>
            <AdminOnly>
                <Link :href="route('players.create')">
                    <Button class="bg-green-500 hover:bg-green-600 text-gray-900">Create Player</Button>
                </Link>
            </AdminOnly>
        </div>

        <table class="w-full table-auto border-collapse border border-gray-700">
            <thead>
                <tr class="bg-gray-800 text-green-400">
                    <th class="border border-gray-700 px-4 py-2 text-left">First Name</th>
                    <th class="border border-gray-700 px-4 py-2 text-left">Last Name</th>
                    <th class="border border-gray-700 px-4 py-2 text-left">Country</th>
                    <th class="border border-gray-700 px-4 py-2 text-left">Ranking</th>
                    <th class="border border-gray-700 px-4 py-2 text-left">Games Won</th>
                    <th class="border border-gray-700 px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="loading">
                    <td colspan="6" class="text-center py-4 text-gray-300">Loading...</td>
                </tr>

                <tr v-for="player in players" :key="player.id" class="hover:bg-gray-700">
                    <td class="border border-gray-700 px-4 py-2 text-gray-300">{{ player.first_name }}</td>
                    <td class="border border-gray-700 px-4 py-2 text-gray-300">{{ player.last_name }}</td>
                    <td class="border border-gray-700 px-4 py-2 text-gray-300">{{ player.country ?? '-' }}</td>
                    <td class="border border-gray-700 px-4 py-2 text-gray-300">{{ player.rank ?? '-' }}</td>
                    <td class="border border-gray-700 px-4 py-2 text-gray-300">{{ player.games_won ?? 0 }}</td>
                    <td class="border border-gray-700 px-4 py-2 text-center space-x-2">
                        <AdminOnly>
                            <Link :href="route('players.edit', player.id)">
                                <Button size="sm" variant="outline" class="border-green-400 text-green-400 hover:bg-green-500 hover:text-gray-900">Edit</Button>
                            </Link>
                            <Button size="sm" variant="destructive" class="bg-red-600 hover:bg-red-700" @click="onDelete(player.id)">Delete</Button>
                        </AdminOnly>
                    </td>
                </tr>

                <tr v-if="!loading && players.length === 0">
                    <td colspan="6" class="text-center py-4 text-gray-300">No players found.</td>
                </tr>
            </tbody>
        </table>
    </AppLayout>
</template>
