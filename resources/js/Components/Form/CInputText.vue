<script setup>

import { defineProps } from 'vue'

const props = defineProps({
    name: String,
    label: String,
    error: String,
    required: Boolean,
    autofocus: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'text'
    }
})

const model = defineModel()

</script>

<template>
    <label class="block text-sm font-medium mb-1">{{ label }}</label>
    <InputText
        v-model="model"
        :name="name"
        :type="type"
        :required="required"
        :autofocus="autofocus"
        class="w-full"
        :invalid="!!error"
    />
    <small v-if="error" class="text-red-500">{{ error }}</small>
</template>
