<script setup>
import { ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Home',
        items: [{ label: 'Dashboard', gicon: 'home', to: '/dashboard' }]
    },
    {
        label: 'Menu',
        items: [
            { label: 'Objednávky', gicon: 'order_approve', to: '/orders' },
        ]
    },
    {
        label: 'Katalog',
        icon: 'pi pi-fw pi-briefcase',
        to: '/pages',
        items: [
            {label: 'Položky', gicon: 'sell', to: '/catalog/items'},
            {label: 'Kategorie', gicon: 'flowchart', to: '/catalog/categories'},
            {label: 'Dodavatelé', gicon: 'store', to: '/catalog/suppliers'},

        ]
    },
    {
        label: 'Administrace',
        icon: 'pi pi-fw pi-briefcase',
        to: '/pages',
        items: [
            {label: 'Pobočky', gicon: 'globe', to: '/admin/branches'},
            {label: 'Uživatelé', gicon: 'person', to: '/admin/users'},
        ]
    },
]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>
