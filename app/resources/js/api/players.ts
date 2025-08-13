import http from './http';
import { type Player } from '@/types';

export const createPlayer = async (playerData: Partial<Player>): Promise<Player> => {
    const response = await http.post('/players', playerData);
    return response.data;
};

export const getPlayers = async (): Promise<Player[]> => {
    const response = await http.get('/players');
    return response.data;
};

export const getPlayerById = async (id: number): Promise<Player> => {
    const response = await http.get(`/players/${id}`);
    return response.data;
};

export const updatePlayer = async (id: number, playerData: Partial<Player>): Promise<Player> => {
    const response = await http.put(`/players/${id}`, playerData);
    return response.data;
};

export const deletePlayer = async (id: number): Promise<void> => {
    await http.delete(`/players/${id}`);
};
