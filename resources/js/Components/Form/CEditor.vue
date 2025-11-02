<script setup>

import { defineProps } from 'vue'

const props = defineProps({
    name: String,
    label: String,
    error: String,
    editorStyle: {
        type: String,
        default: 'height: 200px'
    },
})

const model = defineModel()

</script>

<template>
    <label class="block text-sm font-medium mb-1">{{ label }}</label>
    <Editor :name="name" v-model="model" :editorStyle="editorStyle" :invalid="!!error"/>
    <small v-if="error" class="text-red-500">{{ error }}</small>
</template>
