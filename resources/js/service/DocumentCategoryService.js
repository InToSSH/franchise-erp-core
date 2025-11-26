export const CategoryService = {
    async getCategoryTree() {
        return axios.get(route('api.documents.categories.tree'));
    }
}
