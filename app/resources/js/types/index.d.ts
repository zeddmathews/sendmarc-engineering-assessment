import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    children?: NavItem[];
    adminOnly?: boolean;
}

export interface Game {
    id: number;
    played_at: string;
    winner_id: number | null;
    player1_id?: number;
    player2_id?: number;
    match_status: 'upcoming' | 'ongoing' | 'completed' | 'cancelled' | 'tied';
    player1?: Player;
    player2?: Player;
    player1_points: number;
    player2_points: number;
    created_at?: string;
    updated_at?: string;
    winner?: Player | null;
}

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
    user?: User;
}

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

export interface GamesPageProps extends AppPageProps {
    games: Game[];
}


export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    is_admin: boolean;
}

export interface PlayerPageProps extends AppPageProps {
    users?: User[];
}

export type BreadcrumbItemType = BreadcrumbItem;
