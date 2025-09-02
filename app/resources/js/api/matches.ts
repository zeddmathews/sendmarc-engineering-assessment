import http from './http';
import { Game } from '@/types';

export async function getUpcomingGames(): Promise<Game[]> {
    const response = await http.get('/games/upcoming');
    return response.data.data;
}

export async function getTennisGame(id: number): Promise<Game> {
    const response = await http.get(`/games/${id}`);
    return response.data.data;
}

export async function startGame(id: number): Promise<Game> {
    const response = await http.post(`/games/${id}/start`);
    return response.data.data;
}

export async function assignPoint(id: number, playerId: number): Promise<Game> {
    const response = await http.post(`/games/${id}/point`, { player_id: playerId });
    return response.data.data;
}
