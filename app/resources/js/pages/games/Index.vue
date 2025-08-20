<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, usePage, router, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import AdminOnly from '@/components/AdminOnly.vue';
import type { GamesPageProps, BreadcrumbItem } from '@/types';

const page = usePage<GamesPageProps>();

const games = computed(() => page.props.games ?? []);

const gameIdQuery = ref<string | number | undefined>(undefined);
const player1IdQuery = ref<string | number | undefined>(undefined);
const player2IdQuery = ref<string | number | undefined>(undefined);

const deleting = ref<{ [key: number]: boolean }>({});

const submitSearch = () => {
	router.get(
		route('games.index'),
		{
			game_id: gameIdQuery.value ? Number(gameIdQuery.value) : undefined,
			player1_id: player1IdQuery.value ? Number(player1IdQuery.value) : undefined,
			player2_id: player2IdQuery.value ? Number(player2IdQuery.value) : undefined,
		},
		{ preserveState: true, preserveScroll: true }
	);
};

const deleteGame = (id: number) => {
	if (!confirm('Are you sure you want to delete this game?')) return;

	deleting.value[id] = true;

	router.delete(route('api.games.destroy', id), {
		preserveState: true,
		preserveScroll: true,
		onSuccess: () => {
			const index = games.value.findIndex(g => g.id === id);
			if (index !== -1) games.value.splice(index, 1);
		},
		onFinish: () => {
			deleting.value[id] = false;
		},
	});
};

const breadcrumbs: BreadcrumbItem[] = [
	{ title: 'Games', href: route('games.index') }
];
</script>

<template>
	<Head title="Games" />

	<AppLayout :breadcrumbs="breadcrumbs">
		<div class="p-6 max-w-4xl mx-auto">
			<h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">Games</h1>

			<form @submit.prevent="submitSearch" class="flex gap-4 items-center mb-6 flex-wrap">
				<Input
					type="number"
					min="1"
					v-model="gameIdQuery"
					placeholder="Game ID"
					class="flex-grow max-w-xs dark:bg-gray-800 dark:text-gray-100"
				/>
				<Input
					type="number"
					min="1"
					v-model="player1IdQuery"
					placeholder="Player 1 ID"
					class="flex-grow max-w-xs dark:bg-gray-800 dark:text-gray-100"
				/>
				<Input
					type="number"
					min="1"
					v-model="player2IdQuery"
					placeholder="Player 2 ID"
					class="flex-grow max-w-xs dark:bg-gray-800 dark:text-gray-100"
				/>
				<Button
					type="submit"
					class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
				>
					Search
				</Button>
			</form>

			<div v-if="games.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				<div
					v-for="game in games"
					:key="game.id"
					class="border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-4 rounded shadow hover:shadow-lg cursor-pointer relative transition"
				>
					<p class="text-gray-800 dark:text-gray-200"><strong>Game ID:</strong> {{ game.id }}</p>
					<p class="text-gray-800 dark:text-gray-200"><strong>Played At:</strong> {{ new Date(game.played_at).toLocaleString() }}</p>
					<p class="text-gray-800 dark:text-gray-200"><strong>Winner ID:</strong> {{ game.winner_id ?? 'N/A' }}</p>
					<p class="text-gray-800 dark:text-gray-200"><strong>Match Status:</strong> {{ game.match_status ?? 'N/A' }}</p>
					<p class="text-gray-800 dark:text-gray-200"><strong>Player 1:</strong> {{ game.player1 ? `${game.player1.first_name} ${game.player1.last_name}` : 'N/A' }}</p>
					<p class="text-gray-800 dark:text-gray-200"><strong>Player 2:</strong> {{ game.player2 ? `${game.player2.first_name} ${game.player2.last_name}` : 'N/A' }}</p>

					<AdminOnly>
						<div class="mt-4 flex gap-2 justify-end">
							<Link
								:href="route('games.edit', game.id)"
								class="inline-block px-3 py-1 text-sm bg-green-600 hover:bg-green-700 text-white rounded"
							>
								Edit
							</Link>
							<Button
								type="button"
								@click="deleteGame(game.id)"
								:disabled="deleting[game.id]"
								class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
							>
								{{ deleting[game.id] ? 'Deleting...' : 'Delete' }}
							</Button>
						</div>
					</AdminOnly>
				</div>
			</div>

			<div v-else class="text-center text-gray-500 dark:text-gray-400 mt-6">
				No games found.
			</div>
		</div>
	</AppLayout>
</template>
