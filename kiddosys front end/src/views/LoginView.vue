<template>
  <section class="bg-gray-50 dark:bg-gray-900 mt-20">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h3 class="text-center mb-8 text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Sign in to your account
          </h3>
          <!-- Sign-in Form -->
          <div v-if="isLoading"><Spinner/></div>
          <form class="space-y-4 md:space-y-6" v-else @submit.prevent="handlePrimaryClick">
            <div>
              <TextInput
                label="Email"
                type="email"
                v-model="email"
                placeholder="Enter your email"
                inputId="email-input"
                required
              />
            </div>
            <div>
              <TextInput
                label="Password"
                type="password"
                v-model="password"
                placeholder="Enter your password"
                inputId="password-input"
                required
              />
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input
                    id="remember"
                    aria-describedby="remember"
                    type="checkbox"
                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800"
                  />
                </div>
                <div class="ml-3 text-sm">
                  <label for="remember" class="text-gray-500 dark:text-gray-300">Remember me</label>
                </div>
              </div>
              <a href="#" @click="openForgotPasswordModal" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">
                Forgot password?
              </a>
            </div>
            <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign in</button>
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
              Don’t have an account yet? <a href="#" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign up</a>
            </p>
          </form>

        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/useAuthStore';
import TextInput from '@/components/TextInput.vue';
import Spinner from '@/components/Spinner.vue';

// State
const email = ref('');
const password = ref('');
const forgotPasswordEmail = ref('');
const isLoading = ref(false);
const showForgotPasswordModal = ref(false);
const errorMessage = ref('');
const router = useRouter();
const authStore = useAuthStore();
const alertType = ref('error');
const alertMessage = ref('');
const showAlert = ref(false);

// Function to show alert
function displayAlert(message: string, error: any) {
  alertMessage.value = `${message} ${error.message}`;
  alertType.value = 'error';
  showAlert.value = true;
}

async function handlePrimaryClick() {
  errorMessage.value = '';
  isLoading.value = true;
  try {
    await authStore.login(email.value, password.value);
    email.value = '';
    password.value = '';
    router.push('/admin');
  } catch (error) {
    displayAlert('Failed to log in:', error);
  } finally {
    isLoading.value = false;
  }
}

function dismissAlert() {
  showAlert.value = false;
}

</script>

<style scoped>
/* Add any additional styles if needed */
</style>
