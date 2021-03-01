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
        <div class="mt-4">
          <jet-label for="myQ" value="Question" />
          <jet-input
            id="myQ"
            name="myQ"
            type="text"
            class="mt-1 block w-full"
            autofocus
          />
        </div>

        <div class="mt-4">
          <jet-label for="q_type" value="Type" />
          <jet-input
            id="q_type"
            name="q_type"
            type="text"
            list="tickmarks"
            class="mt-1 block w-full"
            autofocus
          />
        </div>
 
        <datalist id="tickmarks" class="text-black">
          <option value="Short Answer" label="Short Answer"></option>
          <option value="Long Answer" label="Long Answer"></option>
          <option value="Rating Slider" label="Rating Slider"></option>
          <option value="Emoji Picker" label="Emoji Picker"></option>
        </datalist>
 
          <div class="flex items-center justify-center mt-4">
            <jet-button class="ml-4" type="button" @click.native.prevent="addQuestion()">New Question</jet-button>
          </div>
        </div>  

      <!-- Final form -->
        <div
            class="w-full mt-6 px-6 py-4 bg-white overflow-hidden shadow-xl sm:rounded-lg"
        >
          <form @submit.prevent="submit">
            <div class="mt-2 text-2xl">
              Your Form
            </div>
            <div class="mt-4" v-for="(question,i) in form_data.questions" :key="question.id">
              <p>Question: {{ question.question }}</p>
              <p v-if="question.type == 0">Answer type: short text input</p>
              <p v-if="question.type == 1">Answer type: long text input</p>
              <p v-if="question.type == 2">Answer type: rating slider</p>
              <p v-if="question.type == 3">Answer type: emoji picker</p>
              <p v-if="question.type == 4">Answer type: multiple choice</p>
              <jet-button class="mr-4 sm:mr-0 bg-red-800 hover:bg-red-700 active:bg-red-900 focus:border-red-900" type="button" @click.native.prevent="deleteQuestion(i)">Remove Question</jet-button>
            </div>
            <div class="flex items-center justify-center mt-4">
              <jet-button class="ml-4" type="submit">
                Submit Event
              </jet-button>
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
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import JetDropdown from "@/Jetstream/Dropdown";
import JetDropdownLink from "@/Jetstream/DropdownLink";

export default {
  components: {
    AppLayout,
    JetButton,
    JetInput,
    JetCheckbox,
    JetDropdown,
    JetDropdownLink,
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
        var type_val = 0;
        var question_str = user_question.value;
        if (user_question.value === ''){
            alert("Can't add empty question");
        }else{
            if(user_question_type.value === 'Short Answer'){
              type_val = 0;
            }else if(user_question_type.value === 'Long Answer'){
              type_val = 1;
            }else if(user_question_type.value === 'Rating Slider'){
              type_val = 2;
            }else if(user_question_type.value === 'Emoji Picker'){
              type_val = 3;
            }else{
              alert("Not valid question type");
              return;
            }
            var last_char = question_str.charAt(question_str.length-1);
            if(last_char != "?"){
              question_str += "?";
            }
            this.form_data.questions.push({ question:question_str, type:type_val });
            user_question.value = '';  
            user_question_type.value = '';  
        }
          
    } 
  },
};
</script>



