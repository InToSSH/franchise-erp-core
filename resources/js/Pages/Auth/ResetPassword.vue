<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from "@/Components/Checkbox.vue";
import AppLayoutPublic from "@/Layouts/AppLayoutPublic.vue";

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Obnovení hesla" />

    <AppLayoutPublic>
        <div class="flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
            <div>
                <div class="text-center mb-8">
                    <img src="/img/logo.png" alt="Logo" class="mx-auto mb-4 h-20" />
                    <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">Intranet</div>
                </div>

                <div class="rounded-xl border border-gray-400 p-16 bg-white drop-shadow-lg">
                    <form @submit.prevent="submit">
                        <div class="mb-6">
                            <label for="email" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">E-mail</label>
                            <InputText id="email" type="text" placeholder="E-mailová adresa" class="w-full md:w-[30rem] mb-2" v-model="form.email" />
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-surface-900 dark:text-surface-0 font-medium text-xl mb-2">Nové heslo</label>
                            <Password id="password" v-model="form.password" placeholder="Nové heslo" :toggleMask="true" class="mb-2" fluid :feedback="true"></Password>
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-surface-900 dark:text-surface-0 font-medium text-xl mb-2">Nové heslo (znovu)</label>
                            <Password id="password_confirmation" v-model="form.password_confirmation" placeholder="Nové heslo" :toggleMask="true" class="mb-2" fluid :feedback="false"></Password>
                            <InputError :message="form.errors.password_confirmation" />
                        </div>

                        <Button label="Nastavit nové heslo" class="w-full" as="router-link" @click.prevent="submit"></Button>
                    </form>
                </div>
            </div>

        </div>
    </AppLayoutPublic>
</template>

