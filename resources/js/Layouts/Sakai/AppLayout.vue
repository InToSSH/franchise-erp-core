<script setup>
import { useLayout } from "@/Layouts/Sakai/composables/layout.js";
import { computed, ref, watch } from 'vue';
import Toast from 'primevue/toast';
import AppFooter from './AppFooter.vue';
import AppSidebar from './AppSidebar.vue';
import AppTopbar from './AppTopbar.vue';
import { useToast } from 'primevue/usetoast';
import {Head, usePage} from "@inertiajs/vue3";

const toast = useToast();

const page = usePage();

const { layoutConfig, layoutState, isSidebarActive, toggleDarkMode } = useLayout();

const props = defineProps({
    title: {
        type: String,
        default: null,
    },
});


const outsideClickListener = ref(null);

if (page.props.auth.user.dark_mode && layoutConfig.darkTheme === false) {
    toggleDarkMode()
}

watch(() => page.props.flash, flash => {
    console.log(flash);
    if (flash.success === null && flash.error === null && flash.message === null) {
        return;
    }

    if (flash.success) {
        toast.add({severity: 'success', summary: 'Úspěch', detail: flash.success, life: 3000});
    }

    if (flash.error) {
        toast.add({severity: 'error', summary: 'Chyba', detail: flash.error, life: 3000});
    }

    if (flash.message) {
        toast.add({severity: 'info', summary: 'Info', detail: flash.message, life: 3000});
    }

    page.props.flash = {
        success: null,
        error: null,
        message: null
    }

}, {deep: true})

watch(isSidebarActive, (newVal) => {
    if (newVal) {
        bindOutsideClickListener();
    } else {
        unbindOutsideClickListener();
    }
});

const containerClass = computed(() => {
    return {
        'layout-overlay': layoutConfig.menuMode === 'overlay',
        'layout-static': layoutConfig.menuMode === 'static',
        'layout-static-inactive': layoutState.staticMenuDesktopInactive && layoutConfig.menuMode === 'static',
        'layout-overlay-active': layoutState.overlayMenuActive,
        'layout-mobile-active': layoutState.staticMenuMobileActive
    };
});

function bindOutsideClickListener() {
    if (!outsideClickListener.value) {
        outsideClickListener.value = (event) => {
            if (isOutsideClicked(event)) {
                layoutState.overlayMenuActive = false;
                layoutState.staticMenuMobileActive = false;
                layoutState.menuHoverActive = false;
            }
        };
        document.addEventListener('click', outsideClickListener.value);
    }
}

function unbindOutsideClickListener() {
    if (outsideClickListener.value) {
        document.removeEventListener('click', outsideClickListener);
        outsideClickListener.value = null;
    }
}

function isOutsideClicked(event) {
    const sidebarEl = document.querySelector('.layout-sidebar');
    const topbarEl = document.querySelector('.layout-menu-button');

    return !(sidebarEl.isSameNode(event.target) || sidebarEl.contains(event.target) || topbarEl.isSameNode(event.target) || topbarEl.contains(event.target));
}
</script>

<template>
    <Head v-if="title" :title="title"/>
    <div class="layout-wrapper" :class="containerClass">
        <app-topbar></app-topbar>
        <app-sidebar></app-sidebar>
        <div class="layout-main-container">
            <div class="layout-main">
                <slot />
            </div>
            <app-footer></app-footer>
        </div>
        <div class="layout-mask animate-fadein"></div>
    </div>
    <Toast />
</template>
