import http from './http';
import { type PlayerStat } from '@/types';

export const getPlayerStats = async (playerId: number): Promise<PlayerStat[]> => {
    const response = await http.get(`/player-stats?player_id=${playerId}`);
    return response.data;
};

export const updatePlayerStat = async (id: number, statData: Partial<PlayerStat>): Promise<PlayerStat> => {
    const response = await http.put(`/player-stats/${id}`, statData);
    return response.data;
};
