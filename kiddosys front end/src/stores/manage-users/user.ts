// src/stores/user.ts
import { defineStore } from 'pinia';
import apiClient from '@/services';
import { useAuthStore } from '@/stores/useAuthStore';

export const useUserStore = defineStore('user', {
  state: () => ({
    users: [],
    specificUser: null,
    loading: false,
    error: null,
  }),
  actions: {

    // fetch user 
    async fetchUsers() {
      this.loading = true;
      this.error = null;
      const authStore = useAuthStore();
      try {
        if (!authStore.user || !authStore.user.role) {
          throw new Error('User not authenticated or role not available');
        }
        console.log('Fetching users with requester role:', authStore.user.role);
        const response = await apiClient.get('/api/users', {
          headers: {
            'X-User-Role': authStore.user.role,
          }
        });
        console.log('Fetched users:', response.data);
        this.users = response.data.users;
      } catch (error) {
        console.error('Error fetching users:', error);
        this.error = error.message || 'An error occurred while fetching users';
      } finally {
        this.loading = false;
      }
    },

    // Fetch specific user by ID
    async fetchUserById(userId: string) {
      this.loading = true;
      this.error = null;
      const authStore = useAuthStore();
      try {
        if (!authStore.user || !authStore.user.role) {
          throw new Error('User not authenticated or role not available');
        }
        
        // Make the GET request
        const response = await apiClient.get(`/api/users/${userId}`, {
          headers: {
            'X-User-Role': authStore.user.role,
          }
        });

        console.log('Fetched user:', response.data);
        this.specificUser = response.data.user; // Update specific user data
        return this.specificUser;

      } catch (error) {
        console.error('Error fetching specific user:', error);
        this.error = error.message || 'An error occurred while fetching the user';
        throw this.error;
      } finally {
        this.loading = false;
      }
    },


    // create user 

    async createUser(userData :any ) {
      this.loading = true;
      this.error = null;
      const authStore = useAuthStore(); // Get the auth store
      try {
        if (!authStore.user || !authStore.user.role) {
          throw new Error('User not authenticated or role not available');
        }
        const response = await apiClient.post('/api/add-user', userData, {
          headers: {
            'X-User-Role': authStore.user.role,
          }
        });
        this.users.push(response.data.user );
      } catch (error) {
        console.error('Error creating user:', error);
        this.error = error.message || 'An error occurred while creating the user';
      } finally {
        this.loading = false;
      }
    },

    //delete user

    async deleteUser(uuid: string) {
        this.loading = true;
        this.error = null;
        const authStore = useAuthStore();
        try {
          if (!authStore.user || !authStore.user.role || !authStore.user.email) {
            throw new Error('User not authenticated or role/email not available');
          }
  
          const response = await apiClient.post(`/api/users/${uuid}/delete`, {}, {
            headers: {
              'X-User-Role': authStore.user.role,
              'X-User-Email': authStore.user.email,
            }
          });
  
          console.log('Delete response:', response);
          if (response.status === 200) {
            // Use the full UUID for filtering
            this.users = this.users.filter(user => user.uuid !== uuid);
            return true;
          } else {
            throw new Error(response.data.error || 'Failed to delete user');
          }
        } catch (error) {
          console.error('Error deleting user:', error);
          console.error('Error response:', error.response);
          if (error.response && error.response.status === 403) {
            this.error = 'You do not have permission to delete this user';
          } else {
            this.error = error.message || 'An error occurred while deleting the user';
          }
          throw this.error;
        } finally {
          this.loading = false;
        }
      },


    async updateUser(uuid:string, userData:any) {
      this.loading = true;
      this.error = null;
      const authStore = useAuthStore();
      try {
        const response = await apiClient.post(`/api/users/${uuid}`, userData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            'X-User-Role': authStore.user.role,
            'X-User-UUID': authStore.user.uuid
          },
        });
      
        
        // Update the user in the users array if it exists
        const index = this.users.findIndex(user => user.uuid === uuid);
        if (index !== -1) {
          this.users[index] = { ...this.users[index], ...response.data.user };
        }
        
        return response.data.user;
      } catch (error) {
        console.error('Error updating user:', error);
        this.error = error.message || 'An error occurred while updating the user';
        throw error;
      } finally {
        this.loading = false;
      }
    },
  },
});
