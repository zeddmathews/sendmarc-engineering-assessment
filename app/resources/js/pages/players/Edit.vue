<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Player } from '@/types';

const props = defineProps<{ player: Player }>();

const form = useForm({
    name: props.player.first_name + ' ' + props.player.last_name,
    country: props.player.country ?? '',
});

const submit = () => {
    form.put(route('players.update', props.player.id));
};
</script>

<template>
    <AppLayout>
        <Head :title="`Edit Player #${props.player.id}`" />

        <div class="max-w-lg mx-auto p-6 bg-gray-900 shadow rounded">
            <h1 class="text-2xl font-bold mb-4 text-green-400">Edit Player Profile</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="name" class="block font-semibold text-gray-300">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        id="name"
                        class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-300"
                    />
                    <div v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</div>
                </div>

                <div>
                    <label for="country" class="block font-semibold text-gray-300">Country</label>
                    <input
                        v-model="form.country"
                        type="text"
                        id="country"
                        class="w-full border border-gray-700 rounded px-3 py-2 bg-gray-800 text-gray-300"
                    />
                    <div v-if="form.errors.country" class="text-red-500 text-sm">{{ form.errors.country }}</div>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-4 py-2 bg-green-500 text-gray-900 rounded hover:bg-green-600 disabled:opacity-50"
                >
                    Update Player
                </button>
            </form>
        </div>
    </AppLayout>
</template>
