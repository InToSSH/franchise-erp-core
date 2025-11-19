<template>
    <div class="layout-wrapper flex h-screen">
        <!-- Sidebar -->
        <aside
            class="layout-sidebar bg-surface-0 border-r border-surface-200 flex flex-col w-64 transition-transform"
            :class="{ '-translate-x-full': !sidebarVisible, 'translate-x-0': sidebarVisible }"
        >
            <div class="p-4 flex items-center justify-between border-b border-surface-200">
                <h2 class="text-xl font-semibold">MyApp</h2>
                <Button icon="pi pi-times" class="md:hidden" text rounded @click="sidebarVisible = false" />
            </div>

            <ScrollPanel class="flex-1 p-2">
                <PanelMenu :model="menuItems" />
            </ScrollPanel>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header
                class="layout-topbar bg-surface-0 border-b border-surface-200 flex items-center justify-between px-4 py-3"
            >
                <div class="flex items-center gap-2">
                    <Button
                        icon="pi pi-bars"
                        class="md:hidden"
                        text
                        rounded
                        @click="sidebarVisible = !sidebarVisible"
                    />
                    <h1 class="text-lg font-medium">{{ page.props.title || 'Dashboard' }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    <span>{{ page.props.auth?.user?.name }}</span>
                    <Button icon="pi pi-sign-out" text rounded @click="logout" />
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto bg-surface-50 p-6">
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
const sidebarVisible = ref(true);

const logout = () => router.post(route('logout'));

const menuItems = ref([
    {
        label: 'Dashboard',
        icon: 'pi pi-home',
        command: () => router.visit(route('dashboard')),
    },
    {
        label: 'Users',
        icon: 'pi pi-users',
        command: () => router.visit(route('users.index')),
    },
    {
        label: 'Settings',
        icon: 'pi pi-cog',
        command: () => router.visit(route('settings.index')),
    },
]);
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
