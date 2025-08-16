<template>
    <AppLayout>
        <div>
            <header class="container mx-auto flex justify-between items-center py-4">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('dashboard')"
                        class="btn"
                    >
                    <ArrowLeftIcon class="w-4 h-4 mr-2" />
                    <span class="w-max">Voltar</span>
                    </Link>

                    <div v-if="game?.data">
                        <h1 class="text-2xl font-bold">{{ game.data.name }}</h1>
                        <p class="text-sm text-gray-600  line-clamp-1 w-1/2">{{ game.data.description }}</p>
                    </div>

                    <div v-else>
                        <h1 class="text-2xl font-bold">🎮 Criar Novo Jogo</h1>
                        <p class="text-sm text-gray-600  line-clamp-1">Configure seu jogo de bingo personalizado</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span class="rounded-full py-0.5 px-2 text-sm h-min border flex items-center justify-between gap-2">
                        <Gamepad2Icon class="size-4" />
                        <span>Novo Jogo</span>
                    </span>
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Tabs -->
                <form
                    class="md:col-span-2 flex flex-col gap-4"
                    @submit.prevent="onSubmit"
                >
                    <div class="card flex items-center justify-between px-4 py-2">
                        <button
                            v-for="tab, index in tabs"
                            :key="tab['key']"
                            class="flex items-center gap-2 px-4 py-2"
                            type="button"
                            @click.prevent="clickTab(tab, index)"
                            :class="{
                                'text-gray-500': selectedTab != index,
                                'text-dark': selectedTab === index,
                            }"
                        >
                            <component :is="tab['icon']" />
                            <span>{{ tab['text'] }}</span>
                        </button>
                    </div>

                    <template v-if="selectedTab == 0">
                        <div class="card p-4 flex flex-col gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <SettingsIcon class="text-purple-500" />
                                    <p class="text-2xl">Informações Básicas</p>
                                </div>
                                <p>Configure os detalhes principais do seu jogo</p>
                            </div>
                            <AppInput
                                label="Nome do Jogo"
                                id="game.name"
                                name="game.name"
                                class="mt-1 block w-full"
                                v-model="gameForm.name"
                                :message="gameForm.errors.name"
                                required
                                autofocus
                            />
                            <AppTextarea
                                label="Descrição"
                                id="game.description"
                                name="game.description"
                                type="textarea"
                                class="mt-1 block w-full"
                                v-model="gameForm.description"
                                :message="gameForm.errors.description"
                                rows="5"
                            />
                        </div>

                        <div class="card p-4 flex flex-col gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <UsersIcon class="text-blue-500" />
                                    <p class="text-2xl">Configurações de Jogadores</p>
                                </div>
                                <p>Defina limites e configurações para os participantes</p>
                            </div>
                            <AppSelect
                                label="Maximo de Jogadores"
                                id="game.max_players"
                                name="game.max_players"
                                v-model="gameForm.max_players"
                                :message="gameForm.errors.max_players"
                                :options="max_players_options"
                                placeholder="Selecione um modo de jogo"
                                required
                            />
                            <div class="flex gap-2">
                                <Switch v-model="gameForm.is_free" />
                                <Label>Jogo Gratuito</Label>
                            </div>
                            <AppInput
                                v-if="!gameForm.is_free"
                                label="Valor da Cartela (R$)*"
                                id="game.entry_fee"
                                name="game.entry_fee"
                                class="mt-1 block w-full ps-8"
                                v-model="gameForm.entry_fee"
                                :message="gameForm.errors.entry_fee"
                                required
                                autofocus
                                type="number"
                                step="0.1"
                            >
                                <template v-slot:name>
                                    <DollarSignIcon class="text-gray-500 size-4" />
                                </template>
                            </AppInput>
                        </div>

                        <div class="card p-4 flex flex-col gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <ClockIcon class="text-green-500" />
                                    <p class="text-2xl">Data e Horário</p>
                                </div>
                                <p class="line-clamp-1">Configure quando o jogo será realizado</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <AppInput
                                    label="Data de inicio"
                                    id="game.start_date"
                                    name="game.start_date"
                                    v-model="gameForm.game_data.start_datetime"
                                    type="datetime-local"
                                    required
                                />
                                <div class="flex gap-2 col-span-2">
                                    <Switch v-model="gameForm.game_data.automatic" />
                                    <Label>Inicio automatico</Label>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-if="selectedTab == 1">
                        <div class="card p-4 flex flex-col gap-4">
                            <div class="">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <TrophyIcon class="text-yellow-500" />
                                        <p class="text-2xl">Configuração de Prêmios</p>
                                    </div>
                                    <button
                                        class="btn btn-primary flex items-center gap-2"
                                        @click="addAward()"
                                    >
                                        <PlusIcon />
                                        <span>Adicionar Prêmio</span>
                                    </button>
                                </div>
                                <p class="line-clamp-1">Configure múltiplos prêmios para diferentes tipos de
                                    vitória (linha, coluna, diagonal, quatro cantos, cartela cheia)</p>
                            </div>
                            <div
                                v-for="award, index in gameForm.awards"
                                class="border-2 border-dashed border-gray-200 bg-gray-50/50 p-4"
                            >
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-4">
                                        <p>Premio #{{ index + 1 }}</p>
                                        <span
                                            class="rounded-full py-0.5 px-2 text-sm h-min border flex items-center justify-between gap-2"
                                        >
                                            <span>Ordem: {{ index + 1 }}</span>
                                        </span>
                                    </div>
                                    <button
                                        class="btn btn-danger"
                                        type="button"
                                        @click="deleteAward(0)"
                                        v-if="gameForm.awards.length != 1"
                                    >
                                        <Trash2Icon class="size-4" />
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <AppSelect
                                        label="Tipo de Vitoria"
                                        :id="'game.awards.' + index + '.winning_type'"
                                        :name="'game.awards.' + index + '.winning_type'"
                                        v-model="gameForm.awards[index].winning_type"
                                        :options="awards_winning_types"
                                        placeholder="Selecione"
                                    />
                                    <AppSelect
                                        label="Tipo de Premio"
                                        :id="'game.awards.' + index + '.type'"
                                        :name="'game.awards.' + index + '.type'"
                                        v-model="gameForm.awards[index].type"
                                        :options="awards_types"
                                        placeholder="Selecione"
                                    />
                                    <!-- nome do premio -->
                                    <div class="col-span-2">
                                        <AppInput
                                            label="Nome do Prêmio"
                                            :id="'game.awards.' + index + '.name'"
                                            :name="'game.awards.' + index + '.name'"
                                            v-model="gameForm.awards[index].name"
                                            placeholder="Ex: R$ 100,00"
                                            required
                                        />
                                    </div>
                                    <!-- valor -->
                                    <AppInput
                                        label="Valor do Prêmio"
                                        :id="'game.awards.' + index + '.value'"
                                        :name="'game.awards.' + index + '.value'"
                                        v-model="gameForm.awards[index].value"
                                        type="number"
                                        step="0.01"
                                        placeholder="Ex: 100.00"
                                        required
                                    >
                                        <template #leading>
                                            <DollarSignIcon class="size-4 text-gray-500" />
                                        </template>
                                    </AppInput>
                                    <!-- ordem de premiacao -->
                                    <AppSelect
                                        label="Ordem de Premiação"
                                        :id="'game.awards.' + index + '.order'"
                                        :name="'game.awards.' + index + '.order'"
                                        v-model="gameForm.awards[index].order"
                                        :options="gameForm.awards.map((_, i) => ({ value: i, title: `Premiação #${i + 1}` }))"
                                        placeholder="Selecione a ordem"
                                    />
                                    <div class="col-span-2">
                                        <AppTextarea
                                            label="Descrição do Prêmio"
                                            :id="'game.awards.' + index + '.description'"
                                            :name="'game.awards.' + index + '.description'"
                                            v-model="gameForm.awards[index].description"
                                            placeholder="Descrição"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-yellow-900 mb-2">💡 Dicas para Múltiplos Prêmios</h4>
                                <ul class="text-sm text-yellow-800 space-y-1">
                                    <li>
                                        • <strong>Linha:</strong> Primeiro prêmio, mais fácil de conseguir
                                    </li>
                                    <li>
                                        • <strong>Quatro Cantos:</strong> Prêmio intermediário, boa estratégia
                                    </li>
                                    <li>
                                        • <strong>Diagonal:</strong> Prêmio especial, cria expectativa
                                    </li>
                                    <li>
                                        • <strong>Cartela Cheia:</strong> Prêmio principal, maior valor
                                    </li>
                                    <li>• Configure a ordem de premiação para controlar a sequência</li>
                                </ul>
                            </div>
                        </div>
                    </template>
                    <template v-if="selectedTab == 2">
                        <div class="card p-4 flex flex-col gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <TargetIcon class="text-blue-500" />
                                    <p class="text-2xl">Configurações de Sorteio</p>
                                </div>
                                <p class="line-clamp-1">Configure como os números serão sorteados</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <AppSelect
                                    label="Velocidade do Sorteio (segundos)"
                                    :id="'game.draw_speed'"
                                    :name="'game.draw_speed'"
                                    v-model="gameForm.game_data.draw_speed"
                                    :options="draw_speed_list"
                                    placeholder="Selecione"
                                />

                                <AppSelect
                                    label="Pausa Entre Prêmios (segundos)"
                                    :id="'game.pause_between_prizes'"
                                    :name="'game.pause_between_prizes'"
                                    v-model="gameForm.game_data.pause_between_prizes"
                                    :options="pause_between_prizes_list"
                                    placeholder="Selecione"
                                />

                                <AppSelect
                                    label="Tempo Limite do Jogo (minutos)"
                                    :id="'game.game_time_duration'"
                                    :name="'game.game_time_duration'"
                                    v-model="gameForm.game_data.game_time_duration"
                                    :options="game_time_duration_list"
                                    placeholder="Selecione"
                                />

                                <div class="col-span-2">
                                    <AppSelect
                                        label="Modo de Jogo"
                                        id="game.game_data.game_mode"
                                        name="game.game_data.game_mode"
                                        v-model="gameForm.game_data.game_mode"
                                        :options="game_modes?.data.map(type => ({ value: type.code, title: type.name })) ?? []"
                                        placeholder="Selecione um modo de jogo"
                                    >
                                        <slot name="after">
                                            <p class="text-sm text-gray-500">{{ selectedGameType?.description }}</p>
                                        </slot>
                                    </AppSelect>
                                </div>
                            </div>
                        </div>

                        <div class="card p-4 flex flex-col gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <UsersIcon class="text-purple-500" />
                                    <p class="text-2xl">Configurações de Participação</p>
                                </div>
                                <p class="line-clamp-1">Configure regras para os jogadores</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <AppSelect
                                    label="Cartelas por Jogador"
                                    :id="'game.max_cards_per_player'"
                                    :name="'game.max_cards_per_player'"
                                    v-model="gameForm.game_data.max_cards_per_player"
                                    :options="max_cards_per_player_list"
                                    placeholder="Selecione"
                                />

                                <AppSelect
                                    label="Mínimo para Iniciar"
                                    :id="'game.min_players_to_start'"
                                    :name="'game.min_players_to_start'"
                                    v-model="gameForm.game_data.min_players_to_start"
                                    :options="min_players_to_start_list"
                                    placeholder="Selecione"
                                />

                                <div class="flex gap-2">
                                    <Switch v-model="gameForm.game_data.allow_entry_after_start" />
                                    <Label>Permitir Entrada Após Início</Label>
                                </div>
                                <div v-if="!gameForm.game_data.allow_entry_after_start">
                                    <AppSelect
                                        label="Tempo Limite para Entrada (minutos)"
                                        :id="'game.max_entry_time_limit'"
                                        :name="'game.max_entry_time_limit'"
                                        v-model="gameForm.game_data.max_entry_time_limit"
                                        :options="max_entry_time_limit_list"
                                        placeholder="Selecione"
                                    />
                                </div>

                            </div>
                        </div>

                        <div class="card p-4 flex flex-col gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <SettingsIcon class="text-orange-500" />
                                    <p class="text-2xl">Configurações do Sistema</p>
                                </div>
                                <p class="line-clamp-1">Configure funcionalidades adicionais</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="flex gap-2">
                                        <Switch v-model="gameForm.game_data.auto_verify_bingo" />
                                        <Label>Verificação Automática de Bingo</Label>
                                    </div>
                                    <p class="text-xs text-gray-500 ml-6">O sistema verifica automaticamente os bingos
                                    </p>
                                </div>

                                <div>
                                    <div class="flex gap-2">
                                        <Switch v-model="gameForm.game_data.enable_chat" />
                                        <Label>Chat Durante o Jogo</Label>
                                    </div>
                                    <p class="text-xs text-gray-500 ml-6">Permite chat entre jogadores durante a partida
                                    </p>
                                </div>

                                <div>
                                    <div class="flex gap-2">
                                        <Switch v-model="gameForm.game_data.enable_sound" />
                                        <Label>Efeitos Sonoros</Label>
                                    </div>
                                    <p class="text-xs text-gray-500 ml-6">Reproduz sons durante o sorteio e
                                        premiações</p>
                                </div>

                                <div>
                                    <div class="flex gap-2">
                                        <Switch v-model="gameForm.game_data.enable_replay" />
                                        <Label>Gravação do Jogo</Label>
                                    </div>
                                    <p class="text-xs text-gray-500 ml-6">Grava o jogo para replay posterior</p>
                                </div>
                            </div>
                        </div>

                    </template>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <Link
                                :href="route('dashboard')"
                                class="btn btn-secondary"
                            >
                            <span class="w-max">Cancelar</span>
                            </Link>
                        </div>
                        <div class="flex items-center gap-4">
                            <button
                                class="btn btn-secondary"
                                @click="nextTab"
                                v-if="selectedTab != tabs.length - 1"
                            >
                                Próximo
                            </button>
                            <button
                                class="btn btn-primary"
                                type="submit"
                            >
                                <SaveIcon class="w-4 h-4 mr-2" />
                                <span class="w-max">Criar Jogo</span>
                            </button>
                        </div>
                    </div>
                </form>
                <!-- Preview do jogo -->
                <div>
                    <GamePreview :game-form="gameForm" />
                </div>
            </div>
        </div>

    </AppLayout>

    <Toaster richColors />
