<template>
    <AppLayout>
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-semibold">Dodavatelé</h1>
                <Button label="Přidat dodavatele" icon="pi pi-plus" @click="openCreate" />
            </div>


            <DataGrid
                :resource="suppliers"
                route-prefix="catalog.suppliers"
                @editAction="openEdit"
            >
                <Column field="name" header="Název" sortable />
                <Column field="code" header="Kód" sortable />
                <Column field="contact_person" header="Kontaktní osoba" />
                <Column field="email" header="Email" sortable />
                <Column field="phone" header="Telefon" sortable />
                <Column field="full_address" header="Adresa" />
                <Column field="cin" header="IČO" sortable />
            </DataGrid>
            <SupplierForm
                v-model:visible="showForm"
                :model="editingSupplier"
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

const props = defineProps({
    suppliers: Object,
})

const showForm = ref(false)
const editingSupplier = ref(null)

function openCreate() {
    editingSupplier.value = null
    showForm.value = true
}

function openEdit(supplier) {
    editingSupplier.value = supplier
    showForm.value = true
}
</script>
