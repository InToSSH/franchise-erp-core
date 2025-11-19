<script setup>
import {computed, onMounted, watch} from 'vue'
import EntityFormModal from '@/Components/EntityFormModal.vue'
import CInputText from '@/Components/Form/CInputText.vue'
import CAutocomplete from "@/Components/Form/CAutocomplete.vue";
import CInputTextArea from "@/Components/Form/CInputTextArea.vue";
import CAutocompleteLazy from "@/Components/Form/CAutocompleteLazy.vue";
import {useToast} from "primevue/usetoast";
import {useConfirm} from "primevue";
const toast = useToast();
const confirm = useConfirm();
const props = defineProps({
    visible: Boolean,
    model: Object,
    branches: {
        type: Array,
        default: () => [],
    }
})

const emit = defineEmits(['update:visible', 'saved'])

const routes = {
    store: 'sales.orders.store',
    update: 'sales.orders.update',
}

const formTitle = computed(() =>
    props.model && props.model.id ? 'Upravit objednávku' : 'Vytvořit objednávku'
)

const defaultValues = {
    custom_number: '',
    branch_id: '',
    note: '',
    items: [],
    is_draft: true,
    is_approved: false
}

const initialValues = computed(() => ({
    ...defaultValues,
    ...(props.model || {}),
}))


watch(() => props.visible, (newValue) => {
    if (newValue && (!props.model || !props.model.items || props.model.items.length === 0)) {
        addOrderItem(initialValues.value)
    }

    if (newValue && initialValues.value.items.length > 0) {
        initialValues.value.items.forEach(item => {
            if (item.product) {
                item.unit_price = item.product.unit_price * 1
                calculateTotal(item)
            }
        })
    }
})

const addOrderItem = (form) => {
    form.items.push({
        product_id: null,
        product: null,
        quantity: 1,
        unit_price: 0,
        total_price: 0,
    })
}

const removeOrderItem = (form, index) => {
    form.items.splice(index, 1)
}

const onProductSelect = (item, product, allItems, index) => {
    console.log('Selected product:', product)
    if (product && product.id) {
        if (isDuplicateProduct(allItems, product.id, item)) {
            item.product = ''
            item.product_id = null
            item.unit_price = 0
            calculateTotal(item)
            toast.add({severity: 'warn', summary: 'Pozor!', detail: 'Tato položka se již nachází v objednávce.', life: 3000});
            return
        }
        item.product_id = product.id
        item.unit_price = product.unit_price * 1
    } else {
        item.product_id = null
        item.unit_price = 0
    }
    calculateTotal(item)
}

const isDuplicateProduct = (items, productId, currentItem) =>
    productId &&
    items.some(i => i !== currentItem && i.product_id === productId)

const calculateTotal = (item) => {
    item.total_price = (item.quantity || 0) * (item.unit_price || 0)
}

const submitFormLocal = (form, submitFormParent, isDraft, isApproved) => {
    form.is_draft = isDraft
    form.is_approved = isApproved
    submitFormParent()
}

const confirmSubmitAndApprove = (form, submitFormParent) => {
    confirm.require({
        message: `Dojde k okamžitému odeslání objednávky. Tato akce je nevratná.`,
        header: 'Opravdu chcete uložit a schválit tuto objednávku?',
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
            submitFormLocal(form, submitFormParent, false, true)
        },
    })
}

</script>

