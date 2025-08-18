<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { type User } from '@/types';
import { getUsers } from '@/api/users';

const page = usePage();
const authUser = page.props.auth.user as User;
const isAdmin = authUser.is_admin === true;

const users = ref<User[]>([]);

onMounted(async () => {
    if (isAdmin) {
        users.value = await getUsers();
    }
});

const form = useForm({
    first_name: '',
    last_name: '',
    rank: '',
    country: '',
    email: isAdmin ? '' : authUser.email,
});

const submit = () => {
    form.post(route('players.store'));
};
</script>
<template>
    <AppLayout>
        <Head title="Create Player" />

        <div class="max-w-lg mx-auto p-6 bg-gray-900 shadow rounded">
            <h1 class="text-2xl font-bold mb-4 text-green-400">Create Player Profile</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="first_name" class="block font-semibold text-gray-300">First Name</label>
                    <input
                        v-model="form.first_name"
                        type="text"
                        id="first_name"
                        class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-200"
                    />
                    <div v-if="form.errors.first_name" class="text-green-400 text-sm">{{ form.errors.first_name }}</div>
                </div>

                <div>
                    <label for="last_name" class="block font-semibold text-gray-300">Last Name</label>
                    <input
                        v-model="form.last_name"
                        type="text"
                        id="last_name"
                        class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-200"
                    />
                    <div v-if="form.errors.last_name" class="text-green-400 text-sm">{{ form.errors.last_name }}</div>
                </div>

                <div>
                    <label for="rank" class="block font-semibold text-gray-300">Rank</label>
                    <select
                        v-model="form.rank"
                        id="rank"
                        class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-200"
                    >
                        <option value="">Select Rank</option>
                        <option value="Silver">Silver</option>
                        <option value="Gold">Gold</option>
                        <option value="Platinum">Platinum</option>
                    </select>
                    <div v-if="form.errors.rank" class="text-green-400 text-sm">{{ form.errors.rank }}</div>
                </div>

                <div>
                    <label for="country" class="block font-semibold text-gray-300">Country</label>
                    <input
                        v-model="form.country"
                        type="text"
                        id="country"
                        class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-200"
                    />
                    <div v-if="form.errors.country" class="text-green-400 text-sm">{{ form.errors.country }}</div>
                </div>

                <div v-if="isAdmin">
                    <label for="email" class="block font-semibold text-gray-300">Select User Email</label>
                    <select
                        v-model="form.email"
                        id="email"
                        class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-200"
                    >
                        <option value="">Select a user</option>
                        <option v-for="u in users" :key="u.id" :value="u.email">{{ u.name }} ({{ u.email }})</option>
                    </select>
                    <div v-if="form.errors.email" class="text-green-400 text-sm">{{ form.errors.email }}</div>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-4 py-2 bg-green-500 text-gray-900 rounded hover:bg-green-600 disabled:opacity-50"
                >
                    Create Player
                </button>
            </form>
        </div>
    </AppLayout>
</template>
