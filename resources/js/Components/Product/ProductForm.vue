<script setup>
import { computed } from 'vue'
import EntityFormModal from '@/Components/EntityFormModal.vue'
import CInputText from '@/Components/Form/CInputText.vue'
import CInputTextArea from "@/Components/Form/CInputTextArea.vue";
import CInputNumber from "@/Components/Form/CInputNumber.vue";
import CEditor from "@/Components/Form/CEditor.vue";
import CFileUpload from "@/Components/Form/CFileUpload.vue";
import CAutocomplete from "@/Components/Form/CAutocomplete.vue";
import CategoryTreeSelect from "@/Components/Form/CategoryTreeSelect.vue";

const props = defineProps({
    visible: Boolean,
    model: Object,
    suppliers: {
        type: Array,
        default: () => [],
    },
})

const emit = defineEmits(['update:visible', 'saved'])

const routes = {
    store: 'catalog.products.store',
    update: 'catalog.products.update',
}

const formTitle = computed(() =>
    props.model && props.model.id ? 'Upravit zboží' : 'Přidat zboží'
)

const defaultValues = {
    name: '',
    sku: '',
    category_id: '',
    supplier_id: '',
    ean: '',
    description: '',
    weight: '',
    price: '',
    qty_in_pack: '',
    image: '',
}

const initialValues = computed(() => ({
    ...defaultValues,
    ...(props.model || {}),
}))
</script>

<template>
    <EntityFormModal
        v-bind:visible="props.visible"
        v-on:update:visible="emit('update:visible', $event)"
        :title="formTitle"
        :routes="routes"
        :model-id="props.model?.id"
        :initial-values="initialValues"
        @saved="emit('saved')"
        #default="{ errors, form }"
    >
        <div class="grid gap-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <CInputText name="name" label="Název *" v-model="form.name" :error="errors.name" required autofocus/>
                </div>
                <div>
                    <CInputText name="code" label="SKU *" v-model="form.sku" :error="errors.sku" required />
                </div>
                <div>
                    <CInputText name="ean" label="EAN" v-model="form.ean" :error="errors.ean"/>
                </div>
                <div>
                    <CategoryTreeSelect
                        name="category_id"
                        label="Kategorie *"
                        v-model="form.category_id"
                        :error="errors.category_id"
                    />
                </div>
                <div>
                    <CAutocomplete
                        name="supplier_id"
                        label="Dodavatel *"
                        :items="suppliers"
                        v-model="form.supplier_id"
                        :error="errors.supplier_id"
                        required
                    />
                </div>
                <div class="md:col-span-2">
                    <CEditor name="description" label="Popis" v-model="form.description" :error="errors.description" />
                </div>
                <div>
                    <CInputNumber name="price" label="Cena" mode="currency" currency="CZK" v-model="form.price" :error="errors.price" />
                </div>
                <div>
                    <CInputNumber name="weight" :min-fraction-digits="3" label="Váha" suffix=" kg" v-model="form.weight"  :error="errors.weight" />
                </div>
                <div>
                    <CInputNumber name="qty_in_pack" label="Ks v balení" v-model="form.qty_in_pack"  :error="errors.qty_in_pack" />
                </div>
                <Divider class="md:col-span-2"/>
                <div>
                    <CFileUpload name="image" label="Obrázek" v-model="form.image" :error="errors.image" />
                </div>
            </div>
        </div>
    </EntityFormModal>
</template>
