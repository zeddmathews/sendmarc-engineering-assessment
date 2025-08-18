import http from './http';
import { type User } from '@/types';

export const getUsers = async (): Promise<User[]> => {
    const response = await http.get('/users');
    return response.data;
};
