import http from './http';

export const getTennisGame = (gameId: number) =>
    http.get(`/simulate-match/${gameId}`);

export const assignPoint = (gameId: number, playerId: number) =>
    http.post(`/simulate-match/${gameId}/point`, { player_id: playerId });

export const getUpcomingGames = () =>
    http.get('/games/upcomings');

export const startGame = (gameId: number) => {
    return http.post(`/games/${gameId}/start`);
};
