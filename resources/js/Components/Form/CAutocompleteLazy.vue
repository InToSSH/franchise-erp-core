<script setup>

import {defineProps, ref, watch} from 'vue'

const props = defineProps({
    name: String,
    label: String,
    error: String,
    required: Boolean,
    route: String,
    resultType: {
        type: String,
        default: 'value'
    },
    placeholder: {
        type: String,
        default: ''
    },
    autofocus: {
        type: Boolean,
        default: false
    },
    optionLabel: {
        type: String,
        default: 'label'
    }
})

const model = defineModel()
const filteredItems = ref();
const loading = ref(false);

const search = (event) => {
    setTimeout(() => {
        if (event.query.trim().length) {
            loading.value = true;
            fetchItems(event.query);
        }
    }, 250);
};

const fetchItems = async (query, exactValue) => {
    console.log('Fetching items for query:', query);
    try {
        const response = await fetch(route(props.route , { query: query, exactValue: exactValue }));
        const data = await response.json();
        filteredItems.value = data.items;
        loading.value = false;
    } catch (error) {
        console.error('Error fetching autocomplete items:', error);
    }
};

watch(model, (newValue, oldValue) => {
    console.log('Model changed to:', newValue, 'from:', oldValue);
    if (newValue !== oldValue && (newValue !== null && newValue !== undefined && newValue.id !== undefined)) {
        fetchItems('', newValue.id);
    }
}, { immediate: true })


</script>

<template>
    <label class="block text-sm font-medium mb-1">{{ label }}</label>
    <AutoComplete
        v-model="model"
        :autofocus="autofocus"
        :suggestions="filteredItems"
        dropdown
        :loading="loading"
        @complete="search"
        :placeholder="placeholder"
        :option-label="optionLabel"
        class="w-full"
        force-selection
        :invalid="!!error"
    >
        <template #option="slotProps">
            <slot name="option" v-bind="slotProps"/>
        </template>
    </AutoComplete>
    <small v-if="error" class="text-red-500">{{ error }}</small>
</template>
