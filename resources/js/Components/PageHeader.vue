<script setup>
defineProps({
    title: {
        type: String,
        default: 'Page Title'
    },
    newBtnTitle: {
        type: String,
        default: 'Create New'
    },
    newBtnAcl: {
        type: String,
        default: null
    },
    newBtnIcon: {
        type: String,
        default: 'pi pi-plus'
    },
})

const emit = defineEmits(['click:new'])

</script>

<template>
    <div class="flex justify-between lg:items-center flex-col lg:flex-row">
        <h1 class="text-3xl font-semibold">{{ title }}</h1>
        <Button :label="newBtnTitle" :icon="newBtnIcon" @click="() => emit('click:new')" v-if="newBtnAcl ? $userCan(newBtnAcl) : true"/>
    </div>
</template>

<style scoped lang="scss">

</style>
