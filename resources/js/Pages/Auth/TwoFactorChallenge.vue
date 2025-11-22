<script setup>
import { nextTick, ref } from 'vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayoutPublic from "@/Layouts/AppLayoutPublic.vue";
import Checkbox from "@/Components/Checkbox.vue";

const recovery = ref(false);

const form = useForm({
    code: '',
    recovery_code: '',
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
    recovery.value ^= true;

    await nextTick();

    if (recovery.value) {
        recoveryCodeInput.value.focus();
        form.code = '';
    } else {
        codeInput.value.focus();
        form.recovery_code = '';
    }
};

const submit = () => {
    form.post(route('two-factor.login'));
};
</script>

<template>
    <Head title="Two-factor Confirmation" />
    <AppLayoutPublic>
        <div class="flex mt-5 lg:mt-0 lg:items-center justify-center min-h-screen overflow-hidden">
            <div>
                <div class="text-center mb-8">
                    <img src="/img/logo.png" alt="Logo" class="mx-auto mb-4 h-20" />
                    <div class="text-surface-900 dark:text-surface-0 text-3xl font-medium mb-4">Intranet</div>
                </div>

                <div class="rounded-xl m-1 border border-gray-400 p-5 lg:p-16 bg-white drop-shadow-lg max-w-lg">

                    <div class="mb-4 text-sm">
                        <template v-if="! recovery">
                            Potvrďte přístup ke svému účtu zadáním ověřovacího kódu poskytnutého vaší autentizační aplikací.
                        </template>

                        <template v-else>
                            Potvrďte přístup ke svému účtu zadáním jednoho z vašich nouzových obnovovacích kódů.
                        </template>
                    </div>

                    <form @submit.prevent="submit">
                        <div v-if="! recovery">
                            <InputLabel for="code" value="Kód" />
                            <InputText
                                id="code"
                                ref="codeInput"
                                v-model="form.code"
                                type="text"
                                inputmode="numeric"
                                class="mt-1 block w-full"
                                autofocus
                                autocomplete="one-time-code"
                            />
                            <InputError class="mt-2" :message="form.errors.code" />
                        </div>

                        <div v-else>
                            <InputLabel for="recovery_code" value="Kód pro obnovení" />
                            <InputText
                                id="recovery_code"
                                ref="recoveryCodeInput"
                                v-model="form.recovery_code"
                                type="text"
                                class="mt-1 block w-full"
                                autocomplete="one-time-code"
                            />
                            <InputError class="mt-2" :message="form.errors.recovery_code" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="button" class="text-sm hover:text-gray-900 underline cursor-pointer" @click.prevent="toggleRecovery">
                                <template v-if="! recovery">
                                    Použít kód pro obnovení
                                </template>

                                <template v-else>
                                    Použít ověřovací kód
                                </template>
                            </button>

                            <Button label="Přihlásit se" class="ms-4" :class="{ 'opacity-25': form.processing }" type="submit" :disabled="form.processing"></Button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </AppLayoutPublic>
</template>
