<template>
  <div id="app" class="flex flex-col min-h-screen bg-gray-100 relative">
    <!-- Full-width Header -->
    <HeaderItem class="w-full z-2" />

    <div class="flex-1">
      <Sidebar
        v-if="isAuthenticated"
        class="w-64 bg-gray-800 text-white h-full flex-shrink-0 z-0 relative"
      />


      <!-- Main content area -->
      <div :class="isAuthenticated ? 'lg:ml-64 mt-20' : ''">
        <section class="bg-gray-10 p-6 rounded-lg mt-10"><router-view class="flex-1" /></section>
      </div>
    </div>

  </div>
</template>

<script lang="ts">
import { defineComponent, computed, ref, watch } from 'vue';
import { useAuthStore } from '@/stores/useAuthStore';
import HeaderItem from '@/components/Header-Item.vue';
import Sidebar from '@/components/Sidebar-Item.vue';


export default defineComponent({
  components: {
    HeaderItem,
    Sidebar,
  },
  setup() {
    const authStore = useAuthStore();
    const isAuthenticated = computed(() => authStore.isAuthenticated);
    const isModalVisible = ref(false);



    const closeModal = () => {
      isModalVisible.value = false;
      eventBus.isModalVisible = false;
    };

    return { isAuthenticated, isModalVisible, closeModal };
  },
});
</script>

<style>
html, body {
  margin: 0;
  padding: 0;
  font-family: 'Inter', sans-serif;
}

#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  width: 100%;
}

aside {
  min-width: 16rem; /* Sidebar width */
  background-color: #1f2937; /* Dark gray from Tailwind's gray-800 */
}

.container2 {
  background-image: linear-gradient(45deg, #5b0202 20%, #ed2e2e 40%, #c50000 50%);
  background-repeat: no-repeat;
  background-attachment: fixed;
}

main {
  flex: 1;
  display: flex;
  flex-direction: column;
}

/* Header specific styles */
header {
  z-index: 20; /* Ensure header stays in front */
  position: relative; /* Allows z-index to take effect */
}

@media (min-width: 768px) {
  main {
    padding: 1.5rem;
  }
}
</style>
