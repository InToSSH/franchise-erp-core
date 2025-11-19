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
                delete-acl="sales.orders.delete"
            >
                <Column field="increment_number" header="Číslo objednávky" sortable>
                    <template #body="{ data }">
                        <a
                            href="#"
                            class="text-blue-600 hover:underline"
                            @click.prevent="openEdit(data)"
                        >
                            {{ data.increment_number }}
                        </a>
                    </template>
                </Column>
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
                <Column field="createdBy.name" header="Vytvořil" sortable />
                <Column field="approvedBy.name" header="Schválil" sortable>
                    <template #body="{ data }">
                        <span v-if="data.approvedBy" v-tooltip="'Schváleno dne ' + (data.approved_at ? $formatDateTime(data.approved_at) : 'N/A')">
                            {{ data.approvedBy.name }}
                        </span>
                        <span v-else class="text-gray-400 italic">
                            Neschváleno
                        </span>
                    </template>
                </Column>
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
                        v-tooltip.left="'Schválit či zamítnout objednávku'"
                        @click="confirmApprove(data)"
                        text
                    />
                    <Button
                        v-if="data.status === 'draft' && !$userCan('sales.orders.approve')"
                        icon="pi pi-check"
                        severity="success"
                        v-tooltip.left="'Předat ke schválení'"
                        @click="setAwaitingApproval(data)"
                        text
                    />

                </template>
                <template #actionsAfter="{ data }">
                    <Button
                        v-if="data.is_cancelable && $userCan('sales.orders.edit')"
                        icon="pi pi-times"
                        severity="danger"
                        v-tooltip.left="'Zrušit objednávku'"
                        @click="cancelOrder(data)"
                        text
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
        message: `• Po schválení dojde k odeslání objednávky dodavateli.\n\n• Při zamítnutí přejde objednávka do stavu "Koncept" a bude možné ji upravit.`,
        header: 'Shválit či zamítnout objednávku?',
        icon: 'pi pi-info-circle',
        rejectProps: {
            label: 'Zamítnout',
            severity: 'danger',
            outlined: false
        },
        acceptProps: {
            label: 'Schválit',
            severity: 'success'
        },
        accept: () => {
            router.put(route('sales.orders.approve', data.id), {}, {
                preserveState: true,
            })
        },
        reject: () => {
            if (data.status !== 'draft') {
                router.put(route('sales.orders.return-to-draft', data.id), {}, {
                    preserveState: true,
                })
            }
        }
    })
}

function setAwaitingApproval(data) {
    router.put(route('sales.orders.set-awaiting-approval', data.id), {}, {
        preserveState: true,
    })
}

function cancelOrder(data) {
    router.put(route('sales.orders.cancel', data.id), {}, {
        preserveState: true,
    })
}
</script>