</template>

<script setup lang="ts">
import AppInput from "@/components/app/forms/AppInput.vue";
import AppSelect from "@/components/app/forms/AppSelect.vue";
import AppTextarea from "@/components/app/forms/AppTextarea.vue";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";
import AppLayout from "@/layouts/AppLayout.vue";
import { Award, Game, GameForm, GameMode, Resource } from "@/types/models";
import { Link } from "@inertiajs/vue3";
import { useForm } from 'laravel-precognition-vue-inertia';
import { ArrowLeftIcon, CalendarIcon, ClockIcon, DollarSignIcon, Gamepad2Icon, PlusIcon, SaveIcon, SettingsIcon, TargetIcon, Trash2Icon, TrophyIcon, UsersIcon } from "lucide-vue-next";
import { ref, watch } from "vue";

import { Toaster } from "@/components/ui/sonner";
import { toast } from "vue-sonner";
import 'vue-sonner/style.css';
import GamePreview from "@/components/modules/game/GamePreview.vue";

interface Props {
    game_modes?: Resource<GameMode[]>;
    game?: Resource<Game>;
}
type GameFormWithErrors = GameForm & {
    errors: Record<string, string>;
};

const props = defineProps<Props>();
const tabs = [
    {
        "icon": SettingsIcon,
        "text": "Básico",
        "key": "basic",
    },
    {
        "icon": TrophyIcon,
        "text": "Prêmios",
        "key": "awards",
    },
    {
        "icon": ClockIcon,
        "text": "Avançado",
        "key": "advanced",
    },
];
const selectedTab = ref(0);
const selectedGameType = ref<GameMode | undefined>()

