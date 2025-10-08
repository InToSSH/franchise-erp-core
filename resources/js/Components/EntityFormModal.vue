<script setup>
import { Form } from '@inertiajs/vue3'

const props = defineProps({
    visible: Boolean,
    title: String,
    modelId: [Number, String, null],
    routes: Object, // { store, update }
    initialValues: Object, // form fields
})

const emit = defineEmits(['update:visible', 'saved'])

function getAction() {
    if (props.modelId) {
        return route(props.routes.update, props.modelId)
    }
    return route(props.routes.store)
}
</script>

<template>
    <Dialog
        v-bind:visible="visible"
        v-on:update:visible="emit('update:visible', $event)"
        modal
        :header="title"
        class="w-full max-w-4xl"
        draggable
        maximizable
    >
        <Form
            :action="getAction()"
            :method="modelId ? 'put' : 'post'"
            :defaults="initialValues"
            @success="() => { emit('saved'); emit('update:visible', false) }"
            #default="{ errors, processing }"
        >
            <!-- Pass form slot -->
            <slot :errors="errors" />

            <div class="flex justify-end gap-2 mt-6">
                <Button label="Zrušit" text @click="visible = false" />
                <Button type="submit" label="Uložit" :loading="processing" />
            </div>

        </Form>
    </Dialog>
</template>
