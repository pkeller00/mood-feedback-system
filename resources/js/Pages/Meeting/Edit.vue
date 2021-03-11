<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Event
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          <form @submit.prevent="submit" :key="meeting.id">
            <div class="mt-4">
              <jet-label for="name" value="Event Name" />
              <jet-input
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="return_meeting.name"
                :value="meeting.name"
                required
                autofocus
              />
              <div v-if="errors.name" class="mt-3 text-sm text-red-600">
                {{ errors.name }}
              </div>
            </div>

            <div class="mt-4">
              <jet-label for="meeting-start" value="Event Start" />
              <jet-input
                id="meeting-start"
                type="datetime-local"
                class="mt-1 block w-full"
                pattern="[0-3][0-9]/[0-1][0-9]/[0-9]{4}"
                v-model="return_meeting.meeting_start"
                :value="meeting.meeting_start"
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
              <jet-label for="meeting-end" value="Event End" />
              <jet-input
                id="meeting-end"
                type="datetime-local"
                class="mt-1 block w-full"
                pattern="[0-3][0-9]/[0-1][0-9]/[0-9]{4}"
                v-model="return_meeting.meeting_end"
                :value="meeting.meeting_end"
                required
              />
              <div v-if="errors.meeting_end" class="mt-3 text-sm text-red-600">
                {{ errors.meeting_end }}
              </div>
            </div>

            <div class="flex items-center justify-center mt-4">
              <jet-button class="ml-4"> Update </jet-button>
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

export default {
  components: {
    AppLayout,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
  },

  props: {
    meeting: Object,
    errors: Object,
  },

  data() {
    return {
      return_meeting: this.$inertia.form({
        meeting_reference: this.meeting.meeting_reference,
        name: this.meeting.name,
        meeting_start: this.meeting.meeting_start,
        meeting_end: this.meeting.meeting_end,
      }),
    };
  },

  methods: {
    submit() {
      this.$inertia.put(
        `/events/${this.meeting.meeting_reference}`,
        this.return_meeting
      );
    },
  },
};
</script>



