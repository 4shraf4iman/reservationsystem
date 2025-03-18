<template>
  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
      <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
        Babysitter Booking
      </a>
      <div class="w-full bg-white rounded-lg shadow dark:border sm:max-w-3xl xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 sm:p-8">
          <form @submit.prevent="submitReservation" class="space-y-4">
            <!-- Name -->
            <FormInput inputId="customer-name" label="Your Name" v-model="customerName" type="text" required />

            <!-- Phone -->
            <FormInput inputId="phone-number" label="Your Phone Number" v-model="phoneNumber" type="tel" required placeholder="10/11 digit phone number" />

            <!-- Address -->
            <FormArea inputId="address" label="Your Address" v-model="address" row="4" cols="50" required />

            <!-- Date Picker -->
            <input
              class="bg-gray-50 border-gray-200 text-gray-800 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-3.5 dark:bg-gray-700 dark:border-gray-600 placeholder:text-xs dark:placeholder-black-900 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              id="reservation-date"
              type="date"
              :min="minDate"
              :max="maxDate"
              required
              v-model="reservationDate"
              @input="updateAvailableTimes"
            />

            <!-- Time Picker -->
            <div>
              <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reservation Time</label>
              <select v-model="reservationTime" class="input-field" required :disabled="availableTimes.length === 0">
                <option v-if="availableTimes.length === 0" disabled>No available times</option>
                <option v-for="time in availableTimes" :key="time" :value="time">{{ time }}</option>
              </select>
            </div>

            <!-- Children -->
            <div>
              <div v-for="(child, index) in children" :key="index" class="flex gap-2 items-center mb-2">
                <label class="w-full block mb-2 text-sm font-medium text-gray-900 dark:text-white">Children {{ index + 1 }}</label>
                <select v-model.number="child.years" class="input-field" required>
                  <option value="" disabled selected>Select Years</option>
                  <option value="0">0 years</option>
                  <option v-for="year in 12" :key="year" :value="year">{{ year }} years</option>
                </select>

                <select v-model.number="child.months" class="input-field" :required="child.years !== 0">
                  <option value="" disabled selected>Select Months</option>
                  <option v-if="child.years !== 0" value="0">0 months</option>
                  <option v-for="month in 12" :key="month" :value="month" :disabled="child.years === 12 && month === 12">
                    {{ month }} months
                  </option>
                </select>
              </div>
              <button v-if="children.length > 1" @click="removeChild(index)" class="bg-red-500 text-white px-2 py-1 rounded-md">- Remove Child</button>
              &nbsp;&nbsp;
              <button v-if="children.length < 4" @click="addChild" class="bg-blue-500 text-white px-2 py-1 rounded-md">+ Add Child</button>
            </div>

            <!-- Terms Checkbox -->
            <div class="flex items-start">
              <input id="terms" v-model="acceptedTerms" type="checkbox" class="w-4 h-4 border rounded bg-gray-50 focus:ring-3 focus:ring-primary-300" required />
              <label for="terms" class="ml-3 text-sm font-light text-gray-500">I accept the <a href="#" @click.prevent="openTermsModal" class="font-medium text-blue-600 hover:underline">Terms and Conditions</a></label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Submit Booking</button>
          </form>

          <!-- Error & Success Messages -->
          <div v-if="errorMessage" class="text-red-500"><h2 class="text-lg font-semibold text-gray-900">Date are Incorrect, must between in 30 days reserve</h2></div>
          <div v-if="successMessage" class="text-green-500">{{ successMessage }}</div>

          <!-- Terms Modal -->
          <div v-if="showTermsModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md">
              <h2 class="text-lg font-semibold text-gray-900">Terms and Conditions</h2>
              <ul class="mt-4 text-gray-600 list-disc list-inside">
                <li>Reservation must be at least 6 hours ahead and within 30 days.</li>
                <li>Maximum 4 children per reservation.</li>
                <li>Children must be between 1 month and 12 years 11 months.</li>
              </ul>
              <button @click="showTermsModal = false" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md">Close</button>
            </div>
          </div>

          <!-- Success Modal -->
          <div v-if="showSuccessModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md">
              <h2 class="text-lg font-semibold text-gray-900">Booking Successful!</h2>
              <p class="mt-4 text-gray-600">{{ successMessage }}</p>
              <button @click="showSuccessModal = false; resetForm()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md">Finish</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script setup>
