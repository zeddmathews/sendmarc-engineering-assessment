import http from './http';

export const getCurrentUser = () => http.get('/user');
export const updateProfile = (data: Record<string, unknown>) => http.put('/user/profile', data);
export const logout = () => http.post('/logout');
