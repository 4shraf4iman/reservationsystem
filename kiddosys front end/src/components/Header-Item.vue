<template>
    <header class="antialiased fixed">
      <nav class="bg-white border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800">
        <div class="flex flex-wrap justify-between items-center">
          <button data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation" aria-controls="drawer-navigation" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
            </svg>
          </button>
          <div class="flex justify-start items-center hidden md:flex ">
            <router-link to="/reserve" class="flex mr-4 m-1">
              Kiddosys
            </router-link>
          </div>

          <div class="flex items-center lg:order-2">
            <template v-if="isAuthenticated">
              <a
                ref="buttonRef"
                type="button"
                class="flex mx-3 text-sm text-blue-500 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                @click="toggleDropdown"
              >
                <span class="sr-only">Open user menu</span>
                Admin User
              </a>
            </template>

            <template v-else>
              <form class="max-w-md mx-auto mt-4" @submit.prevent="search">
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                <div class="relative">
                  <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                  </div>
                  <input type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search by Booking ID or No Phone ..." required style="width: 400px;" v-model="searchTerm" />
                  <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search </button>
                </div>
              </form>&nbsp;&nbsp;&nbsp;
              <router-link to="/" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mt-4">
                Login
              </router-link>
            </template>
          </div>
        </div>
      </nav>

      <div
        v-if="isActive"
        ref="dropdownRef"
        class="float-right z-50 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
      >
        <div class="py-3 px-4">
          <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ user?.name }}</span>
          <p class="block text-sm text-gray-500 truncate dark:text-gray-400" style="font-size:10px">{{ user?.email }}</p>
        </div>
        <ul class="py-1 text-gray-500 dark:text-gray-400" aria-labelledby="dropdown">
          <li>
            <router-link @click="closeDropdown" to="#" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">
              My profile
            </router-link>
          </li>
          <li>
            <router-link @click="closeDropdown" to="#" class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">
              Account settings
            </router-link>
          </li>
          <li>
            <a
              href="#"
              @click.prevent="logout"
              class="block py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white"
            >
              Sign out
            </a>
          </li>
        </ul>
      </div>
    </header>
  </template>

  <script lang="ts">
  import { defineComponent, computed, ref, watch, onMounted, onUnmounted } from 'vue';
  import { useRouter, RouterLink } from 'vue-router';
  import { useAuthStore } from '@/stores/useAuthStore';

  export default defineComponent({
    name: 'HeaderItem',
    components: {
      RouterLink,
    },
    setup() {
      const router = useRouter();
      const authStore = useAuthStore();
      const isActive = ref(false);
      const dropdownRef = ref<HTMLElement | null>(null);
      const buttonRef = ref<HTMLElement | null>(null);
      const searchTerm = ref('');

      const user = computed(() => authStore.user);
      const isAuthenticated = computed(() => authStore.isAuthenticated);

      const logout = () => {
        authStore.logout();
        router.push('/');
        closeDropdown();
      };

      const toggleDropdown = (event: MouseEvent) => {
        event.stopPropagation();
        isActive.value = !isActive.value;
      };

      const closeDropdown = () => {
        isActive.value = false;
      };

      const search = () => {
        router.push({ name: 'search', params: { searchTerm: searchTerm.value } });
      };

      const handleClickOutside = (event: MouseEvent) => {
        if (
          isActive.value &&
          dropdownRef.value &&
          !dropdownRef.value.contains(event.target as Node) &&
          buttonRef.value &&
          !buttonRef.value.contains(event.target as Node)
        ) {
          closeDropdown();
        }
      };

      onMounted(() => {
        document.addEventListener('click', handleClickOutside);
      });

      onUnmounted(() => {
        document.removeEventListener('click', handleClickOutside);
      });

      watch(
        () => authStore.isAuthenticated,
        (isAuthenticated) => {
          if (!isAuthenticated) {
            router.push('/');
          }
        }
      );

      return {
        user,
        isAuthenticated,
        isActive,
        logout,
        toggleDropdown,
        closeDropdown,
        dropdownRef,
        buttonRef,
        searchTerm,
        search,
      };
    },
  });
  </script>

  <style scoped>
  /* Add your styles here */
  </style>
