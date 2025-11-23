<template>
    <AppLayout title="Kategorie">
        <Head title="Kategorie" />
        <div class="p-6 space-y-4">
            <PageHeader
                title="Kategorie"
                new-btn-title="Přidat kategorii"
                new-btn-acl="catalog.categories.edit"
                @click:new="openCreate"
            />
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
                            v-if="$userCan('catalog.categories.edit')"
                            @click.stop="openEdit(node.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            class="p-button-text p-button-sm p-button-danger"
                            v-if="$userCan('catalog.categories.edit')"
                            @click.stop="confirmDelete(node.data)"
                        />
                    </template>
                </Tree>
            </div>
        </div>
        <CategoryForm
            v-model:visible="showForm"
            :model="editingModel"
        />
        <ConfirmDialog></ConfirmDialog>

    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/Sakai/AppLayout.vue';
import {onMounted, ref, watch} from 'vue'
import {Head, router} from "@inertiajs/vue3";
import {useToast} from "primevue/usetoast";
import CategoryForm from "@/Components/Category/CategoryForm.vue";
import {useConfirm} from "primevue";
import PageHeader from "@/Components/PageHeader.vue";
const props = defineProps({
    categories: Array,
})

const confirm = useConfirm()
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
const editingModel = ref(null)

function openCreate() {
    editingModel.value = null
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

function openEdit(category) {
    console.log('editing', category);
    editingModel.value = category
    showForm.value = true
}

function confirmDelete(model) {
    confirm.require({
        message: `Opravdu chcete smazat tuto kategorii?`,
        header: 'Potvrzení smazání',
        icon: 'pi pi-info-circle',
        rejectProps: {
            label: 'Zrušit',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Smazat',
            severity: 'danger'
        },
        accept: () => {
            router.delete(route('catalog.categories.destroy', model.id), {
                preserveState: true
            })
        },
    })
}
</script>
