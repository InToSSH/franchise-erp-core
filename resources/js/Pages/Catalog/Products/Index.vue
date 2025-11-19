<template>
    <AppLayout>
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-semibold">Produkty</h1>
                <Button label="Přidat produkt" icon="pi pi-plus" @click="openCreate" v-if="$userCan('catalog.products.edit')"/>
            </div>

            <div class="grid grid-cols-10 gap-2">
                <div class="col-span-2 font-semibold">
                    <CategorySelectTree :expanded="true" @selected="categorySelected"></CategorySelectTree>
                </div>
                <div class="col-span-8">
                    <DataGrid
                        :resource="products"
                        route-prefix="catalog.products"
                        @editAction="openEdit"
                        delete-acl="catalog.products.edit"
                    >
                        <Column header="Obrázek">
                            <template #body="slotProps">
                                <img :src="route('assets.images', {path: slotProps.data.image_path})" :alt="slotProps.data.image" class="w-28 rounded" />
                            </template>
                        </Column>
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
                        <Column field="sku" header="SKU" sortable />
                        <Column field="category.name" header="Kategorie" />
                        <Column field="supplier.name" header="Dodavatel" />
                        <Column field="price" header="Cena" sortable>
                            <template #body="{ data }">
                                {{ $formatCurrency(data.price) }}
                            </template>
                        </Column>
                    </DataGrid>
                </div>
            </div>


            <ProductForm
                v-model:visible="showForm"
                :model="editingModel"
                :suppliers="suppliers"
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
import CategorySelectTree from "@/Components/Category/CategorySelectTree.vue";
import {router} from "@inertiajs/vue3";
import ProductForm from "@/Components/Product/ProductForm.vue";

const props = defineProps({
    products: Object,
    categories: Array,
    suppliers: Array,
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

function categorySelected(categoryId) {
    console.log("Selected category ID:", categoryId);
    router.reload({only: ['products'], data: {category_id: categoryId}});
    // Implement filtering logic here
}
</script>