const gameForm = useForm('post', route('games.store'),{
    id: undefined,
    name: "",
    description: undefined,
    max_players: "",
    is_free: true,
    entry_fee: 0,
    awards: [
        {
            "name": "",
            "description": "",
            "value": 0,
            "order": 0,
            "winning_type": "",
            "type": "",
        }
    ],
    game_data: {
        game_mode: "",
        start_datetime: undefined,
        automatic: false,
        draw_speed: 4,
        pause_between_prizes: 10,
        game_time_duration: 1 * 60 * 60, // 1 hora
        max_cards_per_player: 0,
        min_players_to_start: 0,
        allow_entry_after_start: true,
        max_entry_time_limit: 0,
        auto_verify_bingo: false,
        enable_chat: false,
        enable_sound: false,
        enable_replay: false,
    },
    errors: {},
});

function clickTab(tab: object, index: number) {
    selectedTab.value = index;
}
function nextTab() {
    selectedTab.value++;
}

function onSubmit() {
    if (gameForm.id) {
        // Update existing category
        gameForm.put(route('games.update', gameForm.id), {
            onSuccess: () => {
                gameForm.reset();
                toast.success('Jogo atualizada com sucesso!');
            },
            onError: (errors) => {
                console.error('Erro ao atualizar jogo:', errors);
                toast.error('Erro ao atualizar jogo. Verifique os dados e tente novamente.');
            }
        });
        return;
    }

    gameForm.post(route('games.store'), {
        forceFormData: true,
        onSuccess: () => {
            gameForm.reset();
            toast.success('Jogo adicionada com sucesso!');
        },
        onError: (errors) => {
            console.error('Erro ao adicionar jogo:', errors);
            toast.error('Erro ao adicionar jogo. Verifique os dados e tente novamente.');
        }
    });
}

