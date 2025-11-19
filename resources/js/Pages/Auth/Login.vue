<script setup>
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import AppLayoutPublic from "@/Layouts/AppLayoutPublic.vue";
import InputError from "@/Components/InputError.vue";

defineProps({
    canResetPassword: Boolean,
    status: String
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />
    <AppLayoutPublic>
        <div class="flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
            <div>
                <div class="text-center mb-8">
                    <img src="/img/logo.png" alt="Logo" class="mx-auto mb-4 h-20" />
                    <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">Intranet</div>
                </div>

                <div class="rounded-xl border border-gray-400 p-16 bg-white drop-shadow-lg">
                    <div v-if="status" class="mb-4 font-medium text text-green dark:text-green-500 pb-4">
                        {{ status }}
                    </div>
                    <form @submit.prevent="submit">
                        <div class="mb-6">
                            <label for="email" class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2">E-mail</label>
                            <InputText id="email" type="text" placeholder="E-mailová adresa" class="w-full md:w-[30rem] mb-2" v-model="form.email" />
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-surface-900 dark:text-surface-0 font-medium text-xl mb-2">Heslo</label>
                            <Password id="password" v-model="form.password" placeholder="Heslo" :toggleMask="true" class="mb-2" fluid :feedback="false" @keyup.enter="submit"></Password>
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="flex items-center justify-between mt-2 mb-8 gap-8">
                            <div class="flex items-center">
                                <Checkbox v-model="form.remember" id="remember" binary class="mr-2"></Checkbox>
                                <label for="remember">Zapamatovat</label>
                            </div>
                            <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Zapomenuté heslo?
                            </Link>
                        </div>
                        <Button label="Přihlásit se" class="w-full" as="router-link" @click.prevent="submit"></Button>
                    </form>
                </div>
            </div>

        </div>
    </AppLayoutPublic>

</template>
