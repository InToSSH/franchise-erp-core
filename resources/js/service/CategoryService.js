export const CategoryService = {
    async getCategoryTree() {
        return axios.get(route('api.catalog.categories.tree'));
    }
}
