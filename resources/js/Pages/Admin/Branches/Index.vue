<template>
    <AppLayout title="Pobočky">
        <div class="p-6 space-y-4">
            <PageHeader
                title="Pobočky"
                new-btn-title="Vytvořit pobočku"
                new-btn-acl="admin.branches.edit"
                @click:new="openCreate"
            />


            <DataGrid
                :resource="branches"
                route-prefix="admin.branches"
                @editAction="openEdit"
                delete-acl="admin.branches.edit"
            >
                <Column field="name" header="Název" sortable>
                    <template #body="{ data }">
                        <a
                            href="#"
                            class="text-blue-600 hover:underline"
                            @click.prevent="openEdit(data)"
                        >
                            {{ data.name }}
                        </a>
                    </template>
                </Column>
                <Column field="email" header="Email" sortable />
                <Column field="phone" header="Telefon" sortable />
                <Column field="manager.name" header="Odpovědná osoba" sortable />
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
                :users="users"
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
import PageHeader from "@/Components/PageHeader.vue";

const props = defineProps({
    branches: Object,
    users: Array,
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
