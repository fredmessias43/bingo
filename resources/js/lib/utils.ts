import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';
import { format } from 'date-fns';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function formatDate(dateString: string): string {
    if (! dateString) return "--/--/----";

    const date = new Date(dateString);
    return format(date, 'dd/MM/yyyy');
}

export function formatTime(dateString: string): string {
    if (! dateString) return "--:--";

    const date = new Date(dateString);
    return format(date, 'HH:mm');
}

export function formatDateTime(dateString: string): string {
    if (! dateString) return "--/--/---- --:--";

    const date = new Date(dateString);
    return format(date, 'dd/MM/yyyy HH:mm');
}

// active
// inactive
// archived
// completed

export function getStatusBadgeClass(status: string) {
    switch (status) {
        case "active":
            return "bg-green-500 text-black";
        case "inactive":
            return "bg-yellow-500 text-black";
        case "archived":
            return "bg-gray-500 text-white";
        case "completed":
            return "bg-blue-500 text-white";
        default:
            return "bg-gray-300 text-black";
    }
}

export function getStatusText(status: string) {
    switch (status) {
        case "active":
            return "Ativo";
        case "inactive":
            return "Inativo";
        case "archived":
            return "Arquivado";
        case "completed":
            return "Concluído";
        default:
            return "Desconhecido";
    }
}
