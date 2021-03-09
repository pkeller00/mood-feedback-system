<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create Event Feedback Form
      </h2>
    </template>

    <div class="pt-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          Creating feedback form for:
          <div class="mt-2 text-xl">
            {{ meeting.name }}
          </div>

          <div class="mt-2">
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
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          <div class="">
            <jet-label for="question" value="Enter your question here" />
            <jet-input
              id="question"
              name="question"
              v-model.trim="new_question.question"
              type="text"
              class="mt-1 block w-full"
              autofocus
              required
            />
          </div>
          <div
            v-if="new_question_errors.empty_question"
            class="mt-2 text-sm text-red-600"
          >
            You have not provided a question
          </div>

          <div class="mt-2">
            <jet-label
              for="question_type"
              value="What type of response are you after"
            />
            <select
              id="question_type"
              name="question_type"
              v-model="new_question.question_type"
              class="mt-1 block w-full resize-y border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >
              <option value="0">Short Answer</option>
              <option value="1">Long Answer</option>
              <option value="2">Rating Slider</option>
              <option value="3">Emoji Picker</option>
              <!-- <option value="4">Multiple Choice Question</option> -->
            </select>

            <div class="flex items-center justify-center mt-2">
              <jet-button
                class="ml-4"
                type="button"
                @click.native.prevent="addQuestion()"
                >Add Question</jet-button
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Final form -->
    <div class="pb-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          <form @submit.prevent="submit">
            <div class="mt-2 text-2xl">Your Form</div>

            <div
              class="w-full my-2 p-4 bg-white overflow-hidden shadow-md rounded-lg border border-gray-200"
              v-for="(question, i) in form_data.questions"
              :key="question.id"
            >
              <div class="">
                <label :for="i" class="block font-medium text-gray-700"
                  >{{ i + 1 }}. {{ question.question }}</label
                >
                <template v-if="question.question_type == 0">
                  <!-- short text -->
                  <jet-input
                    :id="i"
                    type="text"
                    class="mt-1 block w-full"
                    disabled
                  />
                </template>
                <template v-else-if="question.question_type == 1">
                  <!-- long text -->
                  <textarea
                    :id="i"
                    placeholder="add multiple lines"
                    class="mt-1 block w-full resize-y border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    disabled
                  ></textarea>
                </template>
                <template v-else-if="question.question_type == 2">
                  <!-- rating slider -->
                  <div class="flex justify-center">
                    <div
                      class="flex flex-col justify-center max-w-lg text-center"
                    >
                      <div
                        class="flex flex-row w-full justify-between space-x-5 sm:space-x-10"
                      >
                        <div class="sm:w-24 text-center">really bad</div>
                        <div class="sm:w-24 text-center">okay</div>
                        <div class="sm:w-24 text-center">really good</div>
                      </div>
                      <input
                        :id="i"
                        type="range"
                        name=""
                        min="-1"
                        max="1"
                        step="0.1"
                        value="0"
                        class="w-full focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        disabled
                      />
                    </div>
                  </div>
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
                        type="radio"
                        name="emojipicker"
                        value="-1"
                        disabled
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
                        type="radio"
                        name="emojipicker"
                        value="0"
                        disabled
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
                        type="radio"
                        name="emojipicker"
                        value="1"
                        disabled
                      />
                    </div>
                  </div>
                </template>
                <!-- <template v-if="question.question_type == 4">
                  multiple choice input
                  <jet-input
                    :id="i"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                  />
                </template> -->
                <div class="flex justify-center sm:justify-end w-full">
                  <jet-button
                    class="mt-1 bg-red-800 hover:bg-red-700 active:bg-red-900 focus:border-red-900"
                    type="button"
                    @click.native.prevent="deleteQuestion(i)"
                    >Remove Question</jet-button
                  >
                </div>
              </div>
            </div>

            <div class="mt-4 flex max-w-24 justify-center">
              <vue-recaptcha
                ref="recaptcha"
                @verify="onVerify"
                sitekey="6Lf3oHAaAAAAACbBSSi60Lk4fK9S1tq6iicm-Y_y"
              >
              </vue-recaptcha>
            </div>

            <div v-if="errors.questions" class="mt-3 text-sm text-red-600">
              {{ errors.questions }}
            </div>
            <div v-if="!form_data.questions.length" class="text-gray-600">
              Your form is currently empty
            </div>
            <div class="flex items-center justify-center mt-4">
              <jet-button class="ml-4" type="submit"> Submit Event </jet-button>
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
import JetLabel from "@/Jetstream/Label";
import VueRecaptcha from "vue-recaptcha";
import { createDateFilter } from "vue-date-fns";
import { isSameDay, parseISO } from "date-fns";

export default {
  components: {
    AppLayout,
    JetButton,
    JetInput,
    JetLabel,
    VueRecaptcha,
  },

  props: {
    errors: Object,
    meeting: Object,
  },

  filters: {
    date: createDateFilter("EEEE do MMMM yyyy  HH:mm"),
    sameday: createDateFilter("HH:mm"),
  },

  data() {
    return {
      isSameDay,
      new_question_errors: {
        empty_question: false,
      },
      new_question: {
        question: "",
        question_type: 0,
      },
      form_data: this.$inertia.form({
        questions: [
          { question: "Did you enjoy today's meeting?", question_type: "3" },
          {
            question: "Do you have any comments, or concerns?",
            question_type: "1",
          },
        ],
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
  },

  methods: {
    deleteQuestion($index) {
      this.form_data.questions.splice($index, 1);
    },
    submit() {
      if (this.form_data.robot) {
        this.form_data.post(this.route("meetings.store"));
      }
      return;
    },
    onVerify: function (response) {
      if (response) this.form_data.robot = true;
    },
    addQuestion() {
      // Validate that there is a question
      if (!this.new_question.question) {
        return (this.new_question_errors.empty_question = true);
      }

      // Add question mark to end automatically if not present
      if (
        this.new_question.question.charAt(
          this.new_question.question.length - 1
        ) != "?"
      ) {
        this.new_question.question += "?";
      }

      // Add question to the array model
      this.form_data.questions.push({
        question: this.new_question.question,
        question_type: this.new_question.question_type,
      });

      // Reset other model instances
      this.new_question.question = "";
      this.new_question.question_type = 0;
      this.new_question_errors.empty_question = false;
    },
  },
};
</script>



