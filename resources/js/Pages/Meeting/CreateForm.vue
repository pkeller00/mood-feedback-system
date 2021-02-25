<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create Meeting Form
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          <!-- <jet-validation-errors class="mb-4" /> -->

          <form @submit.prevent="submit">
            <h2>Your Form</h2>
            <div class="mt-4" v-for="(question,i) in form_data.questions" :key="question.id">
              <p>Question: {{ question.question }}</p>
              <!-- <button @click="deleteQuestion(i) >Remove Question</button> -->
              <button @click="deleteQuestion(i)" type="button"> Remove Question </button>
            </div>
            <div class="flex items-center justify-center mt-4">
              <jet-button class="ml-4" type="submit">
                Submit Event
              </jet-button>
            </div>
          </form>
          <div>
            <input id="myQ" name="myQ" type="text">
            <button @click="addQuestion()">New Question</button>
          </div>
          <pre>{{ $data }}</pre>            
        </div>
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
    errors: Object,
    meeting:Array,
  },

  data() {
    return {
      form_data: this.$inertia.form({
        questions: [],
        general: JSON.parse(JSON.stringify(this.meeting))
      }),
    };
  },

  methods: {
    deleteQuestion: function ($index) {
        this.form_data.questions.splice($index,1);
    },
    submit() {
      if(this.form_data.questions[0] == null){
        alert("Cannot submit a form without questions");
      }else{
        this.form_data.post(this.route("meetings.store"));
      }
      // .transform(data => ({
      //     ... data,
      //     remember: this.form.remember ? 'on' : ''
      // }))
      // .post(this.route('meetings.store'), {
      //     onFinish: () => this.form.reset('password'),
      // })
    },
    addQuestion: function () {
        var user_question = document.getElementById("myQ");
        if (user_question.value === ''){
            alert("add some data");
        }else{
            this.form_data.questions.push({ question: user_question.value, type:3 });
            user_question.value = '';    
        }
          
    } 
  },
};
</script>



