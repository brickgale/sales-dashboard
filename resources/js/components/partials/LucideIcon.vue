<script setup lang="ts">
import { computed } from 'vue';
import * as icons from 'lucide-vue-next';

const props = defineProps<{ name: keyof typeof icons }>()

const IconComponent = computed(() => icons[props.name])
</script>

<template>
    <component :is="IconComponent" class="w-6 h-6" :name="props.name" />
</template>