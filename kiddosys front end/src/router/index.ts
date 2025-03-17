import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/useAuthStore';
import UserView from '@/views/user/UserView.vue';
import AdminView from '@/views/admin/AdminView.vue';
import LoginView from '@/views/LoginView.vue';
import SearchView from '@/views/BookingView.vue';



const routes = [

  //globalroute
  {
    path: '/',
    name: 'login',
    component: LoginView,
    meta: { requiresAuth: false },
  },
  {
    path: '/reserve',
    name: 'reserve',
    component: UserView,
    meta: { requiresAuth: false },
  },

  {
    path: '/admin',
    name: 'admin',
    component: AdminView,
    meta: { requiresAuth: true, roles: ['admin'] },

  },

  {
    path: '/search/:searchTerm',
    name: 'search',
    component: SearchView,
    meta: { requiresAuth: false },
  }

];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});


 router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();
  authStore.checkAuth();

  const isAuthenticated = authStore.isAuthenticated;

    try {

      if (to.meta.requiresAuth && !isAuthenticated) {
        next({ name: 'reserve' });
      } else if (to.name === 'login' && isAuthenticated) {
        next({ name: 'admin' });
      }  else {
        next();
      }
    } catch (error) {
      console.error('Error fetching verifications:', error);
      next({ name: 'reserve' }); // Redirect to dashboard or handle error appropriately
    }
  }
);

export default router;




