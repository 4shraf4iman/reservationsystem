import { defineStore } from 'pinia';
import apiClient from '@/services';

interface Registration {
  id: string;
  customer_name: string;
  phone_number: string;
  address: string;
  children: any[];
  reservation_datetime: string;
  booking_no: string;
}

interface RegistrationState {
  registrations: Registration[];
  loading: boolean;
  error: string | null;
}

export const useRegistrationStore = defineStore('registration', {
  state: (): RegistrationState => ({
    registrations: [],
    loading: false,
    error: null,
  }),
  actions: {
    async fetchRegistrations() {
      this.loading = true;
      this.error = null;
      try {
        const response = await apiClient.get('/api/registrations');
        this.registrations = response.data;
      } catch (error) {
        this.error = error.message || 'An error occurred while fetching registrations';
      } finally {
        this.loading = false;
      }
    },

    async fetchRegistrationById(id: string) {
      this.loading = true;
      this.error = null;
      try {
        const response = await apiClient.get(`/api/registrations/${id}`);
        return response.data;
      } catch (error) {
        this.error = error.message || 'An error occurred while fetching the registration';
        throw this.error;
      } finally {
        this.loading = false;
      }
    },

    async fetchRegistrationByCustomerPhone(id: string) {
        this.loading = true;
        this.error = null;
        try {
          const response = await apiClient.get(`/api/registrations/phone/${id}`);
          this.registrations = response.data; // Update the registrations property
          return response.data;
        } catch (error) {
          this.error = error.message || 'An error occurred while fetching the registration';
          throw this.error;
        } finally {
          this.loading = false;
        }
      },

      async fetchRegistrationByBookingId(id: string) {
        this.loading = true;
        this.error = null;
        try {
          const response = await apiClient.get(`/api/registrations/booking/${id}`);
          return response.data;
        } catch (error) {
          this.error = error.message || 'An error occurred while fetching the registration';
          throw this.error;
        } finally {
          this.loading = false;
        }
      },

    async createRegistration(registrationData: Registration) {
      this.loading = true;
      this.error = null;
      try {
        const response = await apiClient.post('/api/registrations', registrationData);
        this.registrations.push(response.data);
        return response.data; // Return the response data
      } catch (error) {
        this.error = error.message || 'An error occurred while creating the registration';
        throw error; // Re-throw the error
      } finally {
        this.loading = false;
      }
    },

    async updateRegistration(id: string, registrationData: Registration) {
      this.loading = true;
      this.error = null;
      try {
        const response = await apiClient.post(`/api/registrations/${id}`, registrationData);
        const index = this.registrations.findIndex(registration => registration.id === id);
        if (index !== -1) {
          this.registrations[index] = response.data;
        }
      } catch (error) {
        this.error = error.message || 'An error occurred while updating the registration';
        throw this.error;
      } finally {
        this.loading = false;
      }
    },

    async deleteRegistration(id: string) {
      this.loading = true;
      this.error = null;
      try {
        await apiClient.delete(`/api/registrations/${id}`);
        this.registrations = this.registrations.filter(registration => registration.id !== id);
      } catch (error) {
        this.error = error.message || 'An error occurred while deleting the registration';
        throw this.error;
      } finally {
        this.loading = false;
      }
    },
  },
});
