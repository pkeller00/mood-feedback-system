<template>
  <div>
    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
      <!-- <div>
                <jet-application-logo class="block h-12 w-auto" />
            </div> -->

      <div class="mt-8 text-2xl">
        Are your audience as engaged as you would hope?
      </div>

      <div class="mt-6 text-gray-500">
        Use the Mood Feedback System to host your next event. Receive live
        analysis of your audience and improve your engagement levels. With live
        feedback you can now tailor your sessions to your audience. Feedback is
        guaranteed to be better quality than traditional methods.
      </div>
    </div>

    <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">
      <div class="p-6">
        <div class="flex items-center">
          <svg
            fill="none"
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            viewBox="0 0 24 24"
            class="w-8 h-8 text-gray-400"
          >
            <path
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
            ></path>
          </svg>
          <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">
            <inertia-link :href="route('home')"
              >Got an event to join?</inertia-link
            >
          </div>
        </div>

        <div class="ml-12">
          <!-- <div class="mt-2 text-sm text-gray-500">
                        Got a meeting to join?
                    </div> -->

          <form @submit.prevent="submit_attend">
            <div class="mt-4">
              <jet-label for="code" value="Enter access code" />
              <!-- <jet-input id="name" type="text" class="mt-1 block w-full" v-model="meeting.name" required autofocus /> -->
              <jet-input
                id="code"
                type="text"
                class="mt-1 block w-full"
                v-model="access_code.code"
                required
                autofocus
              />
              <div v-if="badAccessCode">
                <div v-if="errors.code" class="mt-3 text-sm text-red-600">
                  {{ errors.code }}
                </div>
              </div>
            </div>

            <div class="flex items-center justify-end mt-4">
              <jet-button class="ml-4">
                <!-- <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> -->
                Join Event
              </jet-button>
            </div>
          </form>
        </div>
      </div>

      <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">
        <div class="flex items-center">
          <svg
            fill="none"
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            viewBox="0 0 24 24"
            class="w-8 h-8 text-gray-400"
          >
            <path
              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
            ></path>
            <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">
            <a href="https://laracasts.com">Host an event</a>
          </div>
        </div>

        <div class="ml-12">
          <div class="mt-2 text-sm text-gray-500">
            Have an upcoming event? Go ahead and create an event, customise your
            templates and be prepared for hosting your session.
            <span v-if="this.$page.props.user === null">
              If you have not done so already,
              <inertia-link :href="route('register')" class="text-indigo-700">
                register an account
              </inertia-link>
              with us.
            </span>
          </div>

          <inertia-link :href="route('meetings.create')">
            <div class="flex items-center justify-end mt-4">
              <jet-button class="ml-4"> Create Event </jet-button>
            </div>
          </inertia-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

export default {
  components: {
    JetButton,
    JetInput,
    JetLabel,
    JetValidationErrors,
  },

  data() {
    return {
      access_code: this.$inertia.form({
        code: "",
      }),
    };
  },

  computed: {
    errors() {
      return this.$page.props.errors;
    },

    badAccessCode() {
      return Object.keys(this.errors).length > 0;
    },
  },

  methods: {
    submit_attend() {
      this.$inertia.post(`/attend-event`, this.access_code);
    },
  },
};
</script>
