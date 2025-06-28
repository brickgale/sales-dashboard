<script setup lang="ts">
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@ui/pagination';
import { withDefaults } from 'vue';

withDefaults(
    defineProps<{
        itemsPerPage?: number;
        total?: number;
        currentPage?: number;
    }>(),
    {
        itemsPerPage: 10,
        total: 30,
        currentPage: 1,
    }
);
</script>

<template>
    <Pagination v-slot="{ page }" :items-per-page="itemsPerPage" :total="total" :default-page="1" :page="currentPage">
        <PaginationContent v-slot="{ items }">
            <PaginationPrevious />

            <template v-for="(item, index) in items" :key="index">
                <PaginationItem
                v-if="item.type === 'page'"
                :value="item.value"
                :is-active="item.value === page"
                >
                    {{ item.value }}
                </PaginationItem>
            </template>

            <PaginationEllipsis :index="4" />

            <PaginationNext />
        </PaginationContent>
    </Pagination>
</template>