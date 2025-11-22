<script setup>
import { ref, reactive, nextTick } from 'vue';
import DialogModal from './DialogModal.vue';
import InputError from './InputError.vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import TextInput from './TextInput.vue';

const emit = defineEmits(['confirmed']);

defineProps({
    title: {
        type: String,
        default: 'Potvrďte své heslo',
    },
    content: {
        type: String,
        default: 'Toto je citlivá akce, prosím, potvrďte své heslo před pokračováním.',
    },
    button: {
        type: String,
        default: 'Potvrdit',
    },
});

const confirmingPassword = ref(false);

const form = reactive({
    password: '',
    error: '',
    processing: false,
});

const passwordInput = ref(null);

const startConfirmingPassword = () => {
    axios.get(route('password.confirmation')).then(response => {
        if (response.data.confirmed) {
            emit('confirmed');
        } else {
            confirmingPassword.value = true;

            setTimeout(() => passwordInput.value.focus(), 250);
        }
    });
};

const confirmPassword = () => {
    form.processing = true;

    axios.post(route('password.confirm'), {
        password: form.password,
    }).then(() => {
        form.processing = false;

        closeModal();
        nextTick().then(() => emit('confirmed'));

    }).catch(error => {
        form.processing = false;
        form.error = error.response.data.errors.password[0];
        passwordInput.value.focus();
    });
};

const closeModal = () => {
    confirmingPassword.value = false;
    form.password = '';
    form.error = '';
};
</script>

<template>
    <span>
        <span @click="startConfirmingPassword">
            <slot />
        </span>

        <DialogModal :show="confirmingPassword" @close="closeModal">
            <template #title>
                {{ title }}
            </template>

            <template #content>
                {{ content }}

                <div class="mt-4">
                    <InputText
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Heslo"
                        autocomplete="current-password"
                        @keyup.enter="confirmPassword"
                    />
                    <InputError :message="form.error" class="mt-2" />
                </div>
            </template>

            <template #footer>
                <Button label="Zrušit" severity="secondary" @click="closeModal" class="mr-3"></Button>
                <Button :label="button" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.prevent="confirmPassword"></Button>
            </template>
        </DialogModal>
    </span>
</template>
