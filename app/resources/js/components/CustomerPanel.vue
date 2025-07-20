<template>
  <div class="bg-white shadow p-6 rounded-md">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-blue-600">Customers</h2>
      <button class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">Create</button>
    </div>

    <div class="flex space-x-4 mb-4">
      <div class="flex flex-col">
        <label class="text-sm text-gray-600">Search</label>
        <input type="text" class="border rounded px-2 py-1" placeholder="Search..." />
      </div>
      <div class="flex flex-col">
        <label class="text-sm text-gray-600">Category</label>
        <select class="border rounded px-2 py-1">
          <option value="">Select...</option>
          <option value="Gold">Gold</option>
          <option value="Silver">Silver</option>
          <option value="Bronze">Bronze</option>
        </select>
      </div>
      <div class="flex items-end space-x-2">
        <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Clear</button>
        <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Apply</button>
      </div>
    </div>

    <table class="w-full border text-sm">
      <thead>
        <tr class="bg-gray-200 text-left">
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Reference</th>
          <th class="p-2 border">Category</th>
          <th class="p-2 border">No of Contacts</th>
          <th class="p-2 border">Edit | Delete</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <tr class="odd:bg-gray-50" v-for="(customer, index) in customers" :key="index">
          <td class="p-2 border">{{ customer.name }}</td>
          <td class="p-2 border">{{ customer.reference }}</td>
          <td class="p-2 border">{{ customer.category }}</td>
          <td class="p-2 border">{{ customer.contacts }}</td>
          <td class="p-2 border">Edit | Delete</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
const customers = [
  { name: 'Customer 1', reference: 'CUST1', category: 'Gold', contacts: 4 },
  { name: 'Customer 2', reference: 'CUST2', category: 'Silver', contacts: 1 },
];
</script>
