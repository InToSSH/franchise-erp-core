<template>
    <AppLayout>
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-semibold">Uživatelé</h1>
                <Button label="Přidat uživatele" icon="pi pi-plus" @click="openCreate" />
            </div>


            <DataGrid
                :resource="users"
                route-prefix="admin.users"
                @editAction="openEdit"
            >
                <Column field="name" header="Název" sortable />
                <Column field="email" header="Email" sortable />
                <Column field="phone" header="Telefon" sortable />
                <Column field="branches" header="Pobočky">
                    <template #body="slotProps">
                        <div class="flex flex-wrap gap-1">
                            <Badge
                                v-for="branch in slotProps.data.branches"
                                :key="branch.value"
                                :value="branch.label"
                                severity="warning"
                            />
                        </div>
                    </template>
                </Column>
            </DataGrid>
            <UserForm
                v-model:visible="showForm"
                :model="editingModel"
                :branches="branches"
            />
        </div>
        <ConfirmDialog></ConfirmDialog>

    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/Sakai/AppLayout.vue';
import {ref} from 'vue'
import DataGrid from "@/Components/DataGrid.vue";
import UserForm from "@/Components/User/UserForm.vue";

const props = defineProps({
    users: Object,
    branches: Array,
})

const showForm = ref(false)
const editingModel = ref(null)

function openCreate() {
    editingModel.value = null
    showForm.value = true
}

function openEdit(branch) {
    editingModel.value = branch
    showForm.value = true
}
</script>
