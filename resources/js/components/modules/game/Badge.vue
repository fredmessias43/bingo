<template>
	<span class="rounded-full py-0.5 px-2 text-sm h-min" :class="getStatusBadgeClass(status)">
        {{ getStatusText(status) }}
    </span>
</template>

<script setup lang="ts">
import { getStatusBadgeClass, getStatusText } from '@/lib/utils';

defineProps<{
    status: string
}>()
</script>
