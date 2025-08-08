import http from './http';

export interface Game {
  id: number;
  played_at: string;
  winner_id: number | null;
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
