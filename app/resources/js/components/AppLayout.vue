<template>
  <div class="flex">
    <!-- Sidebar -->
    <div class="w-64 bg-white border-r border-gray-300 min-h-screen">
      <div class="p-4 border-b border-gray-300">
        <h1 class="text-blue-600 font-bold text-xl">slipstream</h1>
        <p class="text-gray-400 text-sm">TMS Tenant</p>
      </div>
      <div class="p-4 border-b border-gray-300">
        <p class="text-sm font-semibold mb-2">Demo Menu</p>
        <ul>
          <li class="mb-2">
            <router-link
              to="/customers"
              class="flex items-center space-x-2 hover:text-blue-600"
            >
              <span>📄</span>
              <span class="font-semibold">Customers</span>
            </router-link>
          </li>
        </ul>
      </div>
    </div>

    <!-- Content Panel -->
    <div class="flex-1 p-4">
      <router-view></router-view>
    </div>
  </div>
</template>