watch(
    () => gameForm.game_data.game_mode,
    (value) => selectedGameType.value = props.game_modes?.data.find((type) => type.code == value),
);
watch(
    () => gameForm.is_free,
    (value) => {
        if (!value) gameForm.entry_fee = 0;
    },
);
watch(
    () => gameForm.game_data.allow_entry_after_start,
    (value) => {
        if (!value) gameForm.game_data.max_entry_time_limit = 0;
    },
);

// tab 2

const max_players_options = Array.from([10, 20, 50, 100, 200, 500, 1000]).map(e => ({ "value": e.toString(), "title": `${e} Jogadores` }));

// tab 3

const awards_winning_types = [
    { value: "line", title: "📏 Linha", description: "Uma linha horizontal completa" },
    { value: "column", title: "📐 Coluna", description: "Uma coluna vertical completa" },
    { value: "diagonal", title: "↗️ Diagonal", description: "Uma diagonal completa" },
    { value: "corners", title: "📍 Quatro Cantos", description: "Os quatro cantos da cartela" },
    { value: "full", title: "🎯 Cartela Cheia", description: "Toda a cartela preenchida" },
    { value: "custom", title: "⚙️ Personalizado", description: "Padrão personalizado" },
];
const awards_types = [
    { value: "money", title: "💰 Dinheiro" },
    { value: "item", title: "🎁 Item/Produto" },
    { value: "voucher", title: "🎫 Vale-compras" },
    { value: "points", title: "⭐ Pontos" },
];

