<script setup>

import {defineProps} from 'vue'

const props = defineProps({
    name: String,
    label: String,
    error: String,
    required: Boolean,
    min: {
        type: Number,
        default: null
    },
    max: {
        type: Number,
        default: null
    },
    mode: {
        type: String,
        default: 'decimal' // or 'integer'
    },
    currency: {
        type: String,
        default: undefined
    },
    prefix: {
        type: String,
        default: undefined
    },
    suffix: {
        type: String,
        default: undefined
    },
    minFractionDigits: {
        type: Number,
        default: undefined
    },
    maxFractionDigits: {
        type: Number,
        default: undefined
    },
})

const model = defineModel()

</script>

<template>
    <label class="block text-sm font-medium mb-1">{{ label }}</label>
    <InputNumber
        v-model="model"
        :name="name"
        :mode="mode"
        :currency="currency"
        :required="required"
        :min="min"
        :max="max"
        :prefix="prefix"
        :suffix="suffix"
        :min-fraction-digits="minFractionDigits"
        :max-fraction-digits="maxFractionDigits"
        currency-display="symbol"
        locale="cs-CZ"
        class="w-full"
        :invalid="!!error"
        fluid
    />
    <small v-if="error" class="text-red-500">{{ error }}</small>
</template>
