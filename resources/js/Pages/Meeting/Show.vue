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
          v-for="(question, i) in questions"
          :key="question.id"
        >
          <div class="mt-4">
            {{ question.question }}
          </div>

          <!-- probably some query based on the type of question to determine which graph to show -->

          <p>Graph goes here</p>
          <line-chart
            :chart-data="chartDatasComputed[i]"
            :options="chartoptions"
          />
        </div>
        data response
        <div>{{ dataResponseComputed }}</div>
        chart response
        <div>{{ chartResponseComputed }}</div>
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
      chartdatas: null,

      chartoptions: {
        scales: {
          xAxes: [
            {
              type: "time",
              //   time: {
              //     unit: "minute",
              //   },
            },
          ],
        },
        responsive: true,
        maintainAspectRatio: false,
      },
      chart_response: null,
      data_response: null,
      //   errors: null,
      polling: null,
      check_time: null,
    };
  },

  computed: {
    chartResponseComputed: function () {
      return this.$data.chart_response;
    },
    dataResponseComputed: function () {
      return this.$data.data_response;
    },
    chartDatasComputed: function () {
      return this.$data.chartdatas;
    },
  },

  created() {
    this.getDataResponse();
    this.getChartResponse();

    // only poll data if the meeting is live at the time of loading page
    // should really have a poll to check the time every so often to call and clear the instance
    if (
      new Date(this.meeting.meeting_start) <= new Date(Date.now()) &&
      new Date(Date.now()) <= new Date(this.meeting.meeting_end)
    ) {
      this.pollData();
    }
  },

  methods: {
    pollData() {
      this.polling = setInterval(() => {
        this.getDataResponse();
        this.getChartResponse();
      }, 10000);
    },
    getDataResponse() {
      axios
        .post(`/get-feedback/${this.meeting.meeting_reference}/data`)
        .then((response) => {
          // JSON responses are automatically parsed.
          console.log(response);
          this.data_response = response.data;
        })
        .catch((e) => {
          console.log(e);
          //   this.errors.push(e);
        });
    },
    getChartResponse() {
      axios
        .post(`/get-feedback/${this.meeting.meeting_reference}/chart`)
        .then((response) => {
          // JSON responses are automatically parsed.
          console.log(response);
          this.chart_response = response.data;
          this.chartdatas = response.data;
        })
        .catch((e) => {
          console.log(e);
          //   this.errors.push(e);
        });
    },
  },

  beforeDestroy() {
    clearInterval(this.polling);
  },
};
</script>
