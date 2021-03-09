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
            <p
              class="raisin-black"
              v-if="isSameDay(meeting_start, meeting_end)"
            >
              {{ meeting_start | date }}
              to
              {{ meeting_end | sameday }}
            </p>
            <p class="raisin-black" v-else>
              {{ meeting_start | date }}
              to
              {{ meeting_end | date }}
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
          <div class="text-xl mt-4">
            {{ question.question }}
          </div>

          <line-chart
            v-if="question.question_type === 0 || question.question_type === 1"
            :chart-data="chartDatasComputed[i]"
            :options="chartoptions.mood"
          />
          <line-chart
            v-else-if="question.question_type === 2"
            :chart-data="chartDatasComputed[i]"
            :options="chartoptions.rating"
          />
          <pie-chart
            v-else-if="question.question_type === 3"
            :chart-data="chartDatasComputed[i]"
            :options="chartoptions.emoji"
          />

          <div class="flex items-center justify-end mt-4">
            <inertia-link
              method="get"
              as="button"
              :href="route('meetings.feedback', [meeting, i + 1])"
              v-if="
                question.question_type === 0 || question.question_type === 1
              "
            >
              <jet-button>Access Responses</jet-button>
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
import LineChart from "@/Charts/LineChart";
import PieChart from "@/Charts/PieChart";
import { createDateFilter } from "vue-date-fns";
import { isSameDay, parseISO } from "date-fns";

export default {
  components: {
    AppLayout,
    JetButton,
    LineChart,
    PieChart,
  },
  filters: {
    date: createDateFilter("EEEE do MMMM yyyy  HH:mm"),
    sameday: createDateFilter("HH:mm"),
  },
  props: {
    meeting: Object,
    questions: Array,
    event_started: Boolean,
  },

  data() {
    return {
      isSameDay,
      chartdatas: [],
      chartoptions: {
        emoji: {
          responsive: true,
          maintainAspectRatio: false,
          cutoutPercentage: 0,
        },
        mood: {
          scales: {
            xAxes: [
              {
                type: "time",
                time: {
                  minUnit: "minute",
                  round: true,
                  stepSize: 5,
                  displayFormats: {
                    minute: "HH:mm",
                  },
                },
              },
            ],
            yAxes: [
              {
                ticks: {
                  suggestedMin: -1,
                  suggestedMax: 1,
                },
                scaleLabel: {
                  display: true,
                  labelString: "Mood",
                  padding: 0,
                },
              },
            ],
          },
          responsive: true,
          maintainAspectRatio: false,
        },
        rating: {
          scales: {
            xAxes: [
              {
                type: "time",
                time: {
                  minUnit: "minute",
                  round: true,
                  stepSize: 5,
                  displayFormats: {
                    minute: "HH:mm",
                  },
                },
              },
            ],
            yAxes: [
              {
                ticks: {
                  suggestedMin: -1,
                  suggestedMax: 1,
                },
                scaleLabel: {
                  display: true,
                  labelString: "Rating",
                  padding: 0,
                },
              },
            ],
          },
          responsive: true,
          maintainAspectRatio: false,
        },
      },
      polling: null,
    };
  },

  computed: {
    chartDatasComputed: function () {
      return this.$data.chartdatas;
    },

    meeting_start: function () {
      return parseISO(this.meeting.meeting_start);
    },
    meeting_end: function () {
      return parseISO(this.meeting.meeting_end);
    },
  },

  created() {
    this.getChartResponse();
    // only poll data if the meeting is live at the time of loading page
    if (this.meeting_start <= Date.now() && Date.now() <= this.meeting_end) {
      this.pollData();
    }
  },

  methods: {
    pollData() {
      this.polling = setInterval(() => {
        this.getChartResponse();
      }, 30000);
    },
    getChartResponse() {
      axios
        .post(`/get-feedback/${this.meeting.meeting_reference}/chart`)
        .then((response) => {
          this.chartdatas = response.data;
        })
        .catch((e) => {
          console.log(e);
        });
    },
  },

  beforeDestroy() {
    clearInterval(this.polling);
  },
};
</script>
