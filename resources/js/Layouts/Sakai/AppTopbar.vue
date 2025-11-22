<script setup>
import { useLayout } from "@/Layouts/Sakai/composables/layout.js";
import {Link} from "@inertiajs/vue3";

const { toggleMenu, toggleDarkMode, isDarkTheme } = useLayout();
</script>

<template>
    <div class="layout-topbar">
        <div class="layout-topbar-logo-container">
            <button class="layout-menu-button layout-topbar-action" @click="toggleMenu">
                <i class="pi pi-bars"></i>
            </button>
            <Link href="/" class="layout-topbar-logo">
                <img src="/img/logo.png" class="max-h-10">

                <span class="hidden md:inline">Intranet</span>
            </Link>
        </div>

        <div class="layout-topbar-actions">
            <div class="layout-config-menu">
                <button type="button" class="layout-topbar-action" @click="toggleDarkMode">
                    <i :class="['pi', { 'pi-moon': isDarkTheme, 'pi-sun': !isDarkTheme }]"></i>
                </button>
            </div>
            <Divider layout="vertical"></Divider>

            <button
                class="layout-topbar-menu-button layout-topbar-action"
                v-styleclass="{ selector: '@next', toggleClass: 'hidden' }"
            >
                <i class="pi pi-ellipsis-v"></i>
            </button>

            <div class="layout-topbar-menu hidden lg:block">
                <div class="layout-topbar-menu-content">
<!--                    <button type="button" class="layout-topbar-action">-->
<!--                        <i class="pi pi-calendar"></i>-->
<!--                        <span>Calendar</span>-->
<!--                    </button>-->
<!--                    <button type="button" class="layout-topbar-action">-->
<!--                        <i class="pi pi-inbox"></i>-->
<!--                        <span>Messages</span>-->
<!--                    </button>-->

                    <div class="ml-3  flex items-center lg:self-center font-semibold h-full">
                        <div>
                            {{ $page.props.auth.user.name }}
                        </div>
                    </div>
                    <button type="button" class="layout-topbar-action" @click="$inertia.visit(route('profile.show'))">
                        <i class="pi pi-user"></i>
                        <span>Nastavení účtu</span>
                    </button>
                    <button type="button" class="layout-topbar-action" @click="$inertia.post(route('logout'))">
                        <i class="pi pi-sign-out"></i>
                        <span>Odhlásit</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
