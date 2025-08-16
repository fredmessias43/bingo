<template>
    <div class="flex flex-col gap-2">
        <Label for="game.max_players">{{ label }} {{ required ? '*' : '' }}</Label>
        <Select :id="id" :name="name" v-model="modelValue" :class="class" :disabled="disabled" :placeholder="placeholder">
            <SelectTrigger class="w-full">
                <SelectValue placeholder="Selecione um maximo de Jogadores" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <!-- <SelectLabel>Fruits</SelectLabel> -->
                    <SelectItem v-for="option in options" :value="option['value']">
                        {{ option['title'] ?? option['label'] }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>

        <slot name="after" ></slot>

        <InputError :message="message" />
    </div>
</template>

<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectGroup, SelectItem } from '@/components/ui/select';
import { useVModel } from '@vueuse/core';

interface SelectOption {
    value: string | number;
    /**
     * @deprecated use label
     */
    title?: string;
    label?: string;
}

interface Props {
    label: string;
    options: Array<SelectOption>;
    message?: string;
    modelValue?: string | number;
    class?: string;
    defaultValue?: string | number;
    disabled?: boolean;
    placeholder?: string;
    name?: string;
    id?: string;
    required?: boolean;
    readonly?: boolean;
    autocomplete?: string;
    autofocus?: boolean;
}

const props = defineProps<Props>();
const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
})

</script>

<style lang="scss" scoped>

</style>
