<script setup>
import TimesIcon from '@primevue/icons/times';
import { defineProps } from 'vue'

const props = defineProps({
    name: String,
    label: String,
    error: String,
    accept: {
        type: String,
        default: 'image/*'
    },
    maxFileSize: {
        type: Number,
        default: 10000000
    },
    placeholder: {
        type: String,
        default: 'Sem přetáhněte soubor'
    }

})

const model = defineModel()

function uploadFile(event) {
    const file = event.files[0];
    console.log('file uploaded:', event);

    if (file) {
        model.value = file;
    }
}

</script>

<template>
    <label class="block text-sm font-medium mb-1">{{ label }}</label>
    <FileUpload
        :name="name"
        :accept="accept"
        :maxFileSize="maxFileSize"
        custom-upload
        auto
        @select="uploadFile"
        cancel-label="Zrušit"
        choose-label="Vybrat soubor"
        :show-upload-button="false"
    >
        <template #empty>
            <span>{{ placeholder }}</span>
        </template>
    </FileUpload>
    <small v-if="error" class="text-red-500">{{ error }}</small>
</template>
