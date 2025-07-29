<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Game, ResourcePaginated } from '@/types/models';
import { Head, Link } from '@inertiajs/vue3';
import GameCard from '@/components/modules/game/GameCard.vue';
import { Plus, Settings } from 'lucide-vue-next';

interface Props {
    games: ResourcePaginated<Game>;
}

const props = defineProps<Props>()//, {
//     games: () => []
// });

</script>

<template>

    <Head title="Dashboard" />

    <AppLayout>
        <header class="flex justify-between items-center mb-6">
            <div class="">
                <h1 class="text-2xl font-bold mb-4">Bingo Online</h1>
                <p class="text-gray-600">Participe dos melhores jogos de bingo online!</p>
            </div>

            <div class="flex items-center gap-4">
                <Link :href="route('admin')"
                    class="btn btn-secondary inline-flex items-center">
                <Settings class="w-4 h-4 mr-2" />
                <span>Admin</span>
                </Link>
                <Link href="/games/create"
                    class="btn btn-primary inline-flex items-center">
                <Plus class="w-4 h-4 mr-2" />
                <span>Criar Jogo</span>
                </Link>
            </div>
        </header>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <GameCard v-for="game in games.data" :key="game.id" :game="game" />
        </div>
    </AppLayout>
</template>
