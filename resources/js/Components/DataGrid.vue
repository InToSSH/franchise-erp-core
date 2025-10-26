<script setup>
import {useConfirm} from "primevue";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";

const search = ref('')
const loading = ref(false)
const confirm = useConfirm()

const props = defineProps({
    resource: Object,
    routePrefix: String,
    showEditAction: {
        type: Boolean,
        default: true
    },
    showDeleteAction: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['editAction'])

const onSearch = () => {
    loading.value = true
    router.get(
        route(props.routePrefix + '.index'),
        {searchValue: search.value},
        {
            preserveState: true,
            replace: true,
            onFinish: () => (loading.value = false),
        }
    )
}

const clearSearch = () => {
    search.value = '';
    onSearch()
}

function onPage(event) {
    console.log(event)
    router.get(
        route(props.routePrefix + '.index'),
        {
            page: event.page + 1,
            searchValue: search.value,
            sortField: event.sortField,
            sortOrder: event.sortOrder,
            perPage: event.rows
        },
        { preserveState: true, replace: true }
    )
}

function sortTable(event) {
    const sortField = event.sortField

    router.get(
        route(props.routePrefix + '.index'),
        {
            sortField: sortField,
            sortOrder: event.sortOrder,
            searchValue: search.value,
        },
        { preserveState: true, replace: true }
    )
}

function confirmDelete(model) {
    confirm.require({
        message: `Opravdu chcete smazat tento záznam?`,
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
            router.delete(route(props.routePrefix + '.destroy', model.id), {
                preserveState: true
            })
        },
    })
}
</script>

<template>
    <div class="space-y-4">
        <!-- Search -->
        <div class="flex items-center gap-2 max-w-sm">
            <IconField>
                <InputIcon>
                    <i class="pi pi-search" />
                </InputIcon>
                <InputText
                    v-model="search"
                    placeholder="Vyhledat..."
                    @keyup.enter="onSearch"
                    class="w-full"
                />
                <InputIcon>
                    <i class="pi pi-times cursor-pointer" v-if="search" @click="clearSearch" />
                </InputIcon>
            </IconField>
            <Button label="Hledat" icon="pi pi-search" @click="onSearch" :loading="loading" />
        </div>

        <!-- DataTable -->
        <DataTable
            :value="resource.data"
            dataKey="id"
            paginator
            :rowsPerPageOptions="[5, 10, 20, 50]"
            :rows="resource.meta.per_page"
            :totalRecords="resource.meta.total"
            :first="(resource.meta.current_page - 1) * resource.meta.per_page"
            :lazy="true"
            @page="onPage"
            @sort="sortTable"
            :loading="loading"
            tableStyle="min-width: 60rem"
            stripedRows
            class="shadow rounded-lg"
        >
            <slot />

            <Column header="Akce" bodyClass="text-center">
                <template #body="{ data }">
                    <div class="flex gap-2 justify-center">
                        <Button
                            icon="pi pi-pencil"
                            severity="info"
                            rounded
                            text
                            @click="emit('editAction', data)"
                        />
                        <!-- Create delete button with confirmation dialog -->
                        <Button
                            icon="pi pi-trash"
                            severity="danger"
                            rounded
                            text
                            @click="confirmDelete(data)"
                        />
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>

</template>
