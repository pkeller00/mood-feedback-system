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
            <jet-button class="ml-4 sm:ml-0">Back to your events</jet-button>
          </inertia-link>
        </div>
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
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
            <jet-button
              class="mr-4 sm:mr-0 bg-green-800 hover:bg-green-700 active:bg-green-900 focus:border-green-900"
              v-if="event_started"
              >Edit Event</jet-button
            >
          </inertia-link>
          <inertia-link
            method="delete"
            as="button"
            :href="route('meetings.destroy', meeting)"
          >
            <jet-button
              class="mr-4 sm:mr-0 bg-red-800 hover:bg-red-700 active:bg-red-900 focus:border-red-900"
              >Delete Event</jet-button
            >
          </inertia-link>
        </div>

        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          <div class="mt-2 text-2xl">Feedback Analysis</div>
        </div>

        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
          v-for="question in questions"
          :key="question.id"
        >
          <div class="mt-4">
            {{ question.question }}
          </div>

            <!-- probably some query based on the type of question to determine which graph to show -->

          <line-chart :chartdata="chartdatas" :options="chartoptions" />

          <p>Graph goes here</p>
        </div>
        <pre>{{ $props }}</pre>
        <pre>{{ $data }}</pre>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetButton from "@/Jetstream/Button";
import LineChart from "@/Charts/LineChart";

export default {
  components: {
    AppLayout,
    JetButton,
    LineChart,
  },

  props: {
    meeting: Object,
    questions: Array,
    event_started: Boolean,
  },

  data() {
    return {
      chartdatas: {
        labels: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
        ],
        datasets: [
          {
            label: "Data One",
            backgroundColor: "#f87900",
            data: [40, 39, 10, 40, 39, 80, 40],
          },
        ],
      },
      chartoptions: {
        responsive: true,
        maintainAspectRatio: false,
      },
    };
  },
};
</script>
