<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Leave Feedback
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- General meeting information can be formatted here -->
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          <div class="text-2xl">
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

        <jet-validation-errors class="mb-4" />
        <form @submit.prevent="submit">
          <!-- For each question in form lets user answer them -->
          <div
            class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
            v-for="(question, i) in feedback_response.questions"
            :key="question.id"
          >
            <div class="">
              <label :for="i" class="block font-medium text-gray-700">{{
                question.question
              }}</label>
              <!-- This is the type of question : -->
              <p v-if="question.question_type == 0" class="text-xs">
                short text input
              </p>
              <p v-if="question.question_type == 1" class="text-xs">
                long text input
              </p>
              <p v-if="question.question_type == 2" class="text-xs">
                rating slider
              </p>
              <p v-if="question.question_type == 3" class="text-xs">
                emoji picker
              </p>
              <p v-if="question.question_type == 4" class="text-xs">
                multiple choice
              </p>

              <template v-if="question.question_type == 0">
                <!-- short text -->
                <jet-input
                  :id="i"
                  v-model.trim="feedback_response.responses[i]"
                  type="text"
                  class="mt-1 block w-full"
                  required
                  autofocus
                />
              </template>
              <template v-else-if="question.question_type == 1">
                <!-- long text -->
                <textarea
                  :id="i"
                  v-model.trim="feedback_response.responses[i]"
                  placeholder="add multiple lines"
                  class="mt-1 block w-full resize-y border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                  required
                  autofocus
                ></textarea>
              </template>
              <template v-else-if="question.question_type == 2">
                <!-- rating slider -->
                <input
                  :id="i"
                  v-model="feedback_response.responses[i]"
                  type="range"
                  name=""
                  min="-1"
                  max="1"
                  step="0.25"
                  value="0"
                  list="tickmarks"
                  required
                  autofocus
                />
                <datalist id="tickmarks" class="text-black">
                  <option value="-1" label="Really bad"></option>
                  <option value="-0.75"></option>
                  <option value="-0.5"></option>
                  <option value="-0.25"></option>
                  <option value="0" label="Average"></option>
                  <option value="0.25"></option>
                  <option value="0.5"></option>
                  <option value="0.75"></option>
                  <option value="1" label="Really good"></option>
                </datalist>
              </template>
              <template v-else-if="question.question_type == 3">
                <!-- emoji picker -->
                <div
                  class="flex flex-row w-full justify-center space-x-5 sm:space-x-10"
                >
                  <div class="text-center">
                    <label for="sad">
                      <!-- sad -->
                      <svg
                        class="fill-current text-red-500 w-16 sm:w-20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                      >
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <circle cx="15.5" cy="9.5" r="1.5" />
                        <circle cx="8.5" cy="9.5" r="1.5" />
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-6c-2.33 0-4.32 1.45-5.12 3.5h1.67c.69-1.19 1.97-2 3.45-2s2.75.81 3.45 2h1.67c-.8-2.05-2.79-3.5-5.12-3.5z"
                        />
                      </svg>
                    </label>
                    <input
                      id="sad"
                      v-model="feedback_response.responses[i]"
                      type="radio"
                      name="emojipicker"
                      value="-1"
                      required
                      autofocus
                    />
                  </div>
                  <div class="text-center">
                    <label for="neutral">
                      <!-- neutral -->
                      <svg
                        class="fill-current text-yellow-500 w-16 sm:w-20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                      >
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M9 15.5h6v1H9v-1z" />
                        <circle cx="15.5" cy="9.5" r="1.5" />
                        <circle cx="8.5" cy="9.5" r="1.5" />
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"
                        />
                      </svg>
                    </label>
                    <input
                      id="neutral"
                      v-model="feedback_response.responses[i]"
                      type="radio"
                      name="emojipicker"
                      value="0"
                    />
                  </div>
                  <div class="text-center">
                    <label for="happy">
                      <!-- happy -->
                      <svg
                        class="fill-current text-green-500 w-16 sm:w-20"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                      >
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <circle cx="15.5" cy="9.5" r="1.5" />
                        <circle cx="8.5" cy="9.5" r="1.5" />
                        <path
                          d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm-5-6c.78 2.34 2.72 4 5 4s4.22-1.66 5-4H7z"
                        />
                      </svg>
                    </label>
                    <input
                      id="happy"
                      v-model="feedback_response.responses[i]"
                      type="radio"
                      name="emojipicker"
                      value="1"
                    />
                  </div>
                </div>
              </template>
              <template v-if="question.question_type == 4">
                <!-- multiple choice input -->
                <jet-input
                  :id="i"
                  v-model.trim="feedback_response.responses[i]"
                  type="text"
                  class="mt-1 block w-full"
                  required
                  autofocus
                />
              </template>

              <!-- Different form question types should go here through a v-if -->
              <div v-if="hasErrors" class="mt-3 text-sm text-red-600">
                {{ errors.responses[i] }}
              </div>
            </div>
          </div>

          <!-- User can input their user information here - if the user is signed in it puts their email address by default -->
          <div
            class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
          >
            <div class="">
              User information (anonymity)
              <jet-label
                for="user_name"
                value="Enter your name (or blank to remain anonymous)"
              />
              <jet-input
                id="user_name"
                type="text"
                class="mt-1 block w-full"
                v-model="feedback_response.name"
                autofocus
              />
              <jet-label
                for="user_email"
                value="Enter your email (or blank to remain anonymous)"
              />
              <jet-input
                id="user_email"
                type="text"
                class="mt-1 block w-full"
                v-model="feedback_response.email"
                autofocus
              />
            </div>
          </div>
          <div class="mt-4">
            <vue-recaptcha ref="recaptcha"
              @verify="onVerify" sitekey="6Lf3oHAaAAAAACbBSSi60Lk4fK9S1tq6iicm-Y_y">
            </vue-recaptcha>
            </div>
          <div class="flex items-center justify-center mt-4">
            <jet-button class="ml-4"> Submit Feedback </jet-button>
          </div>
        </form>
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
import VueRecaptcha from 'vue-recaptcha';
import { createDateFilter } from "vue-date-fns";
import { isSameDay, parseISO } from "date-fns";

export default {
  components: {
    AppLayout,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors,
    VueRecaptcha,
  },

  props: {
    meeting: Object,
  },

  filters: {
    date: createDateFilter("EEEE do MMMM yyyy  HH:mm"),
    sameday: createDateFilter("HH:mm"),
  },

  data() {
    return {
      isSameDay,
      feedback_response: this.$inertia.form({
        questions: this.$page.props.questions,
        responses: [],
        name: "",
        email: "",
        robot: false,
      }),
    };
  },

  computed: {
    meeting_start: function () {
      return parseISO(this.meeting.meeting_start);
    },
    meeting_end: function () {
      return parseISO(this.meeting.meeting_end);
    },
    user_name_comp() {
      return this.feedback_response.name;
    },
    user_email_comp() {
      return this.feedback_response.email;
    },

    errors() {
      return this.$page.props.errors;
    },
    hasErrors() {
      return Object.keys(this.errors).length > 0;
    },
  },

  created() {
    this.feedback_response.name = this.$page.props.user.name;
    this.feedback_response.email = this.$page.props.user.email;
  },

  methods: {
    submit() {
      if (this.feedback_response.robot) {
        this.$inertia.post(`/submit-feedback/${this.meeting.meeting_reference}`, this.feedback_response);
      }
      return;
    },
    onVerify: function (response) {
      if (response) this.feedback_response.robot = true;
    },
  },
};
</script>
