<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="pb-4 p-4 bg-white dark:bg-gray-900">
      <label for="table-search" class="sr-only">Search</label>
      <div class="relative mt-1">
        <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
          <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
          </svg>
        </div>
        <input type="text" id="table-search" class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items" v-model="filterValue">
      </div>
    </div>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="p-4">
            <div class="flex items-center">
              <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              <label for="checkbox-all-search" class="sr-only">checkbox</label>
            </div>
          </th>
          <th scope="col" class="px-6 py-3">Customer Name</th>
          <th scope="col" class="px-6 py-3">Phone Number</th>
          <th scope="col" class="px-6 py-3">Address</th>
          <th scope="col" class="px-6 py-3">Children</th>
          <th scope="col" class="px-6 py-3">Reservation Datetime</th>
          <th scope="col" class="px-6 py-3">Booking No</th>
          <th scope="col" class="px-6 py-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(registration, index) in filteredRegistrations" :key="registration.reservation_datetime" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
          <td class="w-4 p-4">
            <div class="flex items-center">
              <input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
            </div>
          </td>
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ registration.customer_name }}</th>
          <td class="px-6 py-4">{{ registration.phone_number }}</td>
          <td class="px-6 py-4">{{ registration.address }}</td>
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
          <td class="px-6 py-4">{{ formattedDateTime[index] }}</td>
          <td class="px-6 py-4">{{ registration.booking_no }}</td>
          <td class="px-6 py-4">
            <a @click="openEditModal(registration)" class="text-blue-600 hover:underline">Edit</a>
            <a @click="deleteRegistration(registration.id)" class="text-red-600 hover:underline">Delete</a>
          </td>
        </tr>
      </tbody>
    </table>
    <edit-modal v-if="isEditModalOpen" :registration="selectedRegistration" @close="closeEditModal" @update="updateRegistration" />
  </div>
</template>

<script>
import { useRegistrationStore } from '@/stores/manage-registration/registration';
import { computed, ref } from 'vue';
import EditModal from '@/components/EditModal.vue';

export default {
  components: {
    EditModal,
  },
  setup() {
    const registrationStore = useRegistrationStore();
    const isEditModalOpen = ref(false);
    const selectedRegistration = ref(null);
    const filterValue = ref('');

    // Fetch registrations on load
    registrationStore.fetchRegistrations();

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

    const registrations = computed(() => registrationStore.registrations);

    const filteredRegistrations = computed(() => {
      return registrations.value.filter(registration => {
        const filterValueLower = filterValue.value.toLowerCase();
        return Object.values(registration).some(value => {
          if (typeof value === 'string') {
            return value.toLowerCase().includes(filterValueLower);
          } else if (Array.isArray(value)) {
            return value.some(item => {
              if (typeof item === 'object') {
                return Object.values(item).some(val => val.toString().toLowerCase().includes(filterValueLower));
              } else {
                return item.toString().toLowerCase().includes(filterValueLower);
              }
            });
          } else {
            return value.toString().toLowerCase().includes(filterValueLower);
          }
        });
      });
    });

    const formattedDateTime = computed(() => {
      return registrations.value.map(registration => {
        const dateTime = new Date(registration.reservation_datetime);
        return `${dateTime.toLocaleDateString()} ${dateTime.toLocaleTimeString()}`;
      });
    });

    return {
      registrations,
      filteredRegistrations,
      formattedDateTime,
      openEditModal,
      closeEditModal,
      updateRegistration,
      deleteRegistration,
      isEditModalOpen,
      selectedRegistration,
      filterValue,
    };
  },
};
</script>
