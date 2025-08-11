import http from './http';
import { Player } from './players';

export interface Game {
  id: number;
  played_at: string;
  winner_id: number | null;
  player1_id?: number;
  player2_id?: number;
  match_status: 'upcoming' | 'ongoing' | 'completed' | 'cancelled' | 'tied';
  player1?: Player;
  player2?: Player;
  created_at?: string;
  updated_at?: string;
}

export const createGame = async (gameData: Partial<Game>): Promise<Game> => {
  const response = await http.post('/games', gameData);
  return response.data;
};

export const getGameById = async (id: number): Promise<Game> => {
  const response = await http.get(`/games/${id}`);
  return response.data;
};
