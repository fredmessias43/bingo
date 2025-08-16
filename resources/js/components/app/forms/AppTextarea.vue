<template>
    <div class="flex flex-col gap-2">
        <Label for="game.name">{{ label }}{{ required ? '*' : '' }}</Label>
        <Textarea
            :id="id"
            :name="name"
            class="mt-1 block w-full"
            v-model="modelValue"
            :required="required"
            autofocus
            :rows="rows"
            :cols="cols"
            :maxlength="maxlength"
            :minlength="minlength"
            :wrap="wrap"
            :spellcheck="spellcheck"
            :inputmode="inputmode"
            :tabindex="tabindex"
            :dir="dir"
            :disabled="disabled"
            :placeholder="placeholder"
            :readonly="readonly"
            :autocomplete="autocomplete"
        />

        <InputError :message="message" />
    </div>
</template>

<script setup lang="ts">
import InputError from "@/components/InputError.vue";
import Textarea from "@/components/ui/textarea/Textarea.vue";
import Label from "@/components/ui/label/Label.vue";
import { useVModel } from "@vueuse/core";

interface Props {
    label: string;
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
    rows?: number | string;
    cols?: number | string;
    maxlength?: number;
    minlength?: number;
    wrap?: string;
    spellcheck?: boolean;
    inputmode?: string;
    tabindex?: number;
    dir?: string;
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
