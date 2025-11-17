<template>
    <AppLayout>
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-semibold">Objednávky</h1>
                <Button label="Vytvořit objednávku" icon="pi pi-plus" @click="openCreate" v-if="$userCan('sales.orders.edit')"/>
            </div>


            <DataGrid
                :resource="orders"
                route-prefix="sales.orders"
                @editAction="openEdit"
                delete-acl="sales.orders.edit"
            >
                <Column field="increment_number" header="Číslo objednávky" sortable />
                <Column field="branch.name" header="Pobočka" sortable />
                <Column field="custom_number" header="Vlastní označení" sortable />
                <Column field="total_price" header="Celková částka">
                    <template #body="{ data }">
                        {{ $formatCurrency(data.total_price) }}
                    </template>
                </Column>
                <Column field="status" header="Status" sortable>
                    <template #body="{ data }">
                        <Badge :value="data.status_option.label" :severity="{
                            draft: 'secondary',
                            awaiting_approval: 'info',
                            delivered: 'success',
                            canceled: 'danger'
                        }[data.status]" />
                    </template>
                </Column>
                <Column field="createdBy.name" header="Uživatel" sortable />
                <Column field="created_at" header="Vytvořeno">
                    <template #body="{ data }">
                        {{ $formatDateTime(data.created_at) }}
                    </template>
                </Column>
                <template #actionsBefore="{ data }">
                    <Button
                        v-if="['awaiting_approval', 'draft'].includes(data.status) && $userCan('sales.orders.approve')"
                        icon="pi pi-check-circle"
                        severity="warn"
                        v-tooltip="'Schválit objednávku'"
                        rounded
                        text
                        @click="confirmApprove(data)"
                    />
                    <Button
                        v-if="data.status === 'draft' && !$userCan('sales.orders.approve')"
                        icon="pi pi-check"
                        severity="success"
                        v-tooltip="'Předat ke schválení'"
                        rounded
                        text
                        @click="setAwaitingApproval(data)"
                    />
                </template>
            </DataGrid>
            <OrderForm
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
import SupplierForm from "@/Components/Supplier/SupplierForm.vue";
import {ref} from 'vue'
import DataGrid from "@/Components/DataGrid.vue";
import OrderForm from "@/Components/Order/OrderForm.vue";
import {router} from "@inertiajs/vue3";
import {useConfirm} from "primevue";

const props = defineProps({
    orders: Object,
    branches: Array
})

const showForm = ref(false)
const editingModel = ref(null)
const confirm = useConfirm();

function openCreate() {
    editingModel.value = null
    showForm.value = true
}

function openEdit(supplier) {
    editingModel.value = supplier
    showForm.value = true
}

function confirmApprove(data) {
    confirm.require({
        message: `Po schválení dojde k odeslání objednávky dodavateli. Opravdu chcete pokračovat?`,
        header: 'Shválit objednávku?',
        icon: 'pi pi-info-circle',
        rejectProps: {
            label: 'Zrušit',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Schválit',
            severity: 'success'
        },
        accept: () => {

        },
    })
}

function setAwaitingApproval(data) {
    router.post(route('sales.orders.setAwaitingApproval', data.id), {}, {
        preserveState: true
    })
}
</script>
