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
                        <p class="text-sm text-gray-600  line-clamp-1 w-1/2">{{ game.data.description }}</p>
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
            <div className="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Coluna 1 - info -->
                <section>
                    <div class="card flex flex-col items-center gap-4 p-6">
                        <h1>Numero Sorteado</h1>
                        <DrawnNumber :number="1" size="large" class="mb-4" />
                        <p className="text-gray-600">Número 1 de 75</p>
                    </div>
                    <div class="card p-6 mt-6">
                        <h2 class="mb-4">Números Sorteados</h2>
                        <div class="flex flex-wrap items-center gap-4">
                            <DrawnNumber :number="1" size="small" />
                            <DrawnNumber :number="2" size="small" />
                            <DrawnNumber :number="3" size="small" />
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
                                    <span class="text-xs text-gray-500 ">2 cartelas</span>
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

                            <template v-for="card, cardIndex in 3" :key="index">
                                <div class="bg-white shadow rounded-lg p-4 w-72">
                                    <h3 class="text-xl font-semibold mb-2 text-center">Cartela #{{ cardIndex + 1 }}</h3>

                                    <div class="grid grid-cols-5 gap-2 text-center font-bold text-lg text-purple-600">
                                        <span class="py-2">B</span>
                                        <span class="py-2">I</span>
                                        <span class="py-2">N</span>
                                        <span class="py-2">G</span>
                                        <span class="py-2">O</span>
                                    </div>
                                    <div class="grid  grid-rows-5 grid-cols-5 grid-flow-col gap-2">
                                        <template v-for="number, numberIndex in 25" :key="number">
                                            <button
                                                type="button"
                                                class="rounded flex items-center justify-center h-10 w-10 text-lg font-bold"
                                                :class="getClassForNumber(card, number)"
                                                @click="onClickCardNumber(card, number)"
                                                v-if="numberIndex != 12"
                                                >
                                                {{ number }}
                                            </button>
                                            <div v-else
                                                class="rounded flex items-center justify-center h-10 w-10 text-lg font-bold bg-gray-200"
                                            >
                                                <PersonStanding />
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </section>
                <!-- Coluna 3 - numeros sorteados -->
                <section>

                </section>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import DrawnNumber from '@/components/modules/game/DrawnNumber.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import { getStatusBadgeClass, getStatusText } from '@/lib/utils';
import { Game, Resource } from '@/types/models';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeftIcon, PersonStanding, PlayIcon, UserIcon, UsersIcon } from 'lucide-vue-next';

interface Props {
    game: Resource<Game>;
}

const props = defineProps<Props>();
const emit = defineEmits([]);

function getClassForNumber(card: number, number: number): string {
    const isSelected = false;
    if (isSelected) {
        return "";
    }
    return "bg-gray-200";
}

function onClickCardNumber(card: number, number: number): void {

}

</script>

<style lang="scss" scoped></style>
