<template>
    <AppLayout>
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-semibold">Produkty</h1>
                <Button label="Přidat produkt" icon="pi pi-plus" @click="openCreate" />
            </div>


            <DataGrid
                :resource="products"
                route-prefix="catalog.products"
                @editAction="openEdit"
            >
                <Column field="name" header="Název" sortable />
                <Column field="sku" header="SKU" sortable />
                <Column field="category.name" header="Kategorie" />
                <Column field="price" header="Cena" sortable />
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

const props = defineProps({
    products: Object,
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
