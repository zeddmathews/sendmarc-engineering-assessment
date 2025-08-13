import http from './http';

export const getMatchHistory = () => http.get('/matches');
export const simulateMatch = (data: { player1_id: number; player2_id: number }) =>
    http.post('/matches/simulate', data);
