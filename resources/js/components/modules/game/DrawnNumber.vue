<template>
    <div
        class="mx-auto rounded-full flex items-center justify-center font-boldtext-white shadow-2xl"
        :class="[getClassForNumber(number), getSizeClass(size ?? 'small')]"
    >
        {{ number }}
    </div>
</template>

<script setup lang="ts">

interface Props {
    number: number;
    size?: 'small' | 'medium' | 'large';
    class?: string;
}

const props = defineProps<Props>();


function getClassForNumber(number: number): string {
    if (number <= 15) return 'bg-red-400 text-white';
    if (number <= 30) return 'bg-yellow-400 text-white';
    if (number <= 45) return 'bg-green-400 text-white';
    if (number <= 60) return 'bg-blue-400 text-white';
    return 'bg-purple-400';
}

function getSizeClass(size: string): string {
    switch (size) {
        case 'small':
            return 'w-12 h-12 text-2xl';
        case 'medium':
            return 'w-16 h-16 text-3xl';
        case 'large':
            return 'w-24 h-24 text-4xl';
        default:
            return 'w-16 h-16 text-3xl';
    }
}

</script>

<style lang="scss" scoped>

</style>
