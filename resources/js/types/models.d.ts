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
    game_data: GameData;
    created_at: string;
}

export interface GameMode {
    id: string;
    name: string;
    code: string;
    description: string;
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

export interface GameForm {
    [key: string]: any;
    id?: string;
    name: string;
    description?: string;
    max_players: string;
    is_free: true;
    entry_fee: number;
    awards: {
        name: string;
        description: string;
        value: number;
        order: number;
        winning_type: string;
        type: string;
    }[];
    game_data: GameData
}

export interface GameData {
    game_mode?: string;
    start_datetime: string | undefined;
    automatic?: false;
    draw_speed?: number;
    pause_between_prizes?: number;
    game_time_duration?: number;
    max_cards_per_player?: number;
    min_players_to_start?: number;
    allow_entry_after_start?: true;
    max_entry_time_limit?: number;
    auto_verify_bingo?: false;
    enable_chat?: false;
    enable_sound?: false;
    enable_replay?: false;
}
