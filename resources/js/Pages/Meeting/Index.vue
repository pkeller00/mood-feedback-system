<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        My events
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-end mt-4">
          <inertia-link :href="route('meetings.create')">
            <jet-button class="mr-4 sm:mr-0"> Create Event </jet-button>
          </inertia-link>
        </div>
        <div
          class="bg-white overflow-hidden shadow-xl sm:rounded-lg my-4"
          v-for="meeting in meetings"
          :key="meeting.id"
        >
        <inertia-link :href="route('meetings.show', meeting.id)">
          <div class="p-4 sm:px-20 bg-white border-b border-gray-200">
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
        </inertia-link>
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
    meetings: Array,
  },
};
</script>



