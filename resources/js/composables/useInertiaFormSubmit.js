import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

export function useInertiaFormSubmit(initialData = {}, routes = {}) {
    const form = ref({ ...initialData })
    const loading = ref(false)
    const errors = ref({})

    function submit(isEdit = false) {
        loading.value = true
        errors.value = {}

        const method = isEdit ? 'put' : 'post'
        const routeName = isEdit ? routes.update : routes.store
        const url = isEdit ? route(routeName, form.value.id) : route(routeName)

        router[method](url, form.value, {
            preserveScroll: true,
            onError: e => (errors.value = e),
            onFinish: () => (loading.value = false),
        })
    }

    return { form, loading, errors, submit }
}
