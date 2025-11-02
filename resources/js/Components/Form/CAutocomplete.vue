<script setup>

import {defineProps, ref, watch} from 'vue'

const props = defineProps({
    name: String,
    label: String,
    error: String,
    required: Boolean,
    autofocus: {
        type: Boolean,
        default: false
    },
    items: {
        type: Array,
        default: () => []
    },
})

const model = defineModel()
const filteredItems = ref();
const selectedItem = ref();

watch(model, (newValue) => {
    selectedItem.value = props.items.find(i => i.value === newValue) ?? null
}, { immediate: true })

const search = (event) => {
    setTimeout(() => {
        if (!event.query.trim().length) {
            filteredItems.value = [...props.items];
        } else {
            filteredItems.value = props.items.filter((item) => {
                return item.name.toLowerCase().startsWith(event.query.toLowerCase());
            });
        }
    }, 250);
};

const optionSelected = (e) => {
    model.value = e.value ? e.value.value : null;
};

</script>

<template>
    <label class="block text-sm font-medium mb-1">{{ label }}</label>
    <AutoComplete
        v-model="selectedItem"
        :autofocus="autofocus"
        :suggestions="filteredItems"
        dropdown
        @complete="search"
        @option-select="optionSelected"
        option-label="name"
        class="w-full"
        force-selection
        :invalid="!!error"
    />
    <small v-if="error" class="text-red-500">{{ error }}</small>
</template>
