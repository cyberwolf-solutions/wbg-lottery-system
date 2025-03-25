<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const affiliateLink = ref("https://example.com/affiliate-link");

const copyToClipboard = () => {
    navigator.clipboard.writeText(affiliateLink.value).then(() => {
        alert('Affiliate link copied to clipboard!');
    }).catch(err => {
        alert('Failed to copy link: ', err);
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Your Affiliate Link
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Share this link to earn commissions on referred sales.
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <div>
                <InputLabel for="affiliate_link" value="Affiliate Link" />

                <TextInput
                    id="affiliate_link"
                    v-model="affiliateLink"
                    type="text"
                    class="mt-1 block w-full"
                    readonly
                />

                <InputError
                    :message="form.errors.affiliate_link"
                    class="mt-2"
                />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton @click="copyToClipboard">Copy Link</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                        Link copied successfully!
                    </p>
                </Transition>
            </div>
        </div>
    </section>
</template>