function addAward() {
    gameForm.awards.push({
        "name": "",
        "description": "",
        "value": 0,
        "order": 0,
        "winning_type": "",
        "type": "",
    });
}

function deleteAward(index: number) {
    gameForm.awards = gameForm.awards.filter((e, i) => i != index);
}

// tab 4

const draw_speed_list = [
    { value: 1, title: "1 segundo (Muito Rápido)"},
    { value: 2, title: "2 segundos (Rápido)"},
    { value: 3, title: "3 segundos (Normal)"},
    { value: 4, title: "4 segundos (Padrão)"},
    { value: 5, title: "5 segundos (Lento)"},
    { value: 6, title: "6 segundos (Muito Lento)"},
];

const pause_between_prizes_list = [
    {value: 5, title: "5 segundos"},
    {value: 10, title: "10 segundos"},
    {value: 15, title: "15 segundos"},
    {value: 20, title: "20 segundos"},
    {value: 30, title: "30 segundos"},
];

const game_time_duration_list = [
    { value: 1800, title: "30 minutos"},
    { value: 2700, title: "45 minutos"},
    { value: 3600, title: "1 hora"},
    { value: 5400, title: "1h 30min"},
    { value: 7200, title: "2 horas"},
    { value: 0, title: "Sem limite"},
];

const max_cards_per_player_list = [
    { value: 1, title: "1 cartela"},
    { value: 2, title: "2 cartelas"},
    { value: 3, title: "3 cartelas"},
    { value: 4, title: "4 cartelas"},
    { value: 5, title: "5 cartelas"},
    { value: 6, title: "6 cartelas"},
];

const min_players_to_start_list = [
    { value: 1, title: "1 jogador"},
    { value: 2, title: "2 jogadores"},
    { value: 3, title: "3 jogadores"},
    { value: 5, title: "5 jogadores"},
    { value: 10, title: "10 jogadores"},
]

const max_entry_time_limit_list = [
    {value: 2, title: "2 minutos"},
    {value: 5, title: "5 minutos"},
    {value: 10, title: "10 minutos"},
    {value: 15, title: "15 minutos"},
];

</script>

<style lang="scss" scoped></style>
