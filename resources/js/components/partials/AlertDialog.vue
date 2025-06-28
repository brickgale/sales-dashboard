<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@ui/alert-dialog';
import { withDefaults } from 'vue';

withDefaults(
    defineProps<{
        title?: string
        description?: string
    }>(),
    {
        title: 'Are you absolutely sure?',
        description: 'This action cannot be undone. This will permanently delete your account and remove your data from our servers.',
    }
);

defineEmits(['continue'])
</script>

<template>
    <AlertDialog>
        <AlertDialogTrigger as-child>
            <slot name="trigger" />
        </AlertDialogTrigger>
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ title }}</AlertDialogTitle>
                <AlertDialogDescription>
                {{ description }}
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <AlertDialogAction variant="destructive" @click.prevent="$emit('continue')">Continue</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>