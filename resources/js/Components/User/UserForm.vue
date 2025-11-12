<script setup>
import { computed } from 'vue'
import EntityFormModal from '@/Components/EntityFormModal.vue'
import CInputText from '@/Components/Form/CInputText.vue'

const props = defineProps({
    visible: Boolean,
    model: Object,
    branches: {
        type: Array,
        default: () => [],
    },
})

const emit = defineEmits(['update:visible', 'saved'])

const routes = {
    store: 'admin.users.store',
    update: 'admin.users.update',
}

const formTitle = computed(() =>
    props.model && props.model.id ? 'Upravit uživatele' : 'Přidat uživatele'
)

const defaultValues = {
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    branches: []
}

const initialValues = computed(() => ({
    ...defaultValues,
    ...(props.model || {}),
    branches: props.model?.branches?.map(branch => branch.value) || []
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
                <div class="col-span-2">
                    <CInputText name="name" label="Jméno a příjmení *" v-model="form.name" :error="errors.name" required autofocus />
                </div>
                <div>
                    <CInputText name="email" label="Email" v-model="form.email"  :error="errors.email" />
                </div>
                <div>
                    <CInputText name="phone" label="Telefon" v-model="form.phone"  :error="errors.phone" />
                </div>
                <div class="col-span-2 grid md:grid-cols-2 gap-4">
                    <div>
                        <CInputText name="password" label="Heslo" type="password" v-model="form.password"  :error="errors.password" />
                    </div>
                    <div>
                        <CInputText name="password_confirmation" label="Heslo (znovu)" type="password" v-model="form.password_confirmation"  :error="errors.password_confirmation" />
                    </div>
                </div>
                <Divider class="md:col-span-2"/>
                <div>
                    <label class="block text-sm font-medium mb-1">Pobočky</label>
                    <MultiSelect
                        v-model="form.branches"
                        :options="branches"
                        option-label="label"
                        option-value="value"
                        filter
                        placeholder="Vyberte pobočky"
                        class="w-full"
                    />
                </div>
            </div>
        </div>
    </EntityFormModal>
</template>
