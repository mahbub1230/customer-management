<template>
  <div>
    <div class="flex justify-between items-center mb-2">
      <h2 class="text-lg font-bold text-blue-700">Customers</h2>
      <button @click="createCustomer" class="bg-blue-600 text-white px-4 py-1 rounded">Create</button>
    </div>

    <div class="border rounded p-3 mb-4">
      <div class="grid grid-cols-[auto_auto_auto_1fr] gap-x-4 items-end">
        <div>
          <label class="block text-sm font-semibold">Search</label>
          <input
            type="text"
            v-model="search"
            class="border rounded px-2 py-1 w-40"
            placeholder="Search"
          />
        </div>
        <div>
          <label class="block text-sm font-semibold">Category</label>
          <select v-model="category" class="border rounded px-2 py-1 w-40">
            <option value="">[...Select...]</option>
            <option value="Gold">Gold</option>
            <option value="Silver">Silver</option>
            <option value="Bronze">Bronze</option>
          </select>
        </div>
        <div class="flex gap-2">
          <button @click="resetFilter" class="bg-gray-300 text-black px-4 py-1 rounded">Clear</button>
          <button @click="applyFilter" class="bg-blue-600 text-white px-4 py-1 rounded">Apply</button>
        </div>
      </div>
    </div>

    <table class="w-full text-sm border border-collapse text-center">
      <thead class="bg-gray-300">
        <tr>
          <th class="border px-4 py-2 align-middle">Name</th>
          <th class="border px-4 py-2 align-middle">Reference</th>
          <th class="border px-4 py-2 align-middle">Category</th>
          <th class="border px-4 py-2 align-middle">No of Contacts</th>
          <th class="border px-4 py-2 align-middle">Edit | Delete</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(customer, index) in customers" :key="customer.id" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-100'">
          <td class="border px-4 py-2 align-middle">{{ customer.name }}</td>
          <td class="border px-4 py-2 align-middle">{{ customer.reference }}</td>
          <td class="border px-4 py-2 align-middle">{{ customer.category }}</td>
          <td class="border px-4 py-2 align-middle">{{ customer.contacts_count }}</td>
          <td class="border px-4 py-2 align-middle">
            <a href="#" @click.prevent="editCustomer(customer.id)" class="text-blue-600 hover:underline">Edit</a> |
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

    <CustomerFormPanel
      v-if="showCustomerForm"
      :customer-id="selectedCustomerId"
      @close="closeCustomerForm"
    />
  </div>
</template>

<script>
import axios from 'axios';
import CustomerFormPanel from './CustomerFormPanel.vue';

export default {
  name: 'CustomerPanel',
  components: {
    CustomerFormPanel
  },
  data() {
    return {
      customers: [],
      search: '',
      category: '',
      showDeleteConfirm: false,
      customerToDelete: null,
      showCustomerForm: false,
      selectedCustomerId: null
    };
  },
  methods: {
    fetchCustomers() {
      axios
        .get('/api/customers', {
          params: {
            search: this.search.trim(),
            category: this.category.trim(),
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
    createCustomer() {
      this.selectedCustomerId = null;
      this.showCustomerForm = true;
    },
    editCustomer(customerId) {
      this.selectedCustomerId = customerId;
      this.showCustomerForm = true;
    },
    closeCustomerForm() {
      this.showCustomerForm = false;
      this.fetchCustomers();
    }
  },
  mounted() {
    this.fetchCustomers();
  },
};
</script>
