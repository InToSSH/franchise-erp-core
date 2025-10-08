<script setup>

import { defineProps } from 'vue'

const props = defineProps({
    name: String,
    label: String,
    error: String,
    required: Boolean,
})

</script>

<template>
    <label class="block text-sm font-medium mb-1">{{ label }}</label>
    <InputText :name="name" :required="required" autofocus class="w-full" :invalid="!!error" />
    <small v-if="error" class="text-red-500">{{ error }}</small>
</template>
