<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import type { User, Player, PlayerPageProps } from '@/types';

const page = usePage<PlayerPageProps>();
const authUser = page.props.auth.user as User;
const isAdmin = authUser.is_admin === true;

// Only defined for edit
const player = page.props.player as Player | null;

// Users list only relevant for admin create
const users = ref<User[]>([]);

onMounted(() => {
    if (!player && isAdmin && page.props.users) {
        users.value = page.props.users;
    }
});

// Initialize form
const form = useForm({
    first_name: player?.first_name ?? '',
    last_name: player?.last_name ?? '',
    rank: player?.rank ?? '',
    country: player?.country ?? '',
    email: player?.user?.email ?? (isAdmin ? '' : authUser.email),
    user_id: player?.user?.id ?? (isAdmin ? '' : authUser.id),
});

// Auto-fill first/last/email when admin selects a user (only relevant for create)
if (!player && isAdmin) {
    watch(() => form.user_id, (newId) => {
        const selectedUser = users.value.find(u => u.id === newId);
        if (selectedUser) {
            const parts = selectedUser.name.split(' ');
            form.first_name = parts[0] ?? '';
            form.last_name = parts.slice(1).join(' ') ?? '';
            form.email = selectedUser.email ?? '';
        }
    });
}

const submit = () => {
    if (player) {
        form.put(route('players.update', player.id));
    }
}
</script>

<template>
<AppLayout>
    <Head :title="player ? `Edit Player #${player.id}` : 'Create Player'" />

    <div class="max-w-lg mx-auto p-6 bg-gray-900 shadow rounded">
        <h1 class="text-2xl font-bold mb-4 text-green-400">
            {{ player ? 'Edit Player Profile' : 'Create Player Profile' }}
        </h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div v-if="player">
                <p class="text-gray-400 mb-2">Player ID: {{ player.id }}</p>
            </div>

            <div>
                <label for="first_name" class="block font-semibold text-gray-300">First Name</label>
                <input v-model="form.first_name" id="first_name" type="text" class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-200" />
                <div v-if="form.errors.first_name" class="text-green-400 text-sm">{{ form.errors.first_name }}</div>
            </div>

            <div>
                <label for="last_name" class="block font-semibold text-gray-300">Last Name</label>
                <input v-model="form.last_name" id="last_name" type="text" class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-200" />
                <div v-if="form.errors.last_name" class="text-green-400 text-sm">{{ form.errors.last_name }}</div>
            </div>

            <div>
                <label for="rank" class="block font-semibold text-gray-300">Rank</label>
                <select v-model="form.rank" id="rank" class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-200">
                    <option value="">Select Rank</option>
                    <option value="Silver">Silver</option>
                    <option value="Gold">Gold</option>
                    <option value="Platinum">Platinum</option>
                </select>
                <div v-if="form.errors.rank" class="text-green-400 text-sm">{{ form.errors.rank }}</div>
            </div>

            <div>
                <label for="country" class="block font-semibold text-gray-300">Country</label>
                <input v-model="form.country" id="country" type="text" class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-200" />
                <div v-if="form.errors.country" class="text-green-400 text-sm">{{ form.errors.country }}</div>
            </div>

            <!-- Only show user dropdown for admin create -->
            <div v-if="!player && isAdmin">
                <label for="user_id" class="block font-semibold text-gray-300">Select User</label>
                <select v-model="form.user_id" id="user_id" class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-200">
                    <option value="">Select a user</option>
                    <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }} ({{ u.email }})</option>
                </select>
                <div v-if="form.errors.user_id" class="text-green-400 text-sm">{{ form.errors.user_id }}</div>
            </div>

            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-green-500 text-gray-900 rounded hover:bg-green-600 disabled:opacity-50">
                {{ player ? 'Update Player' : 'Create Player' }}
            </button>
        </form>
    </div>
</AppLayout>
</template>
