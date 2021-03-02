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
            <p class="raisin-black">
              {{ meeting.meeting_start }} to {{ meeting.meeting_end }}
            </p>
          </div>
        </div>
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          <div class="mt-4">
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
            class="mt-3 text-sm text-red-600"
          >
            You have not provided a question
          </div>

          <div class="mt-4">
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

            <div class="flex items-center justify-center mt-4">
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
              class="mt-4"
              v-for="(question, i) in form_data.questions"
              :key="question.id"
            >
              <p>Question: {{ question.question }}</p>
              <p v-if="question.question_type == 0">
                Question type: short text input
              </p>
              <p v-if="question.question_type == 1">
                Question type: long text input
              </p>
              <p v-if="question.question_type == 2">
                Question type: rating slider
              </p>
              <p v-if="question.question_type == 3">
                Question type: emoji picker
              </p>
              <!-- <p v-if="question.type == 4">Question type: multiple choice</p> -->
              <jet-button
                class="mr-4 sm:mr-0 bg-red-800 hover:bg-red-700 active:bg-red-900 focus:border-red-900"
                type="button"
                @click.native.prevent="deleteQuestion(i)"
                >Remove Question</jet-button
              >
            </div>
            <div v-if="errors.questions" class="mt-3 text-sm text-red-600">
              {{ errors.questions }}
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
// import JetCheckbox from "@/Jetstream/Checkbox";
// import JetValidationErrors from "@/Jetstream/ValidationErrors";
// import JetDropdown from "@/Jetstream/Dropdown";
// import JetDropdownLink from "@/Jetstream/DropdownLink";

export default {
  components: {
    AppLayout,
    JetButton,
    JetInput,
    JetLabel,
    // JetCheckbox,
    // JetDropdown,
    // JetDropdownLink,
    // JetValidationErrors,
  },

  props: {
    errors: Object,
    meeting: Object,
  },

  data() {
    return {
      new_question_errors: {
        empty_question: false,
      },
      new_question: {
        question: "",
        question_type: 0,
      },
      form_data: this.$inertia.form({
        questions: [],
        // formRequest: true,
      }),
    };
  },

  methods: {
    deleteQuestion($index) {
      this.form_data.questions.splice($index, 1);
    },
    submit() {
      //If list is empty then doesn't have a first element so don't submit
      //   if (this.form_data.questions[0] == null) {
      //     alert("Cannot submit a form without questions");
      //   } else {
      this.form_data.post(this.route("meetings.store"));
      //   }
    },
    addQuestion() {
      // Validate that there is a question
      if (!this.new_question.question) {
        return (this.new_question_errors.empty_question = true);
      }

      // Add question mark to end automatically if not present
      if (
        this.new_question.question.charAt(this.new_question.question.length - 1) != "?"
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



