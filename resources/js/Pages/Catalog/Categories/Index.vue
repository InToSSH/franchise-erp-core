<template>
    <AppLayout>
        <Head title="Kategorie" />
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-semibold">Kategorie</h1>
                <Button label="Přidat kategorii" icon="pi pi-plus" @click="openCreate" />
            </div>
            <div>
                <div class="flex flex-wrap gap-2 mb-6">
                    <Button type="button" icon="pi pi-plus" label="Rozbalit vše" @click="expandAll" />
                    <Button type="button" icon="pi pi-minus" label="Sbalit vše" @click="collapseAll" />
                </div>
                <Tree
                    v-model:value="categoriesDynamic"
                    v-model:expandedKeys="expandedKeys"
                    selection-mode="single"
                    class="w-full"
                    draggable-nodes
                    droppable-nodes
                    @node-drop="onNodeDrop"
                >
                    <template #empty>
                        <div class="p-4 text-gray-500">Žádné kategorie nebyly nalezeny.</div>
                    </template>
                    <template #default="{ node }">
                        {{ node.label }}
                        <Button
                            icon="pi pi-pencil"
                            class="ml-1 p-button-text p-button-sm"
                            @click.stop="openEdit(node.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            class="p-button-text p-button-sm p-button-danger"
                            @click.stop="$emit('confirm-delete', node.data)"
                        />
                    </template>
                </Tree>
            </div>
        </div>
        <ConfirmDialog></ConfirmDialog>

    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/Sakai/AppLayout.vue';
import {onMounted, ref, watch} from 'vue'
import {Head, router} from "@inertiajs/vue3";
import {useToast} from "primevue/usetoast";
const props = defineProps({
    categories: Array,
})

let categoriesDynamic = ref([]);
const expandedKeys = ref({});
// watch for prop changes and update local state
watch(() => props.categories, (newCategories) => {
    categoriesDynamic.value = newCategories
}, {immediate: true})

onMounted(() => {
    expandAll()
})

const toast = useToast();

const showForm = ref(false)
const editingSupplier = ref(null)

function openCreate() {
    editingSupplier.value = null
    showForm.value = true
}

const expandAll = () => {
    for (let category of categoriesDynamic.value) {
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

async function onNodeDrop(event) {
    const movedId = event.dragNode.key
    const parentNode = event.dropNode ? findParent(categoriesDynamic.value, movedId) : null

    const siblings = parentNode ? parentNode.children || [] : categoriesDynamic.value

    const dropIndex = siblings.findIndex(n => String(n.key) === String(movedId))
    const beforeNode = siblings[dropIndex + 1] || null
    const afterNode = siblings[dropIndex - 1] || null

    await axios.post('/api/catalog/categories/' + movedId + '/move', {
        parent_id: parentNode ? parentNode.key : null,
        before_id: beforeNode ? beforeNode.key : null,
        after_id: afterNode ? afterNode.key : null,
    }).then(() => {
        toast.add({severity: 'success', summary: 'Úspěch', detail: 'Pozice kategorie byla úspěšně aktualizována.', life: 3000});
    }).catch((error) => {
        toast.add({severity: 'error', summary: 'Chyba', detail: 'Nepodařilo se aktualizovat pozici kategorie.', life: 3000});
    }).finally(() => {
        router.reload({ only: ['categories'] });
    })
}

function findParent(tree, childKey) {
    for (const node of tree) {
        if (node.children && node.children.some(c => String(c.key) === String(childKey))) {
            return node
        }
        if (node.children) {
            const found = findParent(node.children, childKey)
            if (found) return found
        }
    }
    return null
}

function openEdit(supplier) {
    editingSupplier.value = supplier
    showForm.value = true
}
</script>
