import http from './http';

export interface Player {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  rank: 'Silver' | 'Gold' | 'Platinum' | null;
  games_won: number;
  points: number;
  country: string | null;
  created_at?: string;
  updated_at?: string;
}

export const getPlayers = async (): Promise<Player[]> => {
  const response = await http.get('/players');
  return response.data;
};

export const getPlayerById = async (id: number): Promise<Player> => {
  const response = await http.get(`/players/${id}`);
  return response.data;
};

export const createPlayer = async (playerData: Partial<Player>): Promise<Player> => {
  const response = await http.post('/players', playerData);
  return response.data;
};

export const updatePlayer = async (id: number, playerData: Partial<Player>): Promise<Player> => {
  const response = await http.put(`/players/${id}`, playerData);
  return response.data;
};

export const deletePlayer = async (id: number): Promise<void> => {
  await http.delete(`/players/${id}`);
};
