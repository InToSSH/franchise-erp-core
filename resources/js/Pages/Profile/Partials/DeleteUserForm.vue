<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route('current-user.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection>
        <template #title>
            Smazat účet
        </template>

        <template #description>
            Trvale smazat váš účet.
        </template>

        <template #content>
            <div class="max-w-xl text-sm">
                Jakmile bude váš účet smazán, všechny jeho zdroje a data budou trvale odstraněny. Před smazáním účtu si prosím stáhněte všechna data nebo informace, které si přejete zachovat.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmUserDeletion">
                    Smazat účet
                </DangerButton>
            </div>

            <!-- Delete Account Confirmation Modal -->
            <DialogModal :show="confirmingUserDeletion" @close="closeModal">
                <template #title>
                    Smazat účet
                </template>

                <template #content>
                    Opravdu chcete smazat svůj účet? Jakmile bude váš účet smazán, všechny jeho zdroje a data budou trvale odstraněny. Zadejte prosím své heslo, abyste potvrdili, že chcete svůj účet trvale smazat.

                    <div class="mt-4">
                        <InputText
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-3/4"
                            placeholder="Heslo"
                            autocomplete="current-password"
                            @keyup.enter="deleteUser"
                        />

                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal">
                        Zrušit
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Smazat účet
                    </DangerButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>
