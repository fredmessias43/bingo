<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppHeader from '@/components/AppHeader.vue';
import AppShell from '@/components/AppShell.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {}

withDefaults(defineProps<Props>(), {});
</script>

<template>
    <AppShell class="flex-col">
        <AppHeader />
        <AppContent>
            <slot />
        </AppContent>
    </AppShell>
</template>
