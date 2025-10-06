<script setup>
import { computed } from 'vue'
import { useInertiaFormSubmit } from '@/composables/useInertiaFormSubmit'
import EntityFormModal from '@/Components/EntityFormModal.vue'

const props = defineProps({
    visible: Boolean,
    supplier: Object, // null for create, object for edit
})

// ✅ Writable computed wrapper for v-model
const visible = computed({
    get: () => props.visible,
    set: (value) => emit('update:visible', value),
})

const emit = defineEmits(['update:visible'])

const isEdit = computed(() => !!props.supplier?.id)

const { form, loading, errors, submit } = useInertiaFormSubmit(
    props.supplier || {
        name: '',
        code: '',
        contact_person: '',
        email: '',
        phone: '',
        street: '',
        city: '',
        post_code: '',
        cin: '',
        tin: '',
        bank_account: '',
    },
    { store: 'catalog.suppliers.store', update: 'catalog.suppliers.update' }
)

function handleSubmit() {
    submit(isEdit.value)
}
</script>

<template>
    <EntityFormModal
        v-model:visible="visible"
        :title="isEdit ? 'Upravit dodavatele' : 'Přidat dodavatele'"
        :loading="loading"
        @submit="handleSubmit"
        @cancel="emit('update:visible', false)"
    >
        <div class="grid gap-4">
            <div>
                <label>Název</label>
                <InputText v-model="form.name" class="w-full" :invalid="!!errors.name" />
                <small v-if="errors.name" class="text-red-500">{{ errors.name }}</small>
            </div>

            <div>
                <label>Kontaktní osoba</label>
                <InputText v-model="form.contact_person" class="w-full" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Email</label>
                    <InputText v-model="form.email" class="w-full" />
                </div>
                <div>
                    <label>Telefon</label>
                    <InputText v-model="form.phone" class="w-full" />
                </div>
            </div>

            <div>
                <label>Ulice</label>
                <InputText v-model="form.street" class="w-full" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Město</label>
                    <InputText v-model="form.city" class="w-full" />
                </div>
                <div>
                    <label>PSČ</label>
                    <InputText v-model="form.post_code" class="w-full" />
                </div>
            </div>
        </div>
    </EntityFormModal>
</template>
