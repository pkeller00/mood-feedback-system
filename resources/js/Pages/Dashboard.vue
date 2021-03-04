<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-end space-x-2">
          <inertia-link :href="route('meetings.create')">
            <jet-button class="mr-4 sm:mr-0"> Create Event </jet-button>
          </inertia-link>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg my-4 p-4">
          <h2 class="font text-2xl">Recently Attended Events</h2>
          <div
            class="bg-white overflow-hidden shadow-md rounded-lg my-2 p-4 border border-gray-200"
            v-for="meeting in attended_events"
            :key="meeting.meeting_reference"
          >
            <inertia-link :href="route('attendevents.create', meeting)">
              <div class="mt-1 text-xl">
                {{ meeting.name }}
              </div>

              <div
                class="mt-1 text-gray-600 text-sm sm:flex sm:flex-row sm:space-x-2"
              >
                <p>
                  Access code:
                  <span class="font-mono">{{ meeting.meeting_reference }}</span>
                </p>
                <p
                  v-if="
                    isSameDay(
                      parseISO(meeting.meeting_start),
                      parseISO(meeting.meeting_end)
                    )
                  "
                >
                  {{ parseISO(meeting.meeting_start) | date }}
                  to
                  {{ parseISO(meeting.meeting_end) | sameday }}
                </p>
                <p v-else>
                  {{ parseISO(meeting.meeting_start) | date }}
                  to
                  {{ parseISO(meeting.meeting_end) | date }}
                </p>
              </div>
            </inertia-link>
          </div>

          <template v-if="!attended_events.length">
            <p class="mt-1 text-gray-600">No recently attended events</p>
          </template>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg my-4 p-4">
          <h2 class="font text-2xl">Currently Hosting Events</h2>
          <div
            class="bg-white overflow-hidden shadow-md rounded-lg my-2 p-4 border border-gray-200"
            v-for="meeting in meetings_current"
            :key="meeting.meeting_reference"
          >
            <inertia-link :href="route('meetings.show', meeting)">
              <div class="mt-1 text-xl">
                {{ meeting.name }}
              </div>

              <div
                class="mt-1 text-gray-600 text-sm sm:flex sm:flex-row sm:space-x-2"
              >
                <p>
                  Access code:
                  <span class="font-mono">{{ meeting.meeting_reference }}</span>
                </p>
                <p
                  v-if="
                    isSameDay(
                      parseISO(meeting.meeting_start),
                      parseISO(meeting.meeting_end)
                    )
                  "
                >
                  {{ parseISO(meeting.meeting_start) | date }}
                  to
                  {{ parseISO(meeting.meeting_end) | sameday }}
                </p>
                <p v-else>
                  {{ parseISO(meeting.meeting_start) | date }}
                  to
                  {{ parseISO(meeting.meeting_end) | date }}
                </p>
              </div>
            </inertia-link>
          </div>

          <template v-if="!meetings_current.length">
            <p class="mt-1 text-gray-600">
              You aren't hosting any events currently
            </p>
          </template>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg my-4 p-4">
          <h2 class="font text-2xl">Upcoming Events</h2>
          <div
            class="bg-white overflow-hidden shadow-md rounded-lg my-2 p-4 border border-gray-200"
            v-for="meeting in meetings_future"
            :key="meeting.meeting_reference"
          >
            <inertia-link :href="route('meetings.show', meeting)">
              <div class="mt-1 text-xl">
                {{ meeting.name }}
              </div>

              <div
                class="mt-1 text-gray-600 text-sm sm:flex sm:flex-row sm:space-x-2"
              >
                <p>
                  Access code:
                  <span class="font-mono">{{ meeting.meeting_reference }}</span>
                </p>
                <p
                  v-if="
                    isSameDay(
                      parseISO(meeting.meeting_start),
                      parseISO(meeting.meeting_end)
                    )
                  "
                >
                  {{ parseISO(meeting.meeting_start) | date }}
                  to
                  {{ parseISO(meeting.meeting_end) | sameday }}
                </p>
                <p v-else>
                  {{ parseISO(meeting.meeting_start) | date }}
                  to
                  {{ parseISO(meeting.meeting_end) | date }}
                </p>
              </div>
            </inertia-link>
          </div>
          <template v-if="!meetings_future.length">
            <p class="mt-1 text-gray-600">No upcoming events to host</p>
          </template>
          <div class="flex items-center justify-center mt-4">
            <inertia-link :href="route('meetings.index')">
              <jet-button class="mr-4 sm:mr-0">View All Events</jet-button>
            </inertia-link>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg my-4 p-4">
          <h2 class="font text-2xl">Past Events</h2>
          <div
            class="bg-white overflow-hidden shadow-md rounded-lg my-2 p-4 border border-gray-200"
            v-for="meeting in meetings_past"
            :key="meeting.meeting_reference"
          >
            <inertia-link :href="route('meetings.show', meeting)">
              <div class="mt-1 text-xl">
                {{ meeting.name }}
              </div>

              <div
                class="mt-1 text-gray-600 text-sm sm:flex sm:flex-row sm:space-x-2"
              >
                <p>
                  Access code:
                  <span class="font-mono">{{ meeting.meeting_reference }}</span>
                </p>
                <p
                  v-if="
                    isSameDay(
                      parseISO(meeting.meeting_start),
                      parseISO(meeting.meeting_end)
                    )
                  "
                >
                  {{ parseISO(meeting.meeting_start) | date }}
                  to
                  {{ parseISO(meeting.meeting_end) | sameday }}
                </p>
                <p v-else>
                  {{ parseISO(meeting.meeting_start) | date }}
                  to
                  {{ parseISO(meeting.meeting_end) | date }}
                </p>
              </div>
            </inertia-link>
          </div>
          <template v-if="!meetings_past.length">
            <p class="mt-1 text-gray-600">You have not hosted any events yet</p>
          </template>
          <div class="flex items-center justify-center mt-4">
            <inertia-link :href="route('meetings.index')">
              <jet-button class="mr-4 sm:mr-0">View All Events</jet-button>
            </inertia-link>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetButton from "@/Jetstream/Button";
import { createDateFilter } from "vue-date-fns";
import { isSameDay, parseISO } from "date-fns";

export default {
  components: {
    AppLayout,
    JetButton,
  },

  filters: {
    date: createDateFilter("EEEE do MMMM yyyy  HH:mm"),
    sameday: createDateFilter("HH:mm"),
  },

  props: {
    meetings_current: Array,
    meetings_past: Array,
    meetings_future: Array,
    attended_events: Array,
  },

  data() {
    return {
      isSameDay,
      parseISO,
    };
  },
};
</script>
