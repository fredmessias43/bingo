export interface Resource<T> {
    data: T;
}

export interface ResourcePaginated<T> {
    data: T[];
    meta: Pagination;
}

export interface Pagination {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}



export interface User {
    id: string;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
}

export interface Award {
    id: string;
    name: string;
    description: string;
    image_url: string;
}

export interface Card {
    id: string;
    game_id: string;
    player_id: string;
    numbers: string[];
}

export interface Game {
    id: string;
    name: string;
    description: string;
    max_players: number;
    players_count?: number;
    status: 'active' | 'inactive' | 'archived' | 'completed';
    created_at: string;
}

export interface DrawnNumber {
    id: string;
    game_id: string;
    number: string;
}

export interface Player {
    id: string;
    name: string;
    email: string;
    user_id: string;
}

export interface PlayerInvite {
    id: string;
    player_id: string;
    game_id: string;
    email: string;
    status: 'pending' | 'accepted' | 'declined';
}
