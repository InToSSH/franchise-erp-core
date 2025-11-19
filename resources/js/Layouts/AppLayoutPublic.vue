<template>
    <div class="layout-wrapper flex h-screen">
        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Page content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import PanelMenu from 'primevue/panelmenu';
import Button from 'primevue/button';
import ScrollPanel from 'primevue/scrollpanel';

const page = usePage();

</script>

<style scoped>
.layout-wrapper {
    overflow: hidden;
}

.layout-sidebar {
    z-index: 30;
}

@media (max-width: 768px) {
    .layout-sidebar {
        position: absolute;
        height: 100%;
        transform: translateX(-100%);
    }
}
</style>
