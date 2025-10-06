<script setup>
const props = defineProps({
    visible: Boolean,
    title: String,
    loading: Boolean,
})

const emit = defineEmits(['update:visible', 'submit', 'cancel'])
</script>

<template>
    <Dialog
        :visible="visible"
        modal
        :header="title"
        class="w-full max-w-lg"
        @update:visible="emit('update:visible', $event)"
    >
        <form @submit.prevent="emit('submit')">
            <div class="space-y-4">
                <!-- Slot for form fields -->
                <slot />
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <Button
                    label="Zrušit"
                    icon="pi pi-times"
                    type="button"
                    severity="secondary"
                    @click="emit('cancel')"
                />
                <Button
                    label="Uložit"
                    icon="pi pi-check"
                    type="submit"
                    :loading="loading"
                />
            </div>
        </form>
    </Dialog>
</template>
