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
    },
    deleteAcl: {
        type: String,
        default: null
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
        <div class="flex items-center gap-2 lg:max-w-sm">
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

                    <div class="flex justify-end">
                        <slot name="actionsBefore" :data="data">
                            <!-- Default action buttons if no slot provided -->
                        </slot>
                        <Button
                            :icon="data.is_editable !== false ? 'pi pi-pencil' : 'pi pi-eye'"
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
                            v-if="showDeleteAction && (!deleteAcl || $userCan(deleteAcl))"
                            @click="confirmDelete(data)"
                        />
                        <slot name="actionsAfter" :data="data">
                            <!-- Additional actions can be added here -->
                        </slot>
                    </div>

                </template>
            </Column>
        </DataTable>
        <DataView
            :value="resource.data"
            data-key="id"
            paginator
            :rowsPerPageOptions="[5, 10, 20, 50]"
            :rows="resource.meta.per_page"
            :totalRecords="resource.meta.total"
            :first="(resource.meta.current_page - 1) * resource.meta.per_page"
            :lazy="true"
            @page="onPage"
        >
            <template #list="slotProps">
                <div class="flex flex-col">
                    <div v-for="(item, index) in slotProps.items" :key="index">
                        <div class="flex flex-col sm:flex-row sm:items-center p-6 gap-4"
                             :class="{ 'border-t border-surface-200 dark:border-surface-700': index !== 0 }">
                            <div class="md:w-40 relative">
                                <img class="block xl:block mx-auto rounded w-full"
                                     :src="`https://primefaces.org/cdn/primevue/images/product/${item.image}`"
                                     :alt="item.name"/>
                            </div>
                            <div class="flex flex-col md:flex-row justify-between md:items-center flex-1 gap-6">
                                <div class="flex flex-row md:flex-col justify-between items-start gap-2">
                                    <div>
                                        <span class="font-medium text-surface-500 dark:text-surface-400 text-sm">{{
                                                item.category.name
                                            }}</span>
                                        <div class="text-lg font-medium mt-2">{{ item.name }}</div>
                                    </div>
                                    <div class="bg-surface-100 p-1" style="border-radius: 30px">
                                        <div class="bg-surface-0 flex items-center gap-2 justify-center py-1 px-2"
                                             style="border-radius: 30px; box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04), 0px 1px 2px 0px rgba(0, 0, 0, 0.06)">
                                            <span class="text-surface-900 font-medium text-sm">2</span>
                                            <i class="pi pi-star-fill text-yellow-500"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col md:items-end gap-8">
                                    <span class="text-xl font-semibold">$22</span>
                                    <div class="flex flex-row-reverse md:flex-row gap-2">
                                        <Button icon="pi pi-heart" variant="outlined"></Button>
                                        <Button icon="pi pi-shopping-cart" label="Buy Now"
                                                class="flex-auto md:flex-initial whitespace-nowrap"></Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </DataView>
    </div>

</template>
