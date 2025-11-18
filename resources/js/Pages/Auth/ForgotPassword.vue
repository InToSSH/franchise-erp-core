<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayoutPublic from "@/Layouts/AppLayoutPublic.vue";
import Checkbox from "@/Components/Checkbox.vue";

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Zapomenuté heslo" />

    <AppLayoutPublic>

        <div class="flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
            <div>
                <div class="text-center mb-8">
                    <img src="/img/logo.png" alt="Logo" class="mx-auto mb-4 h-20" />
                    <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">Intranet</div>
                </div>

                <div class="rounded-xl border border-gray-400 p-16 bg-white drop-shadow-lg max-w-2xl">
                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                        Zapomněli jste heslo? Žádný problém. Stačí nám sdělit vaši e‑mailovou adresu a my vám pošleme odkaz pro obnovení hesla, který vám umožní zvolit si nové.
                    </div>

                    <div v-if="status" class="mb-4 font-medium text text-green dark:text-green-500 pb-4">
                        {{ status }}
                    </div>
                    <form @submit.prevent="submit">
                        <div class="mb-6">
                            <label for="email" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">E-mail</label>
                            <InputText id="email" type="text" placeholder="E-mailová adresa" class="w-full mb-2" v-model="form.email" autocomplete="username"/>
                            <InputError :message="form.errors.email" />
                        </div>

                        <Button label="Odeslat odkaz pro obnovení hesla" :class="{ 'opacity-25': form.processing, 'w-full': true }" :disabled="form.processing" as="router-link" @click.prevent="submit"></Button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayoutPublic>
</template>
