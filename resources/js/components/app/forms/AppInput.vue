<template>
    <div class="flex flex-col gap-2">
        <Label for="game.name">{{ label }} {{ required ? '*' : '' }}</Label>

        <div class="relative">
            <div class="absolute top-[0.9rem] left-2 size-4">
                <slot name="leading" />
            </div>

            <Input
                :id="id"
                :name="name"
                class="mt-1 block w-full"
                :class="{
                    'ps-8': slot.leading,
                    'pe-8': slot.trailing,
                }"
                :type="type"
                v-model="modelValue"
                required
                autofocus
                :disabled="disabled"
                :placeholder="placeholder"
                :readonly="readonly"
            />
            <div class="absolute top-[0.9rem] right-2 size-4">
                <slot name="trailing" />
            </div>
        </div>

        <InputError :message="message" />
    </div>
</template>

<script setup lang="ts">
import InputError from "@/components/InputError.vue";
import Input from "@/components/ui/input/Input.vue";
import Label from "@/components/ui/label/Label.vue";
import { useVModel } from "@vueuse/core";
import { InputTypeHTMLAttribute } from "vue";

interface Props {
    label: string;
    message?: string;
    type?: InputTypeHTMLAttribute;
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
const slot = defineSlots<{
    leading?: any;
    trailing?: any;
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
})
</script>

<style lang="scss" scoped>

</style>
