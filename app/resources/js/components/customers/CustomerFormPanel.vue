<template>
  <div class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
    <div class="bg-white w-full max-w-5xl rounded-lg shadow-lg p-4 relative">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-blue-600">
          Customer - {{ isEditMode ? 'Detail' : 'Create' }}
        </h2>
        <div class="flex items-center space-x-2">
          <button @click="goBack" class="text-gray-600 hover:text-gray-800">Back</button>
          <button @click="saveCustomer" class="bg-blue-600 text-white px-4 py-1 rounded-full">Save</button>
        </div>
      </div>

      <div v-if="confirmationMessage" class="mb-4 p-3 bg-green-100 text-green-800 border border-green-300 rounded">
        {{ confirmationMessage }}
      </div>
      <div v-if="errorMessage" class="mb-4 p-3 bg-red-100 text-red-800 border border-red-300 rounded">
        {{ errorMessage }}
      </div>

      <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="border p-4">
          <h3 class="font-semibold mb-2 text-blue-600">General</h3>
          <div class="mb-2">
            <label class="block mb-1">Name<span class="text-red-500">*</span></label>
            <input v-model="customer.name" type="text" class="border w-full px-2 py-1 rounded" required />
          </div>
          <div class="mb-2">
            <label class="block mb-1">Reference<span class="text-red-500">*</span></label>
            <input v-model="customer.reference" type="text" class="border w-full px-2 py-1 rounded" required />
          </div>
          <div class="mb-2">
            <label class="block mb-1">Category<span class="text-red-500">*</span></label>
            <select v-model="customer.category" class="border w-full px-2 py-1 rounded" required>
              <option value="">-- Select --</option>
              <option value="Gold">Gold</option>
              <option value="Silver">Silver</option>
              <option value="Bronze">Bronze</option>
            </select>
          </div>
        </div>

        <div class="border p-4">
          <h3 class="font-semibold mb-2 text-blue-600">Details</h3>
          <div class="mb-2">
            <label class="block mb-1">Start Date</label>
            <input v-model="customer.start_date" type="date" class="border w-full px-2 py-1 rounded" />
          </div>
          <div class="mb-2">
            <label class="block mb-1">Description</label>
            <textarea v-model="customer.description" class="border w-full px-2 py-1 rounded"></textarea>
          </div>
        </div>
      </div>

      <div class="border p-4">
        <div class="flex justify-between items-center mb-2">
          <h3 class="font-semibold text-blue-600">Contacts</h3>
          <button class="bg-blue-600 text-white px-3 py-1 rounded" :disabled="!enableContactButton" @click="openContactForm(null)">Create</button>
        </div>
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-gray-300">
              <th class="border px-2 py-1 text-left">First Name</th>
              <th class="border px-2 py-1 text-left">Last Name</th>
              <th class="border px-2 py-1 text-left">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(contact, index) in contacts" :key="contact.id" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-100'">
              <td class="border px-2 py-1">{{ contact.first_name }}</td>
              <td class="border px-2 py-1">{{ contact.last_name }}</td>
              <td class="border px-2 py-1">
                <a href="#" @click.prevent="openContactForm(contact)">Edit</a> |
                <a href="#" @click.prevent="confirmDeleteContact(contact)">Delete</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Contact Form Modal -->
      <div v-if="showContactModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
          <div class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
          <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-bold text-blue-600">
                Contacts - {{ selectedContact.id ? 'Detail' : 'Create' }}
              </h2>
              <div class="flex items-center space-x-2">
                <button @click="closeContactForm" class="text-gray-600 hover:text-gray-800">Back</button>
                <button @click="saveContact" class="bg-blue-600 text-white px-4 py-1 rounded-full">Save</button>
              </div>
            </div>

            <div class="border p-4">
              <h3 class="font-semibold mb-2 text-blue-600">General</h3>
              <div v-if="contactErrorMessage" class="mb-2 p-2 bg-red-100 text-red-800 border border-red-300 rounded">
                {{ contactErrorMessage }}
              </div>
              <div class="mb-2">
                <label class="block mb-1">First Name<span class="text-red-500">*</span></label>
                <input v-model="selectedContact.first_name" type="text" class="border w-full px-2 py-1 rounded" />
              </div>
              <div class="mb-2">
                <label class="block mb-1">Last Name</label>
                <input v-model="selectedContact.last_name" type="text" class="border w-full px-2 py-1 rounded" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Delete Confirmation -->
      <div v-if="contactToDelete" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-sm">
          <h3 class="text-lg font-semibold text-red-600 mb-4">Confirm Deletion</h3>
          <p>Are you sure you want to delete contact <strong>{{ contactToDelete.first_name }}</strong>?</p>
          <div class="flex justify-end gap-4 mt-4">
            <button @click="contactToDelete = null" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
            <button @click="deleteContact" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  props: {
    customerId: {
      type: [Number, String],
      default: null
    }
  },
  data() {
    return {
      customer: {
        name: '',
        reference: '',
        category: '',
        start_date: '',
        description: ''
      },
      localCustomerId: null,
      contacts: [],
      selectedContact: {},
      contactToDelete: null,
      showContactModal: false,
      isEditMode: false,
      confirmationMessage: '',
      errorMessage: '',
      contactErrorMessage: '',
      enableContactButton: false
    };
  },
  watch: {
    customerId: {
      immediate: true,
      handler(id) {
        this.localCustomerId = id;
        if (id) {
          this.isEditMode = true;
          this.enableContactButton = true;
          this.fetchCustomer(id);
        } else {
          this.isEditMode = false;
          this.enableContactButton = false;
          this.customer = {
            name: '', reference: '', category: '', start_date: '', description: ''
          };
          this.contacts = [];
        }
      }
    }
  },
  methods: {
    fetchCustomer(id) {
      axios.get(`/api/customers/${id}`).then(response => {
        this.customer = response.data;
        this.contacts = response.data.contacts || [];
      });
    },
    saveCustomer() {
      this.errorMessage = '';
      if (!this.customer.name || !this.customer.reference || !this.customer.category) {
        alert('Name, Reference, and Category are required.');
        return;
      }
      const request = this.isEditMode
        ? axios.put(`/api/customers/${this.localCustomerId}`, this.customer)
        : axios.post('/api/customers', this.customer);

      request
        .then(response => {
          const id = this.isEditMode ? this.localCustomerId : response.data.id;
          if (!this.isEditMode) {
            this.isEditMode = true;
            this.localCustomerId = id;
            this.enableContactButton = true;
            this.$emit('saved', id);
          }
          this.confirmationMessage = this.isEditMode ? 'Customer updated successfully' : 'Customer created successfully';
          setTimeout(() => this.confirmationMessage = '', 3000);
          this.fetchCustomer(id);
        })
        .catch(error => {
          if (error.response?.data?.message) {
            this.errorMessage = error.response.data.message;
            setTimeout(() => this.errorMessage = '', 3000);
          }
        });
    },
    goBack() {
      this.$emit('close');
    },
    openContactForm(contact) {
      this.selectedContact = contact ? { ...contact } : { first_name: '', last_name: '' };
      this.contactErrorMessage = '';
      this.showContactModal = true;
    },
    closeContactForm() {
      this.selectedContact = {};
      this.showContactModal = false;
    },
    saveContact() {
      const customerId = this.localCustomerId;
      this.contactErrorMessage = '';
      if (!this.selectedContact.first_name || !this.selectedContact.last_name) {
        this.contactErrorMessage = 'First Name and Last Name are required.';
        return;
      }
      const request = this.selectedContact.id
        ? axios.put(`/api/contacts/${this.selectedContact.id}`, this.selectedContact)
        : axios.post(`/api/customers/${customerId}/contacts`, this.selectedContact);

      request
        .then(() => {
          this.fetchCustomer(customerId);
          this.confirmationMessage = this.selectedContact.id ? 'Contact updated successfully' : 'Contact created successfully';
          setTimeout(() => this.confirmationMessage = '', 3000);
          this.closeContactForm();
        })
        .catch(() => {
          this.contactErrorMessage = this.selectedContact.id ? 'Failed to update contact.' : 'Failed to create contact.';
        });
    },
    confirmDeleteContact(contact) {
      this.contactToDelete = contact;
    },
    deleteContact() {
      const customerId = this.localCustomerId;
      axios.delete(`/api/contacts/${this.contactToDelete.id}`)
        .then(() => {
          this.fetchCustomer(customerId);
          this.contactToDelete = null;
          this.confirmationMessage = 'Contact deleted successfully';
          setTimeout(() => this.confirmationMessage = '', 3000);
        })
        .catch(() => {
          this.confirmationMessage = 'Failed to delete contact.';
          setTimeout(() => this.confirmationMessage = '', 3000);
        });
    }
  }
};
</script>

<style scoped>
button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
