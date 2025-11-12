<template>
    <AppLayout>
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-semibold">Pobočky</h1>
                <Button label="Přidat pobočku" icon="pi pi-plus" @click="openCreate" />
            </div>


            <DataGrid
                :resource="branches"
                route-prefix="admin.branches"
                @editAction="openEdit"
            >
                <Column field="name" header="Název" sortable />
                <Column field="email" header="Email" sortable />
                <Column field="phone" header="Telefon" sortable />
                <Column field="full_address" header="Adresa" />
                <Column field="users" header="Uživatelé">
                    <template #body="slotProps">
                        <div class="flex flex-wrap gap-1">
                            <Badge
                                v-for="user in slotProps.data.users"
                                :key="user.value"
                                :value="user.label"
                                severity="warning"
                            />
                        </div>
                    </template>
                </Column>
            </DataGrid>
            <BranchForm
                v-model:visible="showForm"
                :model="editingModel"
            />
        </div>
        <ConfirmDialog></ConfirmDialog>

    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/Sakai/AppLayout.vue';
import {ref} from 'vue'
import DataGrid from "@/Components/DataGrid.vue";
import BranchForm from "@/Components/Branch/BranchForm.vue";

const props = defineProps({
    branches: Object,
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