<template>
    <EntityFormModal
        v-bind:visible="props.visible"
        v-on:update:visible="emit('update:visible', $event)"
        :title="formTitle"
        :routes="routes"
        :model-id="props.model?.id"
        :initial-values="initialValues"
        :read-only="!$userCan('sales.orders.edit') || (props.model && !props.model.is_editable)"
        @saved="emit('saved')"

    >
        <template #default="{ errors, form }">
            <div class="grid gap-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <CAutocomplete
                            name="branch_id"
                            label="Objednat pro pobočku *"
                            :items="branches"
                            v-model="form.branch_id"
                            :error="errors.branch_id"
                            option-label="label"
                            required
                            autofocus
                        />
                    </div>
                    <div>
                        <CInputText name="custom_number" label="Vlastní označení" v-model="form.custom_number" :error="errors.custom_number"/>
                    </div>
                    <div class="col-span-2">
                        <CInputTextArea name="note" label="Interní poznámka" v-model="form.note" :rows="2" :error="errors.note" />
                    </div>
                </div>
                <Divider/>
                <div class="order-items-section">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-lg font-semibold">Položky objednávky</h4>
                        <Button
                            label="Přidat položku"
                            icon="pi pi-plus"
                            @click="addOrderItem(form)"
                            size="small"
                            :disabled="!$userCan('sales.orders.edit')"
                        />
                    </div>

                    <DataTable :value="form.items" class="p-datatable-sm">
                        <Column header="#" style="width: 50px">
                            <template #body="{ index }">
                                {{ index + 1 }}
                            </template>
                        </Column>
                        <Column header="" style="width: 80px">
                            <template #body="{ data }">
                                <img v-if="data.product && data.product.image_path" :src="route('assets.images', { path: data.product.image_path })" alt="" class="w-12 h-12 object-cover rounded" />
                                <img v-else src="/img/no-img.png" alt="" class="w-12 h-12 object-cover rounded" />
                            </template>
                        </Column>
                        <Column header="Produkt *" style="min-width: 300px">
                            <template #body="{ data, index }">
                                <CAutocompleteLazy
                                    :name="`items.${index}.product`"
                                    route="api.catalog.products.search"
                                    v-model="form.items[index].product"
                                    result-type="object"
                                    @update:modelValue="onProductSelect(data, $event, form.items, index)"
                                    :error="form.errors[`items.${index}.product_id`]"
                                    placeholder="Vyberte produkt"
                                >
                                    <template #option="{ option }">
                                        <div class="flex items-start gap-3">
                                            <img v-if="option.image_path" :src="route('assets.images', { path: option.image_path })" alt="" class="w-9 h-9 object-cover rounded" />
                                            <div class="flex flex-col">
                                                <span class="font-medium">{{ option.label }}</span>
                                                <span class="text-sm text-gray-500">SKU: {{ option.sku }} | Kategorie: {{ option.category_name }}</span>
                                            </div>
                                        </div>
                                    </template>
                                </CAutocompleteLazy>
                            </template>
                        </Column>

                        <Column header="Množství *" style="width: 120px">
                            <template #body="{ data, index }">
                                <InputNumber
                                    v-model="data.quantity"
                                    @update:modelValue="calculateTotal(data)"
                                    :min="1"
                                    :class="{ 'p-invalid': errors[`items.${index}.quantity`] }"
                                />
                            </template>
                        </Column>

                        <Column header="Jedn. cena" style="width: 120px">
                            <template #body="{ data }">
                                {{ $formatCurrency(data.unit_price) }}
                            </template>
                        </Column>

                        <Column header="Celkem" style="width: 120px">
                            <template #body="{ data }">
                                {{ $formatCurrency(data.total_price) }}
                            </template>
                        </Column>

                        <Column style="width: 80px">
                            <template #body="{ index }">
                                <Button
                                    icon="pi pi-trash"
                                    severity="danger"
                                    text
                                    @click="removeOrderItem(form, index)"
                                    :disabled="!$userCan('sales.orders.edit')"
                                />
                            </template>
                        </Column>
                    </DataTable>
                    <!-- Add total price of the order -->
                    <div class="flex justify-end mt-4">
                    <span class="font-semibold text-lg">
                        Celková částka: {{ $formatCurrency(form.items.reduce((sum, item) => sum + (item.total_price || 0), 0)) }}
                    </span>
                    </div>
                </div>
            </div>
        </template>

        <template #buttons="{ form, readOnly, submitForm }">
            <Button label="Zrušit" text @click="emit('update:visible', false)" />
            <Button
                label="Uložit jako koncept"
                @click.prevent="submitFormLocal(form, submitForm, true, false)"
                severity="secondary"
                :loading="form.processing"
                :disabled="readOnly"
            />
            <Button
                label="Uložit ke schválení"
                @click.prevent="submitFormLocal(form, submitForm, false, false)"
                severity="info"
                v-if="!$userCan('sales.orders.approve')"
                :loading="form.processing"
                :disabled="readOnly"
            />
            <Button
                label="Uložit a schválit"
                @click.prevent="confirmSubmitAndApprove(form, submitForm)"
                severity="success"
                v-if="$userCan('sales.orders.approve')"
                :loading="form.processing"
                :disabled="readOnly"
            />
        </template>
    </EntityFormModal>
</template>
