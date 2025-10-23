<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { Link } from '@inertiajs/vue3';;

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <div class="flex-shrink-0">
        <div class="flex h-20 w-20 items-center justify-center">
            <Link :href="'/'">
            <img src="/images/logo.svg" alt="Logo" />
            </Link>
        </div>
    </div>
</template>
