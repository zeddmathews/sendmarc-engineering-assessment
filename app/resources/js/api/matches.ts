import http from './http';

export const getTennisGame = (gameId: number) =>
    http.get(`/games/${gameId}/play`);

export const assignPoint = (gameId: number, playerId: number) =>
    http.post(`/games/${gameId}/point`, { player_id: playerId });

export const getUpcomingGames = () =>
    http.get('/games/upcoming');

export const startGame = (gameId: number) =>
    http.post(`/games/${gameId}/start`);
