<template>
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" aria-labelledby="modal-headline" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen">
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="mt-10">
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                Edit Registration
              </h3>
              <div class="mt-2">
                <form @submit.prevent="updateRegistration">
                  <!-- Name -->
                  <FormInput inputId="customer-name" label="Your Name" v-model="registration.customer_name" type="text" required />

                  <!-- Phone -->
                  <FormInput inputId="customer-phone" label="Your Number Phone" v-model="registration.phone_number" type="tel" placeholder="10/11 digit phone number" required />

                  <!-- Address -->
                  <TextArea inputId="address" label="Your Address" v-model="registration.address" row="4" cols="50" required />

                  <!-- Date Picker -->
                  <div>
                    <label class="mt-5 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reservation Date</label>
                    <input v-model="reservationDate" type="date" class="input-field" required @input="updateAvailableTimes">
                  </div>

                  <!-- Time Picker -->
                  <div>
                    <label class="mt-5 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reservation Time</label>
                    <select v-model="reservationTime" class="input-field" required :disabled="availableTimes.length === 0">
                      <option v-if="availableTimes.length === 0" disabled>No available times</option>
                      <option v-for="time in availableTimes" :key="time" :value="time">{{ time }}</option>
                    </select>
                  </div>

                  <!-- Children -->
                  <div>
                    <label class=" mt-5 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Children</label>
                    <div v-for="(child, index) in registration.children" :key="index" class="flex gap-2 items-center mb-2">
                      <select v-model.number="child.age" class="input-field" required>
                        <option value="" disabled selected>Select Years</option>
                        <option value="0">0 years</option>
                        <option v-for="year in 11" :key="year" :value="year + 1">{{ year + 1 }} years</option>
                      </select>
                      <select v-model.number="child.month" class="input-field" required>
                        <option value="" disabled selected>Select Months</option>
                        <option v-for="month in 12" :key="month" :value="month" :disabled="child.age === 12 && month === 12">
                          {{ month }} months
                        </option>
                      </select>
                      <button v-if="registration.children.length > 1" @click="removeChild(index)" class="bg-red-500 text-white px-2 py-1 rounded-md">Remove</button>
                    </div>
                    <button v-if="registration.children.length < 4" @click="addChild" class="mt-5 bg-blue-500 text-white px-2 py-1 rounded-md">+ Add Child</button>
                  </div>

                  <!-- Terms Checkbox -->
                  <div class="flex items-start">
                    <input id="terms" v-model="registration.accepted_terms" type="checkbox" class="mt-5 w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300" required>
                    <label for="terms" class="mt-5 ml-3 text-sm font-light text-gray-500">I accept the <a href="#" @click.prevent="openTermsModal" class="font-medium text-blue-600 hover:underline">Terms and Conditions</a></label>
                  </div>

                  <!-- Submit Button -->
                  <button type="submit" class="mt-5 w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Update Booking</button>
                  <button type="button" @click="$emit('close')" class="mt-3 w-full text-white bg-white-500 hover:bg-white focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Cancel
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import FormInput from '@/components/TextInput.vue';
import TextArea from '@/components/TextArea.vue';
import { computed, ref, watch, onMounted } from 'vue';
import { useRegistrationStore } from '@/stores/manage-registration/registration';
import { defineProps, defineEmits } from 'vue';

const registrationStore = useRegistrationStore();
const emit = defineEmits();

const props = defineProps({
  registration: Object,
});

const availableTimes = ref([]);
const reservationDate = ref('');
const reservationTime = ref('');

onMounted(() => {
  const datetime = new Date(props.registration.reservation_datetime);
  reservationDate.value = datetime.toISOString().split('T')[0];
  const hours = datetime.getHours().toString().padStart(2, '0');
  const minutes = datetime.getMinutes().toString().padStart(2, '0');
  reservationTime.value = `${hours}:${minutes}`;
  availableTimes.value = [];
  for (let i = 0; i < 24; i++) {
    for (let j = 0; j < 60; j += 30) {
      const time = `${i.toString().padStart(2, '0')}:${j.toString().padStart(2, '0')}`;
      availableTimes.value.push(time);
    }
  }

  props.registration.children.forEach((child) => {
    // Ensure that month is defaulted to 0 if it's not set
    child.age = child.age || 0;
    child.month = child.month || 0; // Set default month to 0 if not provided
  });
});

const updateRegistration = async () => {
  const fullDatetime = `${reservationDate.value} ${reservationTime.value}:00`;
  const formData = new FormData();
  formData.append('customer_name', props.registration.customer_name);
  formData.append('phone_number', props.registration.phone_number);
  formData.append('address', props.registration.address);
  formData.append('reservation_datetime', fullDatetime);
  formData.append('accepted_terms', props.registration.accepted_terms);
  props.registration.children.forEach((child, index) => {
    formData.append(`children[${index}][age]`, child.age);
    formData.append(`children[${index}][month]`, child.month);
  });

  try {
    const response = await registrationStore.updateRegistration(props.registration.id, formData);
    console.log(response);
    emit('close');

  } catch (error) {
    console.error(error);
  }
};

const addChild = () => {
  const newChild = { age: 0, month: 0 }; // Set default age and month
  const updatedChildren = [...props.registration.children, newChild];
  registrationStore.updateChildren(props.registration.id, updatedChildren);
};

const removeChild = (index) => {
  const updatedChildren = props.registration.children.filter((_, i) => i !== index);
  registrationStore.updateChildren(props.registration.id, updatedChildren);
};

const openTermsModal = () => {
};

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
