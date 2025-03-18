<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="mt-20 pb-4 p-4 bg-white dark:bg-gray-900">
      <h1 class="text-center">Booking for {{ $route.params.searchTerm }}</h1>

    </div>

    <table v-if="registrations.length > 0 && !isLoading" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3">
            Customer Name
          </th>
          <th scope="col" class="px-6 py-3">
            Phone Number
          </th>
          <th scope="col" class="px-6 py-3">
            Address
          </th>
          <th scope="col" class="px-6 py-3">
            Children
          </th>
          <th scope="col" class="px-6 py-3">
            Reservation Datetime
          </th>
          <th scope="col" class="px-6 py-3">
            Booking No
          </th>
          <th scope="col" class="px-6 py-3">
            Actions
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="registration in registrations" :key="registration.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
            {{ registration.customer_name }}
          </th>
          <td class="px-6 py-4">
            {{ registration.phone_number }}
          </td>
          <td class="px-6 py-4">
            {{ registration.address }}
          </td>
          <td class="px-6 py-4">
            <div>
              <p>{{ registration.children.length }} children</p>
              <ul>
                <li v-for="(child, index) in registration.children" :key="index">
                  <p><span class="text-red-500">Child {{ index + 1 }}</span>:</p>
                  <p>Age: {{ child.age }} years</p>
                  <p>Month: {{ child.month }} months</p>
                </li>
              </ul>
            </div>
          </td>
          <td class="px-6 py-4">
            {{ new Date(registration.reservation_datetime).toLocaleString() }}
          </td>
          <td class="px-6 py-4"> {{ registration.booking_no }}
          </td>
          <td class="px-6 py-4">
            <a @click="openEditModal(registration)" class="text-blue-600 hover:underline">Edit</a>
            <a @click="deleteRegistration(registration.id)" class="text-red-600 hover:underline">Cancel </a>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-else-if="!isLoading" class="p-20 flex flex-col items-center justify-center ">
      <p class="text-lg text-center text-gray-500 dark:text-gray-400">No appointments/bookings found for {{ $route.params.searchTerm }}.</p>
      <router-link to="/" class="mt-4 py-4 px-6 bg-red-500 text-white rounded-lg hover:bg-red-700 transition duration-300">Create New Reservation</router-link>
    </div>
    <div v-else class="w-full flex justify-center items-center ">
      <Spinner class="text-3xl" />
    </div>
    <edit-modal v-if="isEditModalOpen" :registration="selectedRegistration" @close="closeEditModal" @update="updateRegistration" />
  </div>
</template>

<script>
import { useRegistrationStore } from '@/stores/manage-registration/registration';
import { computed, ref, watch } from 'vue';
import EditModal from '@/components/EditModal.vue';
import { useRoute } from 'vue-router';
import Spinner from '@/components/Spinner.vue';

export default {
  components: {
    EditModal,
    Spinner,
  },
  setup() {
    const route = useRoute();
    const registrationStore = useRegistrationStore();
    const isEditModalOpen = ref(false);
    const selectedRegistration = ref(null);
    const filterValue = ref('');
    const ids = ref(route.params.searchTerm);
    const isLoading = ref(false);
    
    const fetchRegistrations = async () => {
      isLoading.value = true;
      try {
        await registrationStore.fetchRegistrationByCustomerPhone(ids.value);
      } catch (error) {
        console.error("Error fetching registrations:", error);
      } finally {
        isLoading.value = false; // Ensure this runs regardless of success or failure
      }
    };

    fetchRegistrations();

    watch(
      () => route.params.searchTerm,
      async (newSearchTerm) => {
        ids.value = newSearchTerm;
        await fetchRegistrations();
      }
    );

    const openEditModal = (registration) => {
      selectedRegistration.value = registration;
      isEditModalOpen.value = true;
    };

    const closeEditModal = () => {
      isEditModalOpen.value = false;
      selectedRegistration.value = null;
    };

    const updateRegistration = (updatedData) => {
      registrationStore.updateRegistration(updatedData);
      closeEditModal();
    };

    const deleteRegistration = (id) => {
      registrationStore.deleteRegistration(id);
    };

    const registrations = computed(() => {
      return registrationStore.registrations;
    });

    return {
      registrations,
      openEditModal,
      closeEditModal,
      updateRegistration,
      deleteRegistration,
      isEditModalOpen,
      selectedRegistration,
      filterValue,
      isLoading,
    };
  },
};
</script>
