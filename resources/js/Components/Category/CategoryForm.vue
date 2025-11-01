<script setup>
import { computed } from 'vue'
import EntityFormModal from '@/Components/EntityFormModal.vue'
import CInputText from '@/Components/Form/CInputText.vue'

const props = defineProps({
    visible: Boolean,
    model: Object,
    additionalData: {
        type: Object,
        default: () => ({}),
    },
})

const emit = defineEmits(['update:visible', 'saved'])

const routes = {
    store: 'catalog.categories.store',
    update: 'catalog.categories.update',
}

const formTitle = computed(() =>
    props.model && props.model.id ? 'Upravit kategorii' : 'Přidat kategorii'
)

const defaultValues = {
    name: '',
    code: '',
}

const initialValues = computed(() => ({
    ...defaultValues,
    ...(props.model || {}),
    ...(props.additionalData || {}),
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
                <div>
                    <CInputText name="name" label="Název *" v-model="form.name" :error="errors.name" required/>
                </div>
                <div>
                    <CInputText name="code" label="Kód (pro importy) *" v-model="form.code" :error="errors.code" required />
                </div>
            </div>
        </div>
    </EntityFormModal>
</template>