import { ref, computed } from 'vue';
import { useRegistrationStore } from '@/stores/manage-registration/registration';
import FormInput from '@/components/Textinput.vue';
import FormArea from '@/components/TextArea.vue';

const registrationStore = useRegistrationStore();

// Form Data
const customerName = ref('');
const address = ref('');
const phoneNumber = ref('');
const reservationDate = ref('');
const reservationTime = ref('');
const children = ref([{ years: null, months: null }]);
const acceptedTerms = ref(false);
const errorMessage = ref('');
const showTermsModal = ref(false);
const successMessage = ref('');
const showSuccessModal = ref(false);

// Date Range
const minDate = computed(() => new Date().toISOString().split('T')[0]);

const maxDate = computed(() => {
  const maxDate = new Date();
  maxDate.setDate(maxDate.getDate() + 30);
  return maxDate.toISOString().split('T')[0];
});

// Time Selection
const availableTimes = ref([]);

const updateAvailableTimes = () => {
  availableTimes.value = [];
  if (!reservationDate.value) return;

  const selectedDate = new Date(reservationDate.value);
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  selectedDate.setHours(0, 0, 0, 0);

  let startHour = 0;
  if (selectedDate.getTime() === today.getTime()) {
    const minTime = new Date();
    minTime.setHours(minTime.getHours() + 6);
    startHour = minTime.getHours();
  }

  for (let i = startHour; i < 24; i++) {
    availableTimes.value.push(`${i.toString().padStart(2, '0')}:00`);
    availableTimes.value.push(`${i.toString().padStart(2, '0')}:30`);
  }
};

// Add & Remove Children
const addChild = () => children.value.length < 4 && children.value.push({ years: null, months: null });
const removeChild = (index) => children.value.splice(index, 1);

// Submit
const submitReservation = async () => {
  if (!acceptedTerms.value) {
    errorMessage.value = 'You must accept the terms.';
    return;
  }

  const selectedDateTime = new Date(`${reservationDate.value}T${reservationTime.value}:00`);
  const minDateTime = new Date();
  minDateTime.setHours(minDateTime.getHours() + 6);

  const maxDateTime = new Date();
  maxDateTime.setDate(maxDateTime.getDate() + 30);
  maxDateTime.setHours(23, 59, 59, 999);

  // Check if the reservation is at least 6 hours ahead and within the 30-day range
  if (selectedDateTime < minDateTime) {
    errorMessage.value = 'The reservation time must be at least 6 hours from now.';
    return;
  }

  if (selectedDateTime > maxDateTime) {
    errorMessage.value = 'The reservation must be within 30 days.';
    return;
  }

  if (children.value.some((child) => child.years === null || child.months === null)) {
    errorMessage.value = 'Please enter the age of all children.';
    return;
  }

  errorMessage.value = '';
  successMessage.value = '';

  try {
    const fullDatetime = `${reservationDate.value} ${reservationTime.value}:00`;

    const response = await registrationStore.createRegistration({
      customer_name: customerName.value,
      phone_number: phoneNumber.value,
      address: address.value,
      children: children.value.map((child) => ({ age: child.years, month: child.months })),
      reservation_datetime: fullDatetime
    });

    successMessage.value = `Booking successful! Booking No: ${response?.booking_no}`;
    showSuccessModal.value = true;
  } catch (error) {
    errorMessage.value = error.message || 'An error occurred';
  }
};

// Reset Form
const resetForm = () => {
  customerName.value = '';
  address.value = '';
  phoneNumber.value = '';
  reservationDate.value = '';
  reservationTime.value = '';
  children.value = [{ years: null, months: null }];
  acceptedTerms.value = false;
  errorMessage.value = '';
  successMessage.value = '';
};

const openTermsModal = () => showTermsModal.value = true;
</script>
<style>
.input-field {
  background-color: #f9fafb;
  border: 1px solid #d1d5db;
  padding: 10px;
  width: 100%;
  border-radius: 5px;
}
</style>
