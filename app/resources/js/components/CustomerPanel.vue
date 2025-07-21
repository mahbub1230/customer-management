<template>
  <div>
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold text-blue-700">Customers</h2>
      <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Create
      </button>
    </div>

    <div class="bg-gray-50 p-4 rounded border mb-4">
      <div class="flex gap-4 items-center">
        <div>
          <label class="block text-sm font-medium">Search</label>
          <input
            type="text"
            v-model="search"
            class="border rounded px-2 py-1"
            placeholder="Search by name or reference"
          />
        </div>
        <div>
          <label class="block text-sm font-medium">Category</label>
          <select v-model="category" class="border rounded px-2 py-1">
            <option value="">Select...</option>
            <option value="Gold">Gold</option>
            <option value="Silver">Silver</option>
            <option value="Bronze">Bronze</option>
          </select>
        </div>
        <button @click="applyFilter" class="bg-blue-600 text-white px-3 py-1 rounded">
          Apply
        </button>
        <button @click="resetFilter" class="bg-gray-300 px-3 py-1 rounded">
          Clear
        </button>
      </div>
    </div>

    <table class="min-w-full bg-white border rounded text-sm">
      <thead class="bg-gray-200 text-left">
        <tr>
          <th class="px-4 py-2 border">Name</th>
          <th class="px-4 py-2 border">Reference</th>
          <th class="px-4 py-2 border">Category</th>
          <th class="px-4 py-2 border">No of Contacts</th>
          <th class="px-4 py-2 border">Edit | Delete</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="customer in customers" :key="customer.id" class="odd:bg-gray-50">
          <td class="px-4 py-2 border">{{ customer.name }}</td>
          <td class="px-4 py-2 border">{{ customer.reference }}</td>
          <td class="px-4 py-2 border">{{ customer.category }}</td>
          <td class="px-4 py-2 border">{{ customer.contacts_count }}</td>
          <td class="px-4 py-2 border">
            <a href="#" class="text-blue-600 hover:underline">Edit</a> |
            <a href="#" @click.prevent="confirmDelete(customer)" class="text-red-600 hover:underline">Delete</a>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteConfirm"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
      <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
        <h3 class="text-lg font-semibold text-red-600 mb-4">Confirm Deletion</h3>
        <p>
          Are you sure you want to delete <strong>{{ customerToDelete.name }}</strong> and all its
          associated contacts?
        </p>
        <div class="mt-6 flex justify-end gap-4">
          <button @click="cancelDelete" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
          <button @click="deleteCustomer" class="px-4 py-2 bg-red-600 text-white rounded">
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CustomerPanel',
  data() {
    return {
      customers: [],
      search: '',
      category: '',
      showDeleteConfirm: false,
      customerToDelete: null,
    };
  },
  methods: {
    fetchCustomers() {
      axios
        .get('/api/customers', {
          params: {
            search: this.search,
            category: this.category,
          },
        })
        .then((response) => {
          this.customers = response.data;
        })
        .catch((error) => {
          console.error('Failed to load customers:', error);
        });
    },
    applyFilter() {
      this.fetchCustomers();
    },
    resetFilter() {
      this.search = '';
      this.category = '';
      this.fetchCustomers();
    },
    confirmDelete(customer) {
      this.customerToDelete = customer;
      this.showDeleteConfirm = true;
    },
    cancelDelete() {
      this.customerToDelete = null;
      this.showDeleteConfirm = false;
    },
    deleteCustomer() {
      axios
        .delete(`/api/customers/${this.customerToDelete.id}`)
        .then(() => {
          this.fetchCustomers();
          this.showDeleteConfirm = false;
          this.customerToDelete = null;
        })
        .catch((error) => {
          console.error('Failed to delete customer:', error);
        });
    },
  },
  mounted() {
    this.fetchCustomers();
  },
};
</script>
