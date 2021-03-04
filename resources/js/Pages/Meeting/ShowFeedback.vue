<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Feedback Responses
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-start mt-4">
          <inertia-link :href="route('meetings.show', meeting)">
            <jet-button class="ml-4 sm:ml-0">Back to event</jet-button>
          </inertia-link>
        </div>
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          <div class="text-2xl">
            {{ question.question }}
          </div>
        </div>
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
          v-for="responseObj in dataResponseComputed"
          :key="responseObj.response.id"
        >
          <div class="text-xl mt-4">
            {{ JSON.parse(responseObj.response.response)["value"] }}
          </div>
          <div
            class="flex flex-col sm:flex-row sm:space-x-4 sm:justify-end text-gray-600 text-sm"
          >
            <div>
              Sentiment:
              <span
                class="font-bold font-mono"
                :class="{
                  'text-red-600': responseObj.response.score < -0.3,
                  'text-yellow-600':
                    0.3 >= responseObj.response.score &&
                    responseObj.response.score >= -0.3,
                  'text-green-600': responseObj.response.score > 0.3,
                }"
                >{{ responseObj.response.score }}</span
              >
            </div>
          </div>
          <div
            class="flex flex-col sm:flex-row sm:space-x-4 sm:justify-end text-gray-600 text-sm"
          >
            <div v-if="responseObj.response_info[0].name">
              {{ responseObj.response_info[0].name }}
            </div>
            <div v-if="responseObj.response_info[0].email">
              {{ responseObj.response_info[0].email }}
            </div>
            <div>
              {{
                  parseISO(responseObj.response.created_at) | date
              }}
            </div>
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
import { parseISO } from "date-fns";

export default {
  components: {
    AppLayout,
    JetButton,
  },

  filters: {
    date: createDateFilter("EEEE do MMMM yyyy  HH:mm"),
  },

  props: {
    meeting: Object,
    question: Object,
  },

  data() {
    return {
      data_response: null,
      polling: null,
      parseISO,
    };
  },

  computed: {
    dataResponseComputed: function () {
      return this.$data.data_response;
    },
    meeting_start: function () {
      return parseISO(this.meeting.meeting_start);
    },
    meeting_end: function () {
      return parseISO(this.meeting.meeting_end);
    },
  },

  created() {
    this.getDataResponse();

    // only poll data if the meeting is live at the time of loading page
    // should really have a poll to check the time every so often to call and clear the instance
    if (
        this.meeting_start <= Date.now() && Date.now() <= this.meeting_end
    ) {
      this.pollData();
    }
  },

  methods: {
    pollData() {
      this.polling = setInterval(() => {
        this.getDataResponse();
      }, 30000);
    },
    getDataResponse() {
      axios
        .post(
          `/get-feedback/${this.meeting.meeting_reference}/${this.question.id}`
        )
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
  },

  beforeDestroy() {
    clearInterval(this.polling);
  },
};
</script>
