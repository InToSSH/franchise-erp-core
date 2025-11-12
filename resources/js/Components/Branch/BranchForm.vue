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
    store: 'admin.branches.store',
    update: 'admin.branches.update',
}

const formTitle = computed(() =>
    props.model && props.model.id ? 'Upravit pobočku' : 'Přidat pobočku'
)

const defaultValues = {
    name: '',
    code: '',
    email: '',
    phone: '',
    cin: '',
    tin: '',
    street: '',
    city: '',
    post_code: '',
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
                <div>
                    <CInputText name="name" label="Název *" v-model="form.name" :error="errors.name" required autofocus />
                </div>
                <div>
                    <CInputText name="code" label="Kód (pro importy)" v-model="form.code" :error="errors.code" />
                </div>
                <div>
                    <CInputText name="email" label="Email" v-model="form.email"  :error="errors.email" />
                </div>
                <div>
                    <CInputText name="phone" label="Telefon" v-model="form.phone"  :error="errors.phone" />
                </div>
                <div>
                    <CInputText name="cin" label="IČO" v-model="form.cin"  :error="errors.cin" />
                </div>
                <div>
                    <CInputText name="tin" label="DIČ" v-model="form.tin"  :error="errors.tin" />
                </div>
                <Divider class="md:col-span-2"/>
                <div class="md:col-span-2">
                    <CInputText name="street" label="Ulice" v-model="form.street"  :error="errors.street" />
                </div>
                <div>
                    <CInputText name="city" label="Město" v-model="form.city"  :error="errors.city" />
                </div>
                <div>
                    <CInputText name="post_code" label="PSČ" v-model="form.post_code"  :error="errors.post_code" />
                </div>
            </div>
        </div>
    </EntityFormModal>
</template>
