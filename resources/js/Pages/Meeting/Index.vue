<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Index
            </h2>
        </template>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                lemon squeezy
                {{ name }}
                {{ meeting_reference }}
            </div>
        </div>
    </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'


    // import JetButton from '@/Jetstream/Button'
    // import JetInput from '@/Jetstream/Input'
    // import JetCheckbox from '@/Jetstream/Checkbox'
    // import JetLabel from '@/Jetstream/Label'
    // import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            AppLayout,

            // JetButton,
            // JetInput,
            // JetCheckbox,
            // JetLabel,
            // JetValidationErrors
        },

        props: {
            name: String,
            meeting_reference: String
        },
    }
</script>



