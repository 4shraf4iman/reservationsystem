import { defineStore } from 'pinia';
import apiClient from '@/services.ts'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    isAuthenticated: !!localStorage.getItem('user'),
  }),
  actions: {
    async login(email, password) {
      try {
        console.log('Attempting login with:', { email, password });
        const response = await apiClient.post('/api/login', { email, password });
        console.log('Login response:', response);
        
        if (response.data.user) {
          // Store the entire user object from the API
          this.user = response.data.user;
          console.log('hello',this.user);
          
          
          if (!this.user.uuid) {
            this.user.uuid = this.user.uuid;
          }

          this.isAuthenticated = true;
    
          // Save user data to localStorage
          localStorage.setItem('user', JSON.stringify(this.user));
          
          console.log('Login successful, user:', this.user);
          return this.user;
        } else {
          console.log('Login failed: User data not received');
          throw new Error('Login failed: User data not received');
        }
      } catch (error) {
        console.error('Login error:', error);
        console.log('Error response:', error.response);
        if (error.response && error.response.data && error.response.data.error) {
          throw new Error(error.response.data.error);
        } else {
          throw new Error('Login failed: An unexpected error occurred');
        }
      }
    },
    logout() {
      this.user = null;
      this.isAuthenticated = false;
      localStorage.removeItem('user');
    },
    checkAuth() {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        this.user = JSON.parse(storedUser);
        this.isAuthenticated = true;
        console.log('User authenticated from localStorage:', this.user);
      } else {
        this.user = null;
        this.isAuthenticated = false;
        console.log('No user found in localStorage');
      }
    },
  },
});
