import http from './http';

export interface PlayerStat {
  id: number;
  player_id: number;
  game_id: number;
  aces: number;
  double_faults: number;
  first_serve_in: number;
  first_serve_out: number;
  points_won: number;
  break_points_won: number;
  created_at?: string;
  updated_at?: string;
}

export const getPlayerStats = async (playerId: number): Promise<PlayerStat[]> => {
  const response = await http.get(`/player-stats?player_id=${playerId}`);
  return response.data;
};

export const updatePlayerStat = async (id: number, statData: Partial<PlayerStat>): Promise<PlayerStat> => {
  const response = await http.put(`/player-stats/${id}`, statData);
  return response.data;
};
