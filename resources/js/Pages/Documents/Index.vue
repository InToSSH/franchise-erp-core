<template>
    <AppLayout title="Dokumenty">
        <div class="p-6 space-y-4">
            <PageHeader
                title="Dokumenty"
                new-btn-title="Přidat dokument"
                new-btn-acl="documents.documents.edit"
                @click:new="openCreate"
            />
            <div class="lg:hidden">
                <CategoryTreeSelect
                    name="document_category_id"
                    label="Zobrazená kategorie"
                    show-clear
                    @selected="categorySelected"
                />
            </div>
            <div class="grid grid-cols-10 gap-2">
                <div class="col-span-2 font-semibold hidden lg:block">
                    <DocumentCategorySelectTree :expanded="true" @selected="categorySelected"></DocumentCategorySelectTree>
                </div>
                <div class="col-span-10 lg:col-span-8">
                    <DataView :value="documents" paginator :rows="5">
                        <template #list="slotProps">
                            <div class="flex flex-col">
                                <div v-for="(item, index) in slotProps.items" :key="index">
                                    <div class="flex flex-col sm:flex-row sm:items-center p-6 gap-4" :class="{ 'border-t border-surface-200 dark:border-surface-700': index !== 0 }">
                                        <div class="md:w-40 relative">
                                            <img class="block xl:block mx-auto rounded w-full" :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`" :alt="item.name" />
                                            <div class="absolute bg-black/70 rounded-border" style="left: 4px; top: 4px">
                                                <Tag :value="item.inventoryStatus" :severity="getSeverity(item)"></Tag>
                                            </div>
                                        </div>
                                        <div class="flex flex-col md:flex-row justify-between md:items-center flex-1 gap-6">
                                            <div class="flex flex-row md:flex-col justify-between items-start gap-2">
                                                <div>
                                                    <span class="font-medium text-surface-500 dark:text-surface-400 text-sm">{{ item.category }}</span>
                                                    <div class="text-lg font-medium mt-2">{{ item.name }}</div>
                                                </div>
                                                <div class="bg-surface-100 p-1" style="border-radius: 30px">
                                                    <div class="bg-surface-0 flex items-center gap-2 justify-center py-1 px-2" style="border-radius: 30px; box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04), 0px 1px 2px 0px rgba(0, 0, 0, 0.06)">
                                                        <span class="text-surface-900 font-medium text-sm">{{ item.rating }}</span>
                                                        <i class="pi pi-star-fill text-yellow-500"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col md:items-end gap-8">
                                                <span class="text-xl font-semibold">${{ item.price }}</span>
                                                <div class="flex flex-row-reverse md:flex-row gap-2">
                                                    <Button icon="pi pi-heart" variant="outlined"></Button>
                                                    <Button icon="pi pi-shopping-cart" label="Buy Now" :disabled="item.inventoryStatus === 'OUTOFSTOCK'" class="flex-auto md:flex-initial whitespace-nowrap"></Button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </DataView>
                </div>
            </div>


            <DocumentForm
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
import CategorySelectTree from "@/Components/Category/CategorySelectTree.vue";
import {router} from "@inertiajs/vue3";
import ProductForm from "@/Components/Product/ProductForm.vue";
import PageHeader from "@/Components/PageHeader.vue";
import CategoryTreeSelect from "@/Components/Form/CategoryTreeSelect.vue";
import DocumentCategorySelectTree from "@/Components/DocumentCategory/DocumentCategorySelectTree.vue";
import DocumentForm from "@/Components/Document/DocumentForm.vue";

const props = defineProps({
    documents: Object,
    categories: Array,
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
    router.reload({only: ['documents'], data: {document_category_id: categoryId}});
}
</script>
