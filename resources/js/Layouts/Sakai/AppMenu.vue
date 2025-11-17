<script setup>
import {inject, ref} from 'vue';

import AppMenuItem from './AppMenuItem.vue';
const can = inject('userCan');
const model = ref([
    {
        label: 'Menu',
        items: [
            { label: 'Objednávky', gicon: 'order_approve', to: '/sales/orders', visible: can('sales.orders.view') },
        ]
    },
    {
        label: 'Katalog',
        icon: 'pi pi-fw pi-briefcase',
        to: '/pages',
        visible: can('catalog.products.view') || can('catalog.categories.view') || can('catalog.suppliers.view'),
        items: [
            {label: 'Zboží', gicon: 'sell', to: '/catalog/products', visible: can('catalog.products.view')},
            {label: 'Kategorie', gicon: 'flowchart', to: '/catalog/categories', visible: can('catalog.categories.view')},
            {label: 'Dodavatelé', gicon: 'store', to: '/catalog/suppliers', visible: can('catalog.suppliers.view')},

        ]
    },
    {
        label: 'Administrace',
        icon: 'pi pi-fw pi-briefcase',
        to: '/pages',
        visible: can('admin.branches.view') || can('admin.users.view'),
        items: [
            {label: 'Pobočky', gicon: 'globe', to: '/admin/branches', visible: can('admin.branches.view')},
            {label: 'Uživatelé', gicon: 'person', to: '/admin/users', visible: can('admin.users.view')},
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
