<template>
    <Tree
        v-model:value="categories"
        selection-mode="single"
        class="w-full"
        v-model:selection-keys="selectionKeys"
        v-model:expandedKeys="expandedKeys"
    >
        <template #empty>
            <div class="p-4 text-gray-500">Žádné kategorie nebyly nalezeny.</div>
        </template>
    </Tree>
</template>

<script setup>
import {CategoryService} from "@/service/DocumentCategoryService.js";
import {onMounted, ref, watch, watchEffect} from "vue";

let props = defineProps({
    expanded: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['selected']);
const categories = ref([]);
const expandedKeys = ref({});
let selectionKeys = ref(null);


onMounted(() => {
    CategoryService.getCategoryTree().then(response => {
        categories.value = response.data;
        if (props.expanded) {
            expandAll();
        }
    });
});

const expandAll = () => {
    for (let category of categories.value) {
        expandNode(category);
    }

    expandedKeys.value = { ...expandedKeys.value };
};

const collapseAll = () => {
    expandedKeys.value = {};
};

const expandNode = (node) => {
    if (node.children && node.children.length) {
        expandedKeys.value[node.key] = true;

        for (let child of node.children) {
            expandNode(child);
        }
    }
};

watch (selectionKeys, (newSelection) => {
    if (newSelection) {
        const selectedKey = Object.keys(newSelection)[0];
        emit('selected', selectedKey ?? null);
    }
});

watch(() => props.expanded, (newVal) => {
    if (newVal) {
        expandAll();
    } else {
        collapseAll()
    }
});
</script>
