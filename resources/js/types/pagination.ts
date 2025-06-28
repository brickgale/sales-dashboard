export const defaultPaginationState = {
    currentPage: 1,
    perPage: 10,
    total: 0,
} as const;

export type PaginationState = typeof defaultPaginationState;
