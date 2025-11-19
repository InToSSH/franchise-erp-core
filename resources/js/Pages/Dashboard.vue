<template>
    <AppLayout>
        <div class="p-4">
            <div class="flex flex-column md:flex-row md:align-items-center md:justify-content-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Dashboard</h1>
                    <p class="text-color-secondary">
                        Vítejte zpět! Použijte nabídku vlevo pro navigaci do sekce, se kterou chcete pracovat.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/Sakai/AppLayout.vue';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Tag from 'primevue/tag';
</script>
