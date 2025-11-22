<script setup>

import {defineProps, onMounted, ref, watch} from 'vue'
import {CategoryService} from "@/service/CategoryService.js";

const categories = ref([]);
const selectedCategory = ref();

onMounted(() => {
    CategoryService.getCategoryTree().then(response => {
        categories.value = response.data;
    });
});

const props = defineProps({
    name: String,
    label: String,
    error: String,
    readOnly: {
        type: Boolean,
        default: false
    },
    showClear: {
        type: Boolean,
        default: true
    },
})

const model = defineModel()

watch(model, (newValue) => {
    selectedCategory.value = newValue ? { [newValue]: true } : null;
}, { immediate: true })
const optionSelected = (e) => {
    model.value = e ? Object.keys(e)[0] : null;
    emit('selected', model.value);
};

const emit = defineEmits(['selected'])
</script>

<template>
    <label class="block text-sm font-medium mb-1">{{ label }}</label>
    <TreeSelect
        v-model="selectedCategory"
        :name="name"
        :options="categories"
        class="w-full"
        :invalid="!!error"
        @value-change="optionSelected"
        :disabled="readOnly"
        filter
        :show-clear="showClear"
        filterMode="lenient"
    />
    <small v-if="error" class="text-red-500">{{ error }}</small>
</template>
