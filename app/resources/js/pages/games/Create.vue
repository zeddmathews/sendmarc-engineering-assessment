<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { BreadcrumbItem } from '@/types';

const form = useForm({
    played_at: '',
    winner_id: null as number | null,
    match_status: 'upcoming',
    player1_id: null as number | null,
    player2_id: null as number | null,
});

const submit = () => {
    form.post(route('api.games.store'), {
        onSuccess: () => {
            alert('Game created successfully!');
            form.reset();
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Games', href: route('games.index') },
    { title: 'Create', href: '' },
];
</script>

<template>
  <Head title="Create Game" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-6 text-gray-900">Create a New Game</h1>
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                <label for="played_at" class="block text-sm font-semibold mb-2 text-gray-700">Played At (date/time):</label>
                <Input
                    id="played_at"
                    type="datetime-local"
                    v-model="form.played_at"
                    class="w-full"
                    required
                    :class="{ 'border-red-500': form.errors.played_at }"
                />
                <p v-if="form.errors.played_at" class="text-red-600 text-sm mt-1">{{ form.errors.played_at }}</p>
                </div>

                <div>
                <label for="match_status" class="block text-sm font-semibold mb-2 text-gray-700">Match Status:</label>
                <select
                    id="match_status"
                    v-model="form.match_status"
                    class="w-full border rounded px-3 py-2 text-gray-900"
                    required
                >
                    <option value="upcoming">Upcoming</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="tied">Tied</option>
                </select>
                <p v-if="form.errors.match_status" class="text-red-600 text-sm mt-1">{{ form.errors.match_status }}</p>
                </div>

                <div>
                <label for="player1_id" class="block text-sm font-semibold mb-2 text-gray-700">Player 1 ID:</label>
                <Input
                    id="player1_id"
                    type="number"
                    min="1"
                    :modelValue="form.player1_id ?? undefined"
                    @update:modelValue="val => {
                        if (val === undefined || val === '') {
                        form.player1_id = null;
                        } else {
                        form.player1_id = Number(val);
                        }
                    }"
                    placeholder="Enter Player 1 ID"
                    :class="{ 'border-red-500': form.errors.player1_id }"
                    />
                <p v-if="form.errors.player1_id" class="text-red-600 text-sm mt-1">{{ form.errors.player1_id }}</p>
                </div>

                <div>
                <label for="player2_id" class="block text-sm font-semibold mb-2 text-gray-700">Player 2 ID:</label>
                <Input
                    id="player2_id"
                    type="number"
                    min="1"
                    :modelValue="form.player2_id ?? undefined"
                    @update:modelValue="val => {
                        if (val === undefined || val === '') {
                        form.player2_id = null;
                        } else {
                        form.player2_id = Number(val);
                        }
                    }"
                    placeholder="Enter Player 2 ID"
                    :class="{ 'border-red-500': form.errors.player2_id }"
                    />
                <p v-if="form.errors.player2_id" class="text-red-600 text-sm mt-1">{{ form.errors.player2_id }}</p>
                </div>
                <Button type="submit" class="w-full" :disabled="form.processing">
                    {{ form.processing ? 'Creating...' : 'Create Game' }}
                </Button>
            </form>
        </div>
    </AppLayout>
</template>
