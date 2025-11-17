<script setup>
import { useForm } from '@inertiajs/vue3'
import {watch} from "vue";

const props = defineProps({
    visible: Boolean,
    title: String,
    modelId: [Number, String, null],
    routes: Object, // { store, update }
    initialValues: Object, // form fields
    readOnly: {
        type: Boolean,
        default: false
    }
})

const form = useForm({
    ...props.initialValues
})

watch(() => props.visible, (newValue) => {
    if (newValue) {
        form.defaults({
            ...props.initialValues
        })
        form.reset()
    }
})

const emit = defineEmits(['update:visible', 'saved'])

function getAction() {
    if (props.modelId) {
        return route(props.routes.update, props.modelId)
    }
    return route(props.routes.store)
}

function submitForm() {
    if (props.modelId) {
        form.put(getAction(), {
            preserveScroll: true,
            onSuccess: () => {
                emit('saved')
                emit('update:visible', false)
            }
        })
    } else {
        form.post(getAction(), {
            preserveScroll: true,
            onSuccess: () => {
                emit('saved')
                emit('update:visible', false)
            }
        })
    }
}
</script>

<template>
    <Dialog
        v-bind:visible="visible"
        v-on:update:visible="emit('update:visible', $event)"
        modal
        :header="title"
        class="w-full max-w-7xl"
        draggable
        maximizable
    >
        <form @submit.prevent="submitForm">
            <!-- Pass form slot -->
            <fieldset :disabled="readOnly" class="space-y-4">
            <slot :errors="form.errors" :form="form" :readOnly="readOnly"/>
            <div class="flex justify-end gap-2 mt-6">
                <slot name="buttons" :form="form" :visible="visible" :readOnly="readOnly" :submitForm="submitForm">
                    <!-- Default buttons if no slot provided -->
                    <Button label="Zrušit" text @click="visible = false" />
                    <Button type="submit" label="Uložit" :loading="form.processing" />
                </slot>
            </div>
            </fieldset>
        </form>
    </Dialog>
</template>
