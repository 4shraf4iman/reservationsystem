<template>
  <button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle="sidebar-multi-level-sidebar" aria-controls="sidebar-multi-level-sidebar" type="button" class=" inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
    </svg>
  </button>

  <aside id="sidebar-multi-level-sidebar" class="hidden lg:block fixed top-0 left-0 z-3 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-4 py-6 overflow-y-auto bg-gray-900 dark:bg-gray-800">
      <div class="items-center p-2 rounded-lg dark:text-white mt-5 mb-5 m-auto"></div>
      <ul class="space-y-2 font-medium">
        <li v-for="(item, index) in filteredMenuItems" :key="index">
          <router-link :to="item.href" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
            <font-awesome-icon :icon="item.icon" class="mr-2 w-4 h-4 text-white transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" />
            <span class="item-label text-white group-hover:text-gray-900">{{ item.label }}</span>
          </router-link>
        </li>
      </ul>
    </div>
  </aside>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue';
import { useAuthStore } from '@/stores/useAuthStore';

export default defineComponent({
  setup() {
    const authStore = useAuthStore(); // Access the auth store

    const menuItems = [
      { label: 'Appointment', href: '/admin', icon: 'file-invoice' },
    ];

    const filteredMenuItems = computed(() => {
      const userRole = authStore.user ? authStore.user.role : null;

      return menuItems.filter(item => {
        if (!item.role) return true;
        if (Array.isArray(item.role)) {
          return item.role.includes(userRole);
        }
        return userRole === item.role;
      });
    });

    return {
      filteredMenuItems,
    };
  },
});
</script>

<style scoped>
/* Add styles here */
</style>
