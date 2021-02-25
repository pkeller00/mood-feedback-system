<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Leave Feedback
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- general meeting information -->
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

        <!-- <jet-validation-errors class="mb-4" /> -->
        <form @submit.prevent="submit">
          <!-- For each question in form lets user answer them -->
          <div
            class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
            v-for="(question, i) in feedback_response.questions"
            :key="question.id"
          >
            <div class="mt-4">
              Question {{ i }}.
              <jet-label :for="i" :value="question.question" />
              This is the type of question :
              <p class="block font-medium text-sm text-gray-700">
                {{ question.question_type }}
              </p>
              <!-- different form question types should go here through a v-if -->
              Place response here:
              <jet-input
                :id="i"
                type="text"
                class="mt-1 block w-full"
                required
                v-model="feedback_response.resp[i]"
                autofocus
              />
              <!-- <div v-if="errors.name" class="mt-3 text-sm text-red-600">
                  {{ errors.name }}
                </div> -->
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
    // questions: Array,
    errors: Object,
  },

  data() {
    return {
      feedback_response: this.$inertia.form({
        questions: this.$page.props.questions,
        resp: [],
        // meeting_start: "",
        // meeting_end: "",
      }),
    };
  },

  methods: {
    submit() {
      this.$inertia.post(`/submit-feedback`, this.feedback_response);
    //   this.feedback_response.post(this.route("attendevents.store"));
      // .transform(data => ({
      //     ... data,
      //     remember: this.form.remember ? 'on' : ''
      // }))
      // .post(this.route('meetings.store'), {
      //     onFinish: () => this.form.reset('password'),
      // })
    },
  },
};
</script>



