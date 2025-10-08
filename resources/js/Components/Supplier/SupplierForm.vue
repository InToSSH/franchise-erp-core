<script setup>
import { computed } from 'vue'
import EntityFormModal from '@/Components/EntityFormModal.vue'
import CInputText from '@/Components/Form/CInputText.vue'

const props = defineProps({
    visible: Boolean,
    model: Object,
})

const emit = defineEmits(['update:visible', 'saved'])

const routes = {
    store: 'catalog.suppliers.store',
    update: 'catalog.suppliers.update',
}

const formTitle = computed(() =>
    props.model && props.model.id ? 'Upravit dodavatele' : 'Přidat dodavatele'
)

const defaultValues = {
    name: '',
    code: '',
    email: '',
    phone: ''
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
        #default="{ errors }"
    >
        <div class="grid gap-4">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <CInputText name="name" label="Název *" :error="errors.name" required/>
                </div>
                <div>
                    <CInputText name="code" label="Kód (pro importy)" :error="errors.code"/>
                </div>
                <div>
                    <CInputText name="email" label="Email *" :error="errors.email" required />
                </div>
                <div>
                    <CInputText name="phone" label="Telefon *" :error="errors.phone" required />
                </div>
                <div>
                    <CInputText name="cin" label="IČO" :error="errors.cin" />
                </div>
                <div>
                    <CInputText name="tin" label="DIČ" :error="errors.tin" />
                </div>
                <div>
                    <CInputText name="contact_person" label="Kontaktní osoba" :error="errors.contact_person" />
                </div>
                <div>
                    <CInputText name="bank_account" label="Číslo účtu" :error="errors.bank_account" />
                </div>
                <Divider class="md:col-span-2"/>
                <div class="md:col-span-2">
                    <CInputText name="street" label="Ulice" :error="errors.street" />
                </div>
                <div>
                    <CInputText name="city" label="Město" :error="errors.city" />
                </div>
                <div>
                    <CInputText name="post_code" label="PSČ" :error="errors.post_code" />
                </div>
            </div>
        </div>
    </EntityFormModal>
</template>
