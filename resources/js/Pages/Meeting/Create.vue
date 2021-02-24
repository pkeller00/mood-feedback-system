<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create Meeting
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          <!-- <jet-validation-errors class="mb-4" /> -->

          <form @submit.prevent="submit">
            <div class="mt-4">
              <jet-label for="name" value="Meeting Name" />
              <jet-input
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="meeting.name"
                required
                autofocus
              />
              <div v-if="errors.name" class="mt-3 text-sm text-red-600">
                {{ errors.name }}
              </div>
            </div>

            <div class="mt-4">
              <jet-label for="meeting-start" value="Meeting Start" />
              <jet-input
                id="meeting-start"
                type="datetime-local"
                class="mt-1 block w-full"
                placeholder="dd/mm/yyyy 00:00"
                pattern="[0-3][0-9]/[0-1][0-9]/[0-9]{4}"
                v-model="meeting.meeting_start"
                required
              />
              <div
                v-if="errors.meeting_start"
                class="mt-3 text-sm text-red-600"
              >
                {{ errors.meeting_start }}
              </div>
            </div>

            <div class="mt-4">
              <jet-label for="meeting-end" value="Meeting End" />
              <jet-input
                id="meeting-end"
                type="datetime-local"
                class="mt-1 block w-full"
                placeholder="dd/mm/yyyy 00:00"
                pattern="[0-3][0-9]/[0-1][0-9]/[0-9]{4}"
                v-model="meeting.meeting_end"
                required
              />
              <div v-if="errors.meeting_end" class="mt-3 text-sm text-red-600">
                {{ errors.meeting_end }}
              </div>
            </div>

            <div class="flex items-center justify-center mt-4">
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
import AppLayout from "@/Layouts/AppLayout";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

export default {
  components: {
    AppLayout,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors,
  },

  props: {
    errors: Object,
  },

  data() {
    return {
      meeting: this.$inertia.form({
        name: "",
        meeting_start: "",
        meeting_end: "",
      }),
    };
  },

  methods: {
    submit() {
      this.meeting.post(this.route("meetings.store"));
      // .transform(data => ({
      //     ... data,
      //     remember: this.form.remember ? 'on' : ''
      // }))
      // .post(this.route('meetings.store'), {
      //     onFinish: () => this.form.reset('password'),
      // })
    },
  },
};
</script>



