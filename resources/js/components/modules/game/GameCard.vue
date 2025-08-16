<template>
    <div class="card rounded-2xl bg-white border hover:shadow-lg transition-shadow px-8 py-6">
        <div class="card-header">
            <div class="mb-4">
                <div class="flex justify-between gap-4 mb-4">
                    <h3 class="text-xl font-semibold line-clamp-1" :title="game.name">{{ game.name }}</h3>
                    <Badge :class="getStatusBadgeClass(game.status)">{{ getStatusText(game.status) }}</Badge>
                </div>
                <p class="text-gray-600 mt-1 line-clamp-1" :title="game.description">{{ game.description }}</p>
            </div>
        </div>
        <div class="card-content">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-600">
                        <UsersIcon class="w-4 h-4 mr-1" />
                        {{ game?.players_count ?? 0 }}/{{ game.max_players }} jogadores
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <ClockIcon class="w-4 h-4 mr-1" />
                        {{ formatDateTime(game.game_data?.start_datetime ?? '') }}
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-600">
                        <span class="">Taxa:</span>
                        <span class="font-semibold ml-1">
                            R$ 10,00
                        </span>
                    </div>

                    <div class="flex items-center text-sm text-green-600 font-semibold">
                        <TrophyIcon class="w-4 h-4 mr-1" />
                        Iphone 15
                    </div>
                </div>


                <div class="flex gap-2 mt-4">
                    <Link v-if="game.status === 'active'" :href="route('games.show', game.id)"
                        class="btn btn-success flex-1">
                    Participar
                    </Link>
                    <Link v-if="game.status === 'active'" :href="route('games.show', game.id)"
                        class="btn btn-primary flex-1">
                    Assistir
                    </Link>
                    <Link
                        v-if="game.status === 'inactive' || game.status === 'archived' || game.status === 'completed'"
                        :href="route('games.show', game.id)"
                        class="btn btn-outline flex-1"
                    >
                        Ver Resultados
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Game } from '@/types/models';
import { Link } from '@inertiajs/vue3';
import { formatDateTime, getStatusBadgeClass, getStatusText } from '@/lib/utils';
import { User } from '@/types';
import { UsersIcon, ClockIcon } from 'lucide-vue-next';
import Badge from '@/components/ui/badge/Badge.vue';


interface Props {
    game: Game;
    user?: User;
}

const props = defineProps<Props>();
const { game } = props;


</script>
