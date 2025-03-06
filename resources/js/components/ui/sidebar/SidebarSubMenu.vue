<script setup lang="ts">
import { cn } from '@/lib/utils';

defineProps<{ className?: string; }>();
</script>

<template>
    <div :class="cn('ml-4 pl-4 border-l space-y-1', className)">
        <slot />
    </div>
</template>

<style scoped>

</style>