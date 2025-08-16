<template>
    <div class="card shadow-lg border-0 bg-white/80 backdrop-blur-sm">
        <div>
            <h1 class="flex items-center">
                <EyeIcon class="w-5 h-5 mr-2 text-purple-600" />
                Preview do Jogo
            </h1>
            <CardDescription>Veja como seu jogo aparecerá para os jogadores</CardDescription>
        </div>
        <div class="space-y-4">
            <!--  Preview Card -->
            <div class="border rounded-lg p-4 bg-gradient-to-br from-purple-50 to-blue-50">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-lg">{{ gameForm.name || "Nome do Jogo" }}</h3>
                        <p class="text-sm text-gray-600">{{ gameForm.description || "Descrição do jogo" }}
                        </p>
                    </div>
                    <Badge color="bg-yellow-500 text-white">Aguardando</Badge>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center text-gray-600">
                            <UsersIcon class="w-4 h-4 mr-1" />
                            0/{{ gameForm.max_players }} jogadores
                        </div>
                        <div class="flex items-center text-gray-600">
                            <ClockIcon class="w-4 h-4 mr-1" />
                            {{ formatDateTime(gameForm.start_datetime) }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <span class="text-gray-600">Taxa: </span>
                            <span class="font-semibold">
                                {{ gameForm.is_free ? "Gratuito" : `R$ ${gameForm.entry_fee.toFixed(2)}` }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <TrophyIcon class="w-4 h-4 mr-1 text-yellow-500" />
                            <span class="font-semibold text-yellow-600">{{ gameForm.awards.length }}
                                prêmios</span>
                        </div>
                    </div>

                    <button class="btn btn-success w-full mt-3">Participar do Jogo</button>
                </div>
            </div>

            <!--  Lista de Prêmios -->
            <div
                class="space-y-3"
                v-if="gameForm.awards.length"
            >
                <h4 class="font-semibold text-gray-900 flex items-center">
                    <Target class="w-4 h-4 mr-2" />
                    Prêmios Configurados
                </h4>
                <div class="space-y-2 max-h-40 overflow-y-auto">
                    <div
                        class="bg-white p-2 rounded border text-xs"
                        v-for="award, index in gameForm.awards.sort((a, b) => a.order - b.order)"
                        :key="award.name + award.order"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span>{{ getAwardTypeIcon(award.type) }}</span>
                                <span class="font-semibold">{{ award.name || `Prêmio ${index + 1}` }}</span>
                            </div>
                            <Badge
                                variant="outline"
                            >
                                {{ award.order }}º
                            </Badge>
                        </div>
                        <p class="text-gray-600 mt-1">{{ award.description || "Descrição do prêmio" }}</p>
                    </div>

                </div>
            </div>


            <!--  Estatísticas -->
            <div class="space-y-3">
                <h4 class="font-semibold text-gray-900">📊 Estatísticas</h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-blue-50 p-3 rounded">
                        <div class="text-blue-700">Modo</div>
                        <div class="font-bold text-blue-900 capitalize">{{ gameForm.game_mode }}</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded">
                        <div class="text-green-700">Velocidade</div>
                        <div class="font-bold text-green-900">{{ gameForm.draw_speed }}s</div>
                    </div>
                    <div class="bg-purple-50 p-3 rounded">
                        <div class="text-purple-700">Cartelas</div>
                        <div class="font-bold text-purple-900">Até {{ gameForm.max_cards_per_player }}</div>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded">
                        <div class="text-yellow-700">Prêmios</div>
                        <div class="font-bold text-yellow-900">R$ {{ getTotalAwardValue().toFixed(0) }}
                        </div>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded">
                        <div class="text-indigo-700">Mín. Jogadores</div>
                        <div class="font-bold text-indigo-900">{{ gameForm.min_players_to_start }}</div>
                    </div>
                    <div class="bg-red-50 p-3 rounded">
                        <div class="text-red-700">Tempo Limite</div>
                        <div class="font-bold text-red-900 text-xs">
                            {{ gameForm.game_time_duration === 0 ? "Sem limite" : `${gameForm.game_time_duration}min` }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script
    setup
    lang="ts"
>
import Badge from "@/components/ui/badge/Badge.vue";
import { formatDateTime } from "@/lib/utils";
import { GameForm } from "@/types/models";
import { ClockIcon, EyeIcon, TrophyIcon, UsersIcon } from "lucide-vue-next";

interface Props {
    gameForm: GameForm;
}

const props = defineProps<Props>();
const emit = defineEmits([]);

function getTotalAwardValue() {
    return props.gameForm.awards
        .filter((award) => award.type === "money")
        .reduce((total, award) => total + (award.value || 0), 0)
}

function getAwardTypeIcon(type: string) {
    return type;
    // const awardType = awardTypes.find((pt) => pt.value === type)
    // return awardType ? awardType.label.split(" ")[0] : "🏆"
}

</script>

<style
    lang="scss"
    scoped
></style>
