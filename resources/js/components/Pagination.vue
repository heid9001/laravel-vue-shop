<template>
    <nav class="mt-4" aria-label="Pagination">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="text-muted mb-0">
                Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} entries
            </p>
        </div>

        <ul class="pagination justify-content-center">
            <li
                v-for="link in meta.links"
                :key="link.label"
                class="page-item"
                :class="{ 'active': link.active, 'disabled': !link.url }"
            >
                <a
                    class="page-link"
                    href="#"
                    @click.prevent="handlePageChange(link)"
                    :class="{ 'disabled': !link.url }"
                    v-html="link.label"
                ></a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: 'Pagination',
    props: {
        meta: {
            type: Object,
            required: true,
            default: () => ({
                from: 1,
                to: 1,
                total: 0,
                links: []
            })
        }
    },
    emits: ['page-changed'],
    methods: {
        handlePageChange(link) {
            if (link.url) {
              let pageNum = link.url.match(/page=(\d+)/)?.[1] || 1;
              this.$emit('page-changed', pageNum);
            }
        }
    }
}
</script>

<style scoped>
.page-link {
    cursor: pointer;
}

.page-link.disabled {
    pointer-events: none;
    opacity: 0.6;
}
</style>
