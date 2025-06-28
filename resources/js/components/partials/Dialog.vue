<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@ui/dialog'
import { cn } from '@/lib/utils'

const props = defineProps<{
    title?: string
    description?: string
    class?: string
}>()
    
</script>

<template>
  <Dialog>
        <DialogTrigger as-child>
            <slot name="trigger" />
        </DialogTrigger>
        <DialogContent :class="cn('sm:max-w-[425px]', props.class)">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>
                    {{ description }}
                </DialogDescription>
            </DialogHeader>
            <slot />
            <DialogFooter v-if="$slots.footer">
                <slot name="footer" />
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>