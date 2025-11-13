<template>
    <AppLayout>
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-semibold">Uživatelé</h1>
                <Button label="Přidat uživatele" icon="pi pi-plus" @click="openCreate" v-if="$userCan('admin.users.edit')"/>
            </div>


            <DataGrid
                :resource="users"
                route-prefix="admin.users"
                @editAction="openEdit"
                delete-acl="admin.users.edit"
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
                <Column field="roles" header="Role">
                    <template #body="slotProps">
                        <div class="flex flex-wrap gap-1">
                            <Badge
                                v-for="role in slotProps.data.roles"
                                :key="role.value"
                                :value="role.label"
                                severity="info"
                            />
                        </div>
                    </template>
                </Column>
            </DataGrid>
            <UserForm
                v-model:visible="showForm"
                :model="editingModel"
                :branches="branches"
                :roles="roles"
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
    roles: Array
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
