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

        <jet-validation-errors class="mb-4" />
        <form @submit.prevent="submit">
          <!-- For each question in form lets user answer them -->
          <div
            class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
            v-for="(question, i) in feedback_response.questions"
            :key="question.id"
          >
            <div class="mt-4">
              <label :for="i" class="block font-medium text-gray-700"
                >{{ i }}) {{ question.question }}</label
              >
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
                <label>
                  <input
                    id="sad"
                    v-model="feedback_response.responses[i]"
                    type="radio"
                    name="emojipicker"
                    value="-1"
                    required
                    autofocus
                  />
                  sad
                </label>
                <label>
                  <input
                    id="neutral"
                    v-model="feedback_response.responses[i]"
                    type="radio"
                    name="emojipicker"
                    value="0"
                  />
                  neutral
                </label>
                <label>
                  <input
                    id="happy"
                    v-model="feedback_response.responses[i]"
                    type="radio"
                    name="emojipicker"
                    value="1"
                  />
                  happy
                </label>
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
                {{ errors }}
              </div>
            </div>
          </div>

          <!-- User can input their user information here - if the user is signed in it puts their email address by default -->
          <div
            class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
          >
            <div class="mt-4">
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

export default {
  components: {
    AppLayout,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors,
  },

  props: {
    meeting: Object,
  },

  data() {
    return {
      feedback_response: this.$inertia.form({
        questions: this.$page.props.questions,
        responses: [],
        name: "",
        email: "",
      }),
    };
  },

  computed: {
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
      this.$inertia.post(`/submit-feedback/${this.meeting.meeting_reference}`, this.feedback_response);
    },
  },
};
</script>
