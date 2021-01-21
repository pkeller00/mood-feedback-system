<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Meeting
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    hello world
                    </div> -->
                    <jet-validation-errors class="mb-4" />

                    <form @submit.prevent="submit">
                        <div class="mt-4">
                            <jet-label for="name" value="Meeting Name" />
                            <jet-input id="name" type="text" class="mt-1 block w-full" v-model="meeting.name" required autofocus />
                        </div>

                        <div class="mt-4">
                            <jet-label for="meeting-date" value="Meeting Date" />
                            <jet-input id="meeting-date" type="date" class="mt-1 block w-full" placeholder="dd/mm/yyyy" pattern="[0-3][0-9]/[0-1][0-9]/[0-9]{4}" v-model="meeting.meeting_date" required />
                        </div>
                        <div class="mt-4">
                            <jet-label for="start-time" value="Start Time" />
                            <jet-input id="start-time" type="time" class="mt-1 block w-full" placeholder="hh:mm" v-model="meeting.start_time" required />
                        </div>
                        <div class="mt-4">
                            <jet-label for="end-time" value="End Time" />
                            <jet-input id="end-time" type="time" class="mt-1 block w-full" placeholder="hh:mm" v-model="meeting.end_time" required />
                        </div>

                        <!-- <div class="mt-4">
                            <jet-label for="password" value="Password" />
                            <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
                        </div>

                        <div class="block mt-4">
                            <label class="flex items-center">
                                <jet-checkbox name="remember" v-model="form.remember" />
                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>
                        </div> -->

                        <div class="flex items-center justify-end mt-4">
                            <!-- <inertia-link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                                Forgot your password?
                            </inertia-link> -->

                            <jet-button class="ml-4">
                            <!-- <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> -->
                                Create Meeting
                            </jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    // import FormSection from '@/Jetstream/FormSection'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            AppLayout,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors
        },

        // props: {

        // },

        data() {
            return {
                meeting: this.$inertia.form({
                    name: '',
                    meeting_date: '',
                    start_time: '',
                    end_time: '',
                })
            }
        },

        // methods: {
        //     submit() {
        //         this.form
        //             .transform(data => ({
        //                 ... data,
        //                 remember: this.form.remember ? 'on' : ''
        //             }))
        //             .post(this.route('login'), {
        //                 onFinish: () => this.form.reset('password'),
        //             })
        //     }
        // }
    }
</script>



