<script setup>

import { defineProps } from 'vue'

const props = defineProps({
    name: String,
    label: String,
    error: String,
    required: Boolean,
    rows: {
        type: Number,
        default: 4
    }
})

const model = defineModel()

</script>

<template>
    <label class="block text-sm font-medium mb-1">{{ label }}</label>
    <Textarea :name="name" :required="required" :rows="rows" v-model="model" class="w-full" :invalid="!!error" />
    <small v-if="error" class="text-red-500">{{ error }}</small>
</template>
