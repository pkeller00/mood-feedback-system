<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Event Details
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-start mt-4">
          <inertia-link :href="route('meetings.index')">
            <jet-button class="mr-4 sm:mr-0"> Back to view</jet-button>
          </inertia-link>
        </div>
        <!-- <div class="flex items-center justify-end mt-4">
          <inertia-link :href="route('/')">
            <jet-button class="mr-4 sm:mr-0"> Update Event </jet-button>
          </inertia-link>
        </div> -->
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          <!-- <jet-validation-errors class="mb-4" /> -->
          <!-- <div class="p-4 sm:px-20 bg-white border-b border-gray-200"> -->
          <div class="mt-2 text-2xl">
            {{ meeting.name }}
          </div>

          <div class="mt-6 text-gray-500">
            <p>
              Access code:
              <span class="raisin-black font-mono">{{
                meeting.meeting_reference
              }}</span>
            </p>
            <p class="raisin-black">
              {{ meeting.meeting_start }} to {{ meeting.meeting_end }}
            </p>
          </div>
        </div>
        <div class="flex items-center justify-end sm:space-x-4 mt-4">
          <inertia-link :href="route('meetings.edit', meeting)">
            <jet-button class="mr-4 sm:mr-0 bg-green-800 hover:bg-green-700 active:bg-green-900 focus:border-green-900">Edit Event</jet-button>
          </inertia-link>
            <!-- <jet-button class="mr-4 sm:mr-0 bg-red-800 hover:bg-red-700 active:bg-red-900 focus:border-red-900">Delete Event</jet-button> -->
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetButton from "@/Jetstream/Button";

export default {
  components: {
    AppLayout,
    JetButton,
  },

  props: {
    meeting: Object,
  },
};
</script>



