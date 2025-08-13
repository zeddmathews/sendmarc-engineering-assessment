import http from './http';
import { type Game } from '@/types';

interface GameFilters {
    game_id?: number;
    player1_id?: number;
    player2_id?: number;
}

export const createGame = async (gameData: Partial<Game>): Promise<Game> => {
    const response = await http.post('/games', gameData);
    return response.data;
};

export const getGames = async (filters?: GameFilters): Promise<Game[]> => {
    const response = await http.get('/games', { params: filters });
    return response.data;
};

export const getGameById = async (id: number): Promise<Game> => {
    const response = await http.get(`/games/${id}`);
    return response.data;
};

export const updateGame = async (id: number, gameData: Partial<Game>): Promise<Game> => {
    const response = await http.put(`/games/${id}`, gameData);
    return response.data;
};

export const deleteGame = async (id: number): Promise<void> => {
    await http.delete(`/games/${id}`);
};
