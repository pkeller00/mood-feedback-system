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
          <input id="myQ" name="myQ" type="text">
          <select name="q_type" id="q_type">
            <option value="0">Short Answer</option>
            <option value="1">Long Answer</option>
            <option value="2">Rating Slider</option>
            <option value="3">Emoji Picker</option>
          </select>
             <!-- Will need to add in a seperate notification form or something -->
          <button @click="addQuestion()">New Question</button>
          <form @submit.prevent="submit">
            <h1>Your Form</h1>
            <div class="mt-4" v-for="(question,i) in form_data.questions" :key="question.id">
              <!-- Show question added with a remove question button -->
              <p>Question: {{ question.question }}</p>
              <button @click="deleteQuestion(i)" type="button"> Remove Question </button>
            </div>
            <div class="flex items-center justify-center mt-4">
              <jet-button class="ml-4" type="submit">
                Submit Event
              </jet-button>
            </div>
          </form>
          <div>
          </div>
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
        general: JSON.parse(JSON.stringify(this.meeting)) //Copy from previous page
      }),
    };
  },

  methods: {
    deleteQuestion: function ($index) {
        this.form_data.questions.splice($index,1);
    },
    submit() {
      //If list is empty then doesn't have a first element so don't submit
      if(this.form_data.questions[0] == null){
        alert("Cannot submit a form without questions");
      }else{
        this.form_data.post(this.route("meetings.store"));
      }
    },
    addQuestion: function () {
        var user_question = document.getElementById("myQ");
        var user_question_type = document.getElementById("q_type");
        if (user_question.value === ''){
            alert("Can't add empty question");
        }else{
            this.form_data.questions.push({ question: user_question.value, type:user_question_type.value });
            user_question.value = '';  
            user_question_type.value = '0';  
        }
          
    } 
  },
};
</script>



