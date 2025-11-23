<template>
    <AppLayout title="Dodavatelé">
        <div class="p-6 space-y-4">
            <PageHeader
                title="Dodavatelé"
                new-btn-title="Přidat dodavatele"
                new-btn-acl="catalog.suppliers.edit"
                @click:new="openCreate"
            />

            <DataGrid
                :resource="suppliers"
                route-prefix="catalog.suppliers"
                @editAction="openEdit"
                delete-acl="catalog.suppliers.edit"
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
                <Column field="code" header="Kód" sortable />
                <Column field="contact_person" header="Kontaktní osoba" />
                <Column field="email" header="Email" sortable />
                <Column field="phone" header="Telefon" sortable />
                <Column field="full_address" header="Adresa" />
                <Column field="cin" header="IČO" sortable />
            </DataGrid>
            <SupplierForm
                v-model:visible="showForm"
                :model="editingModel"
            />
        </div>
        <ConfirmDialog></ConfirmDialog>

    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/Sakai/AppLayout.vue';
import SupplierForm from "@/Components/Supplier/SupplierForm.vue";
import {ref} from 'vue'
import DataGrid from "@/Components/DataGrid.vue";
import PageHeader from "@/Components/PageHeader.vue";

const props = defineProps({
    suppliers: Object,
})

const showForm = ref(false)
const editingModel = ref(null)

function openCreate() {
    editingModel.value = null
    showForm.value = true
}

function openEdit(supplier) {
    editingModel.value = supplier
    showForm.value = true
}
</script>
