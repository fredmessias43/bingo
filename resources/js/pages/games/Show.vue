<template>
    <Head :title="'Game ' + game.data.name" />

    <div>
        <div class="border-b-2 shadow-sm">
            <header class="container mx-auto flex justify-between items-center py-4">
                <div class="flex items-center gap-4">
                    <Link :href="route('dashboard')" class="btn">
                        <ArrowLeftIcon class="w-4 h-4 mr-2" />
                        <span class="w-max">Sair do jogo</span>
                    </Link>

                    <div>
                        <h1 class="text-2xl font-bold">{{ game.data.name }}</h1>
                        <p class="text-sm text-gray-600 line-clamp-1 w-1/2">{{ game.data.description }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <Badge :class="getStatusBadgeClass(game.data.status)">{{ getStatusText(game.data.status) }}</Badge>
                    <button class="btn btn-primary w-full flex items-center justify-center">
                        <PlayIcon class="w-4 h-4 mr-2" />
                        <span class="w-max">Iniciar Jogo</span>
                    </button>
                </div>
            </header>
        </div>

        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Coluna 1 - info -->
                <section>
                    <div class="card flex flex-col items-center gap-4 p-6">
                        <h1>Numero Sorteado</h1>
                        <DrawnNumber v-if="lastDrawnNumber" :number="lastDrawnNumber.number" size="large" class="mb-4" />
                        <p v-if="lastDrawnNumber" class="text-gray-600">Número {{ totalDrawn }} de 75</p>
                        <p v-else class="text-gray-400">Aguardando sorteio...</p>
                    </div>

                    <div class="card p-6 mt-6">
                        <h2 class="mb-4">Números Sorteados</h2>
                        <div class="flex flex-wrap items-center gap-4">
                            <DrawnNumber
                                v-for="drawn in drawnNumbers"
                                :key="drawn.id"
                                :number="drawn.number"
                                size="small"
                            />
                            <p v-if="drawnNumbers.length === 0" class="text-gray-400 text-sm">Nenhum número sorteado ainda</p>
                        </div>
                    </div>

                    <div class="card p-6 mt-6">
                        <h2 class="mb-4">
                            <UsersIcon class="w-4 h-4 inline-block mr-2" />
                            <span>Jogadores online</span>
                        </h2>
                        <div class="flex flex-col gap-2 pe-4 max-h-40 overflow-y-auto">
                            <div class="flex items-center justify-between gap-2" v-for="index in 10" :key="index">
                                <div class="w-max flex items-center gap-2">
                                    <UserIcon class="w-4 h-4" />
                                    <span class="text-gray-700">Jogador {{ index }}</span>
                                </div>
                                <div class="rounded-full py-0.5 px-2 text-sm h-min border border-gray-300 flex items-center">
                                    <span class="text-xs text-gray-500">2 cartelas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Coluna 2 - cartelas -->
                <section class="col-span-2">
                    <div class="card p-6">
                        <h2 class="mb-4">Cartelas</h2>
                        <div class="flex flex-wrap justify-between gap-4">
                            <template v-for="(card, cardIndex) in 3" :key="cardIndex">
                                <div class="bg-white shadow rounded-lg p-4 w-72">
                                    <h3 class="text-xl font-semibold mb-2 text-center">Cartela #{{ cardIndex + 1 }}</h3>
                                    <div class="grid grid-cols-5 gap-2 text-center font-bold text-lg text-purple-600">
                                        <span class="py-2">B</span>
                                        <span class="py-2">I</span>
                                        <span class="py-2">N</span>
                                        <span class="py-2">G</span>
                                        <span class="py-2">O</span>
                                    </div>
                                    <div class="grid grid-rows-5 grid-cols-5 grid-flow-col gap-2">
                                        <template v-for="(number, numberIndex) in 25" :key="number">
                                            <button
                                                v-if="numberIndex !== 12"
                                                type="button"
                                                class="rounded flex items-center justify-center h-10 w-10 text-lg font-bold"
                                                :class="getClassForNumber(card, number)"
                                                @click="onClickCardNumber(card, number)"
                                            >
                                                {{ number }}
                                            </button>
                                            <div v-else class="rounded flex items-center justify-center h-10 w-10 text-lg font-bold bg-gray-200">
                                                <PersonStanding />
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import DrawnNumber from '@/components/modules/game/DrawnNumber.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import { getStatusBadgeClass, getStatusText } from '@/lib/utils';
import type { DrawnNumber as DrawnNumberType, Game, Resource } from '@/types/models';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeftIcon, PersonStanding, PlayIcon, UserIcon, UsersIcon } from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

interface Props {
    game: Resource<Game>;
}

const props = defineProps<Props>();

const drawnNumbers = ref<DrawnNumberType[]>([]);
const totalDrawn = ref(0);

const lastDrawnNumber = computed(() => drawnNumbers.value[drawnNumbers.value.length - 1] ?? null);

const drawnNumberSet = computed(() => new Set(drawnNumbers.value.map((d) => d.number)));

function getClassForNumber(_card: number, number: number): string {
    return drawnNumberSet.value.has(String(number)) ? 'bg-primary text-primary-foreground' : 'bg-gray-200';
}

function onClickCardNumber(_card: number, _number: number): void {}

onMounted(() => {
    window.Echo.private(`game.${props.game.data.id}`).listen('NumberDrawn', (e: { drawn_number: DrawnNumberType; total_drawn: number }) => {
        drawnNumbers.value.push(e.drawn_number);
        totalDrawn.value = e.total_drawn;
    });
});

onUnmounted(() => {
    window.Echo.leave(`game.${props.game.data.id}`);
});
</script>

<style lang="scss" scoped></style>
