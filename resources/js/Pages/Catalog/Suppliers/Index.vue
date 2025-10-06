<template>
    <AppLayout>
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-semibold">Dodavatelé</h1>
                <Button label="Přidat dodavatele" icon="pi pi-plus" @click="openCreate" />
            </div>

            <!-- Search -->
            <div class="flex items-center gap-2 max-w-sm">
                <IconField>
                    <InputIcon>
                        <i class="pi pi-search" />
                    </InputIcon>
                    <InputText
                        v-model="search"
                        placeholder="Vyhledat..."
                        @keyup.enter="onSearch"
                        class="w-full"
                    />
                </IconField>
                <Button label="Hledat" icon="pi pi-search" @click="onSearch" :loading="loading" />
            </div>

            <!-- Table -->
            <DataTable
                :value="suppliers.data"
                dataKey="id"
                paginator
                :rows="suppliers.per_page"
                :totalRecords="suppliers.total"
                :first="(suppliers.current_page - 1) * suppliers.per_page"
                :lazy="true"
                @page="onPage"
                :loading="loading"
                tableStyle="min-width: 60rem"
                stripedRows
                class="shadow rounded-lg"
            >
                <Column field="name" header="Name" sortable />
                <Column field="contact_person" header="Contact" />
                <Column field="email" header="Email" />
                <Column field="phone" header="Phone" />
                <Column header="Status">
                    <template #body="{ data }">
                        <Tag :value="data.is_active ? 'Active' : 'Inactive'" :severity="data.is_active ? 'success' : 'danger'" />
                    </template>
                </Column>
                <Column header="Actions" bodyClass="text-center">
                    <template #body="{ data }">
                        <div class="flex gap-2 justify-center">
                            <Button
                                icon="pi pi-pencil"
                                severity="secondary"
                                rounded
                                text
                                @click="$inertia.visit(route('suppliers.edit', data.id))"
                            />
                            <Button
                                icon="pi pi-trash"
                                severity="danger"
                                rounded
                                text
                                @click="$inertia.delete(route('suppliers.destroy', data.id))"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
            <SupplierForm v-model:visible="showForm" :supplier="editingSupplier" />
        </div>

    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/Sakai/AppLayout.vue';
import SupplierForm from "@/Components/Supplier/SupplierForm.vue";
import {ref} from 'vue'
import {router} from '@inertiajs/vue3'

const props = defineProps({
    suppliers: Object,
})

const search = ref('')
const loading = ref(false)

const onSearch = () => {
    loading.value = true
    router.get(
        route('suppliers.index'),
        {search: search.value},
        {
            preserveState: true,
            replace: true,
            onFinish: () => (loading.value = false),
        }
    )
}

function onPage(event) {
    router.get(
        route('suppliers.index'),
        { page: event.page + 1, search: search.value },
        { preserveState: true, replace: true }
    )
}

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
