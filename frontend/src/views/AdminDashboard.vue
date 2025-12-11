<template>
  <div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg fixed h-full">
      <div class="p-6">
        <h2 class="text-2xl font-bold text-red-600 mb-8">Admin Panel</h2>
        
        <nav class="space-y-2">
          <button
            @click="activeTab = 'users'"
            :class="[
              'w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors',
              activeTab === 'users' 
                ? 'bg-red-600 text-white' 
                : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="font-medium">User Management</span>
          </button>

          <button
            @click="activeTab = 'memberships'"
            :class="[
              'w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors whitespace-nowrap',
              activeTab === 'memberships' 
                ? 'bg-red-600 text-white' 
                : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
            </svg>
            <span class="font-medium">Membership Renewal</span>
          </button>

          <button
            @click="activeTab = 'payments'"
            :class="[
              'w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors',
              activeTab === 'payments' 
                ? 'bg-red-600 text-white' 
                : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium">Pending Payments</span>
          </button>

          <button
            @click="activeTab = 'sessions'"
            :class="[
              'w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors',
              activeTab === 'sessions' 
                ? 'bg-red-600 text-white' 
                : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="font-medium">Sessions</span>
          </button>

          <button
            @click="activeTab = 'merchandise'"
            :class="[
              'w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors',
              activeTab === 'merchandise' 
                ? 'bg-red-600 text-white' 
                : 'text-gray-700 hover:bg-gray-100'
            ]"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="font-medium">Merchandise</span>
          </button>

          <hr class="my-4 border-gray-200">

          <button
            @click="goToHome"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-medium">Back to Home</span>
          </button>

          <button
            @click="handleLogout"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="font-medium">Logout</span>
          </button>
        </nav>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8">
      <!-- User Management Tab -->
      <div v-if="activeTab === 'users'">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">User Management</h1>
          <p class="text-gray-600">Manage all users and their accounts</p>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by name or email..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
              <select
                v-model="filterStatus"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
              >
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="bg-white rounded-lg shadow-sm p-12 text-center">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-red-600"></div>
          <p class="mt-4 text-gray-600">Loading users...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <p class="text-red-800">{{ error }}</p>
      </div>

      <!-- Users Table -->
      <div v-else class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in filteredUsers" :key="user._id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 bg-red-600 rounded-full flex items-center justify-center text-white font-bold">
                    {{ user.name.charAt(0).toUpperCase() }}
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">{{ user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="{
                    'bg-purple-100 text-purple-800': user.role === 'admin',
                    'bg-blue-100 text-blue-800': user.role === 'player'
                  }"
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                >
                  {{ user.role }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ user.phone || 'N/A' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="{
                    'bg-green-100 text-green-800': user.is_active,
                    'bg-gray-100 text-gray-800': !user.is_active
                  }"
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                >
                  {{ user.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button
                    @click="toggleUserStatus(user)"
                    :disabled="updatingUser === user._id"
                    :class="user.is_active 
                      ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' 
                      : 'bg-green-100 text-green-700 hover:bg-green-200'"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <svg v-if="user.is_active" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ user.is_active ? 'Deactivate' : 'Activate' }}
                  </button>
                  <button
                    @click="openEditModal(user)"
                    class="px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-xs font-medium transition-colors flex items-center gap-1"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                  </button>
                  <button
                    @click="confirmDelete(user)"
                    :disabled="deletingUser === user._id"
                    class="px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg text-xs font-medium transition-colors flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Empty State -->
        <div v-if="filteredUsers.length === 0" class="text-center py-12">
          <p class="text-gray-500">No users found matching your filters.</p>
        </div>
      </div>

      <!-- Edit Modal -->
      <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 animate-fade-in">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Edit User</h2>
          
          <form @submit.prevent="updateUser">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input
                  v-model="editForm.name"
                  type="text"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input
                  v-model="editForm.email"
                  type="email"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input
                  v-model="editForm.phone"
                  type="tel"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select
                  v-model="editForm.role"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                >
                  <option value="player">Player</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
            </div>

            <div class="mt-6 flex space-x-3">
              <button
                type="submit"
                :disabled="updatingUser"
                class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50"
              >
                {{ updatingUser ? 'Saving...' : 'Save Changes' }}
              </button>
              <button
                type="button"
                @click="closeEditModal"
                class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 animate-fade-in">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Confirm Delete</h2>
          <p class="text-gray-600 mb-6">
            Are you sure you want to delete <strong>{{ userToDelete?.name }}</strong>? This action cannot be undone.
          </p>
          
          <div class="flex space-x-3">
            <button
              @click="deleteUser"
              :disabled="deletingUser"
              class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50"
            >
              {{ deletingUser ? 'Deleting...' : 'Delete' }}
            </button>
            <button
              @click="closeDeleteModal"
              class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
      </div>

      <!-- Membership Renewal Tab -->
      <div v-if="activeTab === 'memberships'">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Membership Renewal</h1>
          <p class="text-gray-600">Manage membership status for all users by semester</p>
        </div>

        <!-- Semester Filter -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Select Semester</label>
              <select
                v-model="selectedSemester"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-gray-900"
              >
                <option :value="null">Select a semester</option>
                <option v-for="sem in availableSemesters" :key="sem.label" :value="sem">
                  {{ sem.label }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
              <input
                v-model="membershipSearchQuery"
                type="text"
                placeholder="Search by name or email..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
              <select
                v-model="membershipStatusFilter"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
              >
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="expired">Expired</option>
                <option value="pending">Pending</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Memberships Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
          <div v-if="loadingMemberships" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-red-600 border-t-transparent"></div>
            <p class="mt-4 text-gray-600">Loading memberships...</p>
          </div>

          <table v-else class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  User
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Email
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Role
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Semester Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Renewed
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Renewal Date
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="membership in filteredMemberships" :key="membership.user_id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ membership.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-500">{{ membership.email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="{
                      'bg-purple-100 text-purple-800': membership.role === 'admin',
                      'bg-blue-100 text-blue-800': membership.role === 'player'
                    }"
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  >
                    {{ membership.role }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    v-if="selectedSemester"
                    :class="{
                      'bg-green-100 text-green-800': getMembershipStatus(membership) === 'active',
                      'bg-red-100 text-red-800': getMembershipStatus(membership) === 'expired',
                      'bg-yellow-100 text-yellow-800': getMembershipStatus(membership) === 'pending'
                    }"
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  >
                    {{ getMembershipStatus(membership) }}
                  </span>
                  <span v-else class="text-sm text-gray-400">Select semester</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    v-if="selectedSemester"
                    :class="{
                      'bg-green-100 text-green-800': getMembershipRenewed(membership),
                      'bg-gray-100 text-gray-800': !getMembershipRenewed(membership)
                    }"
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  >
                    {{ getMembershipRenewed(membership) ? 'Yes' : 'No' }}
                  </span>
                  <span v-else class="text-sm text-gray-400">-</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ getMembershipRenewalDate(membership) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-3">
                    <button
                      v-if="selectedSemester"
                      @click="openRenewalModal(membership)"
                      class="text-blue-600 hover:text-blue-900 transition-colors"
                      :disabled="updatingMembership === membership.user_id"
                    >
                      Update Status
                    </button>
                    <span v-else class="text-gray-400">Select semester</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Empty State -->
          <div v-if="!loadingMemberships && filteredMemberships.length === 0" class="text-center py-12">
            <p class="text-gray-500">No memberships found matching your filters.</p>
          </div>
        </div>
      </div>

      <!-- Renewal Modal -->
      <div v-if="showRenewalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 animate-fade-in">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Update Membership Status</h2>
          
          <form @submit.prevent="updateMembershipStatus">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <p class="text-gray-900">{{ renewalForm.userName }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <p class="text-gray-900">{{ selectedSemester?.label }}</p>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select
                  v-model="renewalForm.status"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                >
                  <option value="active">Active</option>
                  <option value="expired">Expired</option>
                  <option value="pending">Pending</option>
                </select>
              </div>
              
              <div>
                <label class="flex items-center space-x-2">
                  <input
                    v-model="renewalForm.renewed"
                    type="checkbox"
                    class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                  />
                  <span class="text-sm font-medium text-gray-700">Mark as Renewed</span>
                </label>
              </div>
            </div>

            <div class="mt-6 flex space-x-3">
              <button
                type="submit"
                :disabled="updatingMembership"
                class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50"
              >
                {{ updatingMembership ? 'Updating...' : 'Update' }}
              </button>
              <button
                type="button"
                @click="closeRenewalModal"
                class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Semester Confirmation Modals -->
      <div v-if="showSemesterActionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 animate-fade-in">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ semesterActionTitle }}</h2>
          <p class="text-gray-600 mb-6" v-html="semesterActionMessage"></p>
          
          <div class="flex space-x-3">
            <button
              @click="executeSemesterAction"
              :disabled="loadingMemberships"
              :class="semesterActionColor"
              class="flex-1 text-white py-2 px-4 rounded-lg transition-colors disabled:opacity-50"
            >
              {{ loadingMemberships ? 'Processing...' : 'Confirm' }}
            </button>
            <button
              @click="closeSemesterActionModal"
              class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>

      <!-- Payment Verification Modal -->
      <div v-if="showVerifyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 animate-fade-in">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">
            {{ verifyAction === 'verify' ? 'Verify Payment' : 'Reject Payment' }}
          </h2>
          
          <div class="space-y-4 mb-6">
            <div>
              <p class="text-sm text-gray-600">User</p>
              <p class="font-semibold text-gray-900">{{ verifyForm.userName }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Semester</p>
              <p class="font-semibold text-gray-900">{{ verifyForm.semester }} {{ verifyForm.year }}</p>
            </div>

            <div v-if="verifyAction === 'reject'" class="mt-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
              <textarea
                v-model="verifyForm.rejectionReason"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Please provide a reason for rejection..."
              ></textarea>
            </div>
          </div>

          <div class="flex space-x-3">
            <button
              @click="processPaymentVerification"
              :disabled="processingPayment || (verifyAction === 'reject' && !verifyForm.rejectionReason)"
              :class="verifyAction === 'verify' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'"
              class="flex-1 text-white py-2 px-4 rounded-lg transition-colors disabled:opacity-50"
            >
              {{ processingPayment ? 'Processing...' : verifyAction === 'verify' ? 'Verify & Activate' : 'Reject' }}
            </button>
            <button
              @click="closeVerifyModal"
              class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>

      <!-- Pending Payments Tab -->
      <div v-if="activeTab === 'payments'">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Verifications</h1>
          <p class="text-gray-600">Review and verify payment receipts</p>
        </div>

        <!-- Payment Type Tabs -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
          <div class="flex space-x-4">
            <button
              @click="paymentTab = 'membership'"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-colors',
                paymentTab === 'membership' 
                  ? 'bg-red-600 text-white' 
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              ]"
            >
              Membership Payments (RM 15)
            </button>
            <button
              @click="paymentTab = 'guest'"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-colors',
                paymentTab === 'guest' 
                  ? 'bg-red-600 text-white' 
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              ]"
            >
              Guest Drop-in Payments (RM 3)
              <span v-if="pendingGuestPayments.length > 0" class="ml-2 px-2 py-1 bg-red-500 text-white text-xs rounded-full">
                {{ pendingGuestPayments.length }}
              </span>
            </button>
          </div>
        </div>

        <!-- Membership Payments Tab -->
        <div v-if="paymentTab === 'membership'">
          <!-- Loading State -->
          <div v-if="loadingPayments" class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-red-600"></div>
            <p class="mt-4 text-gray-600">Loading pending payments...</p>
          </div>

          <!-- Payments List -->
          <div v-else class="space-y-4">
            <div v-if="pendingReceipts.length === 0" class="bg-white rounded-lg shadow-sm p-12 text-center">
              <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-gray-500 text-lg">No pending membership payments</p>
            </div>

            <div v-for="receipt in pendingReceipts" :key="`${receipt.user_id}-${receipt.receipt.semester}-${receipt.receipt.year}`" 
                 class="bg-white rounded-lg shadow-sm p-6">
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- User Info -->
                <div class="space-y-4">
                  <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ receipt.user_name }}</h3>
                    <p class="text-sm text-gray-600">{{ receipt.user_email }}</p>
                  </div>

                  <div class="space-y-2">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Semester:</span>
                      <span class="text-sm font-semibold text-gray-900">{{ receipt.receipt.semester }} {{ receipt.receipt.year }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Uploaded:</span>
                      <span class="text-sm text-gray-900">{{ formatDate(receipt.receipt.uploaded_at) }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Amount:</span>
                      <span class="text-sm font-semibold text-gray-900">RM 15.00</span>
                    </div>
                  </div>
                </div>

                <!-- Receipt Preview -->
                <div class="space-y-4">
                  <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                    <p class="text-sm font-medium text-gray-700 mb-2">Payment Receipt</p>
                    <div class="bg-white rounded p-3 text-center">
                      <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      <p class="text-xs text-gray-500 mt-2">{{ receipt.receipt.filename }}</p>
                      <a :href="`${API_URL.replace('/api', '')}/storage/${receipt.receipt.path}`" 
                         target="_blank"
                         class="text-xs text-blue-600 hover:text-blue-800 mt-2 inline-block">
                        View Full Receipt
                      </a>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex space-x-3">
                    <button
                      @click="verifyPaymentDirect(receipt)"
                      :disabled="processingPayment === receipt.user_id"
                      class="flex-1 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                      {{ processingPayment === receipt.user_id ? 'Processing...' : '✓ Verify & Activate' }}
                    </button>
                    <button
                      @click="openVerifyModal(receipt, 'reject')"
                      :disabled="processingPayment === receipt.user_id"
                      class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                      ✗ Reject
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Guest Payments Tab -->
        <div v-if="paymentTab === 'guest'">
          <!-- Loading State -->
          <div v-if="loadingGuestPayments" class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-red-600"></div>
            <p class="mt-4 text-gray-600">Loading guest payments...</p>
          </div>

          <!-- Guest Payments List -->
          <div v-else class="space-y-4">
            <div v-if="pendingGuestPayments.length === 0" class="bg-white rounded-lg shadow-sm p-12 text-center">
              <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-gray-500 text-lg">No pending guest payments</p>
            </div>

            <div v-for="payment in pendingGuestPayments" :key="payment._id" 
                 class="bg-white rounded-lg shadow-sm p-6">
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Registration Info -->
                <div class="space-y-4">
                  <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ payment.player?.user?.name || 'Unknown' }}</h3>
                    <p class="text-sm text-gray-600">{{ payment.player?.user?.email || 'No email' }}</p>
                  </div>

                  <div class="space-y-2">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Session:</span>
                      <span class="text-sm font-semibold text-gray-900">{{ payment.session?.title || 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Session Date:</span>
                      <span class="text-sm text-gray-900">{{ formatSessionDate(payment.session?.date) }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Registered:</span>
                      <span class="text-sm text-gray-900">{{ formatDate(payment.registration_date) }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Amount:</span>
                      <span class="text-sm font-semibold text-gray-900">RM {{ payment.payment_amount?.toFixed(2) || '3.00' }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Type:</span>
                      <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Non-Member Drop-in</span>
                    </div>
                  </div>
                </div>

                <!-- Receipt Preview -->
                <div class="space-y-4">
                  <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                    <p class="text-sm font-medium text-gray-700 mb-2">Payment Receipt</p>
                    <div class="bg-white rounded p-3 text-center">
                      <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      <p class="text-xs text-gray-500 mt-2">Receipt</p>
                      <a :href="`${API_URL.replace('/api', '')}/storage/${payment.guest_payment_receipt}`" 
                         target="_blank"
                         class="text-xs text-blue-600 hover:text-blue-800 mt-2 inline-block">
                        View Full Receipt
                      </a>
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex space-x-3">
                    <button
                      @click="verifyGuestPayment(payment._id, 'approve')"
                      :disabled="processingGuestPayment === payment._id"
                      class="flex-1 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                      {{ processingGuestPayment === payment._id ? 'Processing...' : '✓ Approve Payment' }}
                    </button>
                    <button
                      @click="verifyGuestPayment(payment._id, 'reject')"
                      :disabled="processingGuestPayment === payment._id"
                      class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                      ✗ Reject
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sessions Tab -->
      <div v-if="activeTab === 'sessions'">
        <div class="mb-8 flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Sessions Management</h1>
            <p class="text-gray-600">Create and manage sessions with calendar view</p>
          </div>
          <button
            @click="showCreateSessionModal = true"
            class="flex items-center space-x-2 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-lg"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="font-semibold">Create Session</span>
          </button>
        </div>

        <!-- View Toggle -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
          <div class="flex space-x-4">
            <button
              @click="switchToCalendarView"
              type="button"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-colors cursor-pointer',
                sessionView === 'calendar' 
                  ? 'bg-red-600 text-white' 
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              ]"
            >
              <svg class="w-5 h-5 inline-block mr-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Calendar View
            </button>
            <button
              @click="switchToListView"
              type="button"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-colors cursor-pointer',
                sessionView === 'list' 
                  ? 'bg-red-600 text-white' 
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              ]"
            >
              <svg class="w-5 h-5 inline-block mr-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              List View
            </button>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loadingSessions" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-red-600 border-t-transparent"></div>
          <p class="mt-4 text-gray-600">Loading sessions...</p>
        </div>

        <!-- Calendar View -->
        <div v-if="!loadingSessions && sessionView === 'calendar'" class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex justify-between items-center mb-6">
            <button
              @click="previousMonth"
              class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <h2 class="text-2xl font-bold text-gray-900">
              {{ currentMonthYear }}
            </h2>
            <button
              @click="nextMonth"
              class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>

          <!-- Calendar Grid -->
          <div class="grid grid-cols-7 gap-2">
            <!-- Day Headers -->
            <div v-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day"
              class="text-center font-semibold text-gray-600 py-2">
              {{ day }}
            </div>

            <!-- Calendar Days -->
            <div
              v-for="(day, index) in calendarDays"
              :key="index"
              :class="[
                'min-h-24 p-2 border rounded-lg',
                day.isCurrentMonth ? 'bg-white border-gray-200' : 'bg-gray-50 border-gray-100',
                day.isToday ? 'ring-2 ring-red-600' : ''
              ]"
            >
              <div class="text-sm font-semibold mb-1"
                :class="[
                  day.isCurrentMonth ? 'text-gray-900' : 'text-gray-400',
                  day.isToday ? 'text-red-600' : ''
                ]">
                {{ day.date }}
              </div>
              <div class="space-y-1">
                <div
                  v-for="session in day.sessions"
                  :key="session._id"
                  @click="viewSessionDetails(session)"
                  class="text-xs px-2 py-1 rounded cursor-pointer truncate hover:shadow-md transition-shadow"
                  :class="{
                    'bg-blue-100 text-blue-800 hover:bg-blue-200': session.type === 'drop-in',
                    'bg-green-100 text-green-800 hover:bg-green-200': session.type === 'training',
                    'bg-purple-100 text-purple-800 hover:bg-purple-200': session.type === 'tournament',
                    'bg-orange-100 text-orange-800 hover:bg-orange-200': session.type === 'special-event'
                  }"
                  :title="session.title"
                >
                  {{ session.start_time }} - {{ session.title }}
                </div>
              </div>
            </div>
          </div>

          <!-- Legend -->
          <div class="mt-6 flex flex-wrap gap-4">
            <div class="flex items-center space-x-2">
              <div class="w-4 h-4 bg-blue-100 rounded"></div>
              <span class="text-sm text-gray-600">Drop-in</span>
            </div>
            <div class="flex items-center space-x-2">
              <div class="w-4 h-4 bg-green-100 rounded"></div>
              <span class="text-sm text-gray-600">Training</span>
            </div>
            <div class="flex items-center space-x-2">
              <div class="w-4 h-4 bg-purple-100 rounded"></div>
              <span class="text-sm text-gray-600">Tournament</span>
            </div>
            <div class="flex items-center space-x-2">
              <div class="w-4 h-4 bg-orange-100 rounded"></div>
              <span class="text-sm text-gray-600">Special Event</span>
            </div>
          </div>
        </div>

        <!-- List View -->
        <div v-if="!loadingSessions && sessionView === 'list'" class="bg-white rounded-lg shadow-sm overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participants</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="session in sortedSessions" :key="session._id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ session.title }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="{
                      'bg-blue-100 text-blue-800': session.type === 'drop-in',
                      'bg-green-100 text-green-800': session.type === 'training',
                      'bg-purple-100 text-purple-800': session.type === 'tournament',
                      'bg-orange-100 text-orange-800': session.type === 'special-event'
                    }"
                  >
                    {{ session.type }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ formatDate(session.date) }}</div>
                  <div class="text-sm text-gray-600">{{ formatTime(session.start_time) }} - {{ formatTime(session.end_time) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ session.location }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ session.current_participants || 0 }} / {{ session.max_participants }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="viewSessionDetails(session)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    View
                  </button>
                  <button
                    @click="editSession(session)"
                    class="text-green-600 hover:text-green-900 mr-3"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteSession(session)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <div v-if="sortedSessions.length === 0" class="text-center py-12 text-gray-500">
            No sessions found. Create your first session!
          </div>
        </div>
      </div>

      <!-- Create/Edit Session Modal -->
      <div v-if="showCreateSessionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-gray-900">
                {{ editingSession ? 'Edit Session' : 'Create New Session' }}
              </h2>
              <button @click="closeSessionModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <form @submit.prevent="saveSession" class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                  <input
                    v-model="sessionForm.title"
                    type="text"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="e.g., Weekly Drop-in Session"
                  />
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                  <textarea
                    v-model="sessionForm.description"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Session description..."
                  ></textarea>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                  <select
                    v-model="sessionForm.type"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                  >
                    <option value="drop-in">Drop-in</option>
                    <option value="training">Training</option>
                    <option value="tournament">Tournament</option>
                    <option value="special-event">Special Event</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                  <input
                    v-model="sessionForm.date"
                    type="date"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                  <select
                    v-model="sessionForm.start_time"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                  >
                    <option value="" disabled>Select start time</option>
                    <option v-for="time in timeOptions" :key="time" :value="time">
                      {{ time }}
                    </option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                  <select
                    v-model="sessionForm.end_time"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                  >
                    <option value="" disabled>Select end time</option>
                    <option v-for="time in timeOptions" :key="time" :value="time">
                      {{ time }}
                    </option>
                  </select>
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                  <input
                    v-model="sessionForm.location"
                    type="text"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="e.g., Main Gym"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Max Participants *</label>
                  <input
                    v-model.number="sessionForm.max_participants"
                    type="number"
                    required
                    min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                  />
                </div>

                <div class="flex items-center">
                  <input
                    v-model="sessionForm.is_active"
                    type="checkbox"
                    class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                  />
                  <label class="ml-2 text-sm font-medium text-gray-700">Active Session</label>
                </div>
              </div>

              <div class="flex justify-end space-x-3 pt-4">
                <button
                  type="button"
                  @click="closeSessionModal"
                  class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="savingSession"
                  class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50"
                >
                  {{ savingSession ? 'Saving...' : (editingSession ? 'Update Session' : 'Create Session') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Session Details Modal -->
      <div v-if="showSessionDetailsModal && selectedSession" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-gray-900">{{ selectedSession.title }}</h2>
              <button @click="closeSessionDetailsModal" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div class="space-y-4">
              <!-- Type Badge -->
              <div>
                <span
                  class="px-3 py-1 inline-flex text-sm font-semibold rounded-full"
                  :class="{
                    'bg-blue-100 text-blue-800': selectedSession.type === 'drop-in',
                    'bg-green-100 text-green-800': selectedSession.type === 'training',
                    'bg-purple-100 text-purple-800': selectedSession.type === 'tournament',
                    'bg-orange-100 text-orange-800': selectedSession.type === 'special-event'
                  }"
                >
                  {{ selectedSession.type }}
                </span>
              </div>

              <!-- Description -->
              <div v-if="selectedSession.description">
                <h3 class="text-sm font-medium text-gray-700 mb-1">Description</h3>
                <p class="text-gray-600">{{ selectedSession.description }}</p>
              </div>

              <!-- Date & Time -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <h3 class="text-sm font-medium text-gray-700 mb-1">Date</h3>
                  <p class="text-gray-900">{{ formatDate(selectedSession.date) }}</p>
                </div>
                <div>
                  <h3 class="text-sm font-medium text-gray-700 mb-1">Time</h3>
                  <p class="text-gray-900">{{ formatTime(selectedSession.start_time) }} - {{ formatTime(selectedSession.end_time) }}</p>
                </div>
              </div>

              <!-- Location -->
              <div>
                <h3 class="text-sm font-medium text-gray-700 mb-1">Location</h3>
                <p class="text-gray-900">{{ selectedSession.location }}</p>
              </div>

              <!-- Participants -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <h3 class="text-sm font-medium text-gray-700 mb-1">Participants</h3>
                  <p class="text-gray-900">{{ selectedSession.current_participants || 0 }} / {{ selectedSession.max_participants }}</p>
                </div>
              </div>

              <!-- Status -->
              <div>
                <h3 class="text-sm font-medium text-gray-700 mb-1">Status</h3>
                <span
                  class="px-2 py-1 text-xs font-semibold rounded-full"
                  :class="selectedSession.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                >
                  {{ selectedSession.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-6 mt-6 border-t">
              <button
                @click="closeSessionDetailsModal"
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
              >
                Close
              </button>
              <button
                @click="editSessionFromDetails"
                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
              >
                Edit
              </button>
              <button
                @click="deleteSessionFromDetails"
                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Merchandise Tab -->
      <div v-if="activeTab === 'merchandise'">
        <div class="mb-8 flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Merchandise Management</h1>
            <p class="text-gray-600">Manage club merchandise, featured products, and inventory</p>
          </div>
          <button
            @click="openProductModal()"
            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Product
          </button>
        </div>

        <!-- Products Grid -->
        <div v-if="loadingProducts" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-red-600"></div>
          <p class="mt-4 text-gray-600">Loading products...</p>
        </div>

        <div v-else-if="products.length === 0" class="text-center py-12 bg-white rounded-lg shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No Products Yet</h3>
          <p class="text-gray-600 mb-6">Get started by adding your first product</p>
          <button
            @click="openProductModal()"
            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
          >
            Add Your First Product
          </button>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="product in products"
            :key="product._id"
            class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow"
          >
            <!-- Product Image -->
            <div class="relative h-48 bg-gray-200">
              <img
                v-if="product.image_url"
                :src="product.image_url"
                :alt="product.name"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <!-- Featured Badge -->
              <div
                v-if="product.is_featured"
                class="absolute top-2 left-2 px-3 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-full"
              >
                ⭐ Featured
              </div>
              <!-- Stock Badge -->
              <div
                v-if="!product.in_stock"
                class="absolute top-2 right-2 px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full"
              >
                Out of Stock
              </div>
            </div>

            <!-- Product Details -->
            <div class="p-4">
              <div class="mb-2">
                <h3 class="text-lg font-bold text-gray-900">{{ product.name }}</h3>
                <p class="text-sm text-gray-500">{{ product.category }}</p>
              </div>
              
              <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ product.description }}</p>
              
              <div class="flex items-center gap-2 mb-3">
                <span class="text-xl font-bold text-red-600">RM {{ product.price }}</span>
                <span v-if="product.original_price" class="text-sm text-gray-400 line-through">
                  RM {{ product.original_price }}
                </span>
              </div>

              <div class="text-sm text-gray-600 mb-3">
                <span v-if="product.sizes && product.sizes.length">
                  Sizes: {{ product.sizes.join(', ') }}
                </span>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-2 pt-3 border-t">
                <button
                  @click="toggleProductFeatured(product)"
                  class="flex-1 px-3 py-2 text-sm rounded-lg transition-colors"
                  :class="product.is_featured ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                >
                  {{ product.is_featured ? '⭐ Featured' : 'Set Featured' }}
                </button>
                <button
                  @click="openProductModal(product)"
                  class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors"
                >
                  Edit
                </button>
                <button
                  @click="confirmDeleteProduct(product)"
                  class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add/Edit Product Modal -->
      <div
        v-if="showProductModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.self="closeProductModal"
      >
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-fade-in">
          <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">
              {{ editingProduct ? 'Edit Product' : 'Add New Product' }}
            </h2>
            <button
              @click="closeProductModal"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <form @submit.prevent="saveProduct" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Product Name -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                <input
                  v-model="productForm.name"
                  type="text"
                  required
                  placeholder="E.g., Gators Jersey"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
              </div>

              <!-- Description -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea
                  v-model="productForm.description"
                  required
                  rows="3"
                  placeholder="Describe the product..."
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                ></textarea>
              </div>

              <!-- Category -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <select
                  v-model="productForm.category"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                >
                  <option value="">Select category</option>
                  <option value="Apparel">Apparel</option>
                  <option value="Equipment">Equipment</option>
                  <option value="Accessories">Accessories</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <!-- Price -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price (RM) *</label>
                <input
                  v-model.number="productForm.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  placeholder="0.00"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
              </div>

              <!-- Original Price -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Original Price (RM)</label>
                <input
                  v-model.number="productForm.original_price"
                  type="number"
                  step="0.01"
                  min="0"
                  placeholder="Leave empty if no discount"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
              </div>

              <!-- Image Upload -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                
                <!-- Image Preview -->
                <div v-if="imagePreview || productForm.image_url" class="mb-3">
                  <img 
                    :src="imagePreview || productForm.image_url" 
                    alt="Product preview" 
                    class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200"
                  />
                  <button
                    v-if="imagePreview || productForm.image_url"
                    @click="clearImage"
                    type="button"
                    class="mt-2 text-sm text-red-600 hover:text-red-700"
                  >
                    Remove Image
                  </button>
                </div>

                <!-- File Input -->
                <div class="flex gap-2">
                  <label class="flex-1 flex items-center justify-center px-4 py-2 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-red-500 transition-colors">
                    <div class="text-center">
                      <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      <span class="mt-1 text-sm text-gray-600">
                        {{ selectedImage ? selectedImage.name : 'Click to upload image' }}
                      </span>
                      <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 5MB</p>
                    </div>
                    <input
                      type="file"
                      @change="handleImageSelect"
                      accept="image/*"
                      class="hidden"
                    />
                  </label>
                </div>
              </div>

              <!-- Sizes -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Available Sizes</label>
                <div class="flex flex-wrap gap-2">
                  <label v-for="size in availableSizes" :key="size" class="flex items-center">
                    <input
                      type="checkbox"
                      :value="size"
                      v-model="productForm.sizes"
                      class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500"
                    />
                    <span class="text-sm text-gray-700">{{ size }}</span>
                  </label>
                </div>
              </div>

              <!-- Badge -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Badge (Optional)</label>
                <input
                  v-model="productForm.badge"
                  type="text"
                  placeholder="E.g., New, Sale, Limited"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
              </div>

              <!-- Checkboxes -->
              <div class="flex flex-col gap-2">
                <label class="flex items-center">
                  <input
                    type="checkbox"
                    v-model="productForm.in_stock"
                    class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500"
                  />
                  <span class="text-sm text-gray-700">In Stock</span>
                </label>
                <label class="flex items-center">
                  <input
                    type="checkbox"
                    v-model="productForm.is_featured"
                    class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500"
                  />
                  <span class="text-sm text-gray-700">Featured Product</span>
                </label>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-4 mt-6 pt-6 border-t">
              <button
                type="button"
                @click="closeProductModal"
                class="flex-1 px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="savingProduct"
                class="flex-1 px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50"
              >
                {{ savingProduct ? 'Saving...' : (editingProduct ? 'Update Product' : 'Add Product') }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <div
        v-if="showProductDeleteModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.self="showProductDeleteModal = false"
      >
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full animate-fade-in">
          <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Delete Product</h3>
            <p class="text-gray-600 text-center mb-6">
              Are you sure you want to delete "<strong>{{ productToDelete?.name }}</strong>"?
              This action cannot be undone.
            </p>
            <div class="flex gap-4">
              <button
                @click="showProductDeleteModal = false"
                class="flex-1 px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
              >
                Cancel
              </button>
              <button
                @click="deleteProduct"
                class="flex-1 px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8001/api'
import { useUserStore } from '@/stores/user'
import { useMembershipStore } from '@/stores/membership'
import { useSessionStore } from '@/stores/session'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const userStore = useUserStore()
const membershipStore = useMembershipStore()
const sessionStore = useSessionStore()
const authStore = useAuthStore()

// Active tab state
const activeTab = ref('users')

// User Management State
const loading = ref(false)
const error = ref(null)
const searchQuery = ref('')
const debouncedSearchQuery = ref('')
const filterRole = ref('')
const filterStatus = ref('')
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const userToDelete = ref(null)
const editForm = ref({
  _id: null,
  name: '',
  email: '',
  phone: '',
  role: 'player'
})
const updatingUser = ref(null)
const deletingUser = ref(null)

// Membership Management State
const loadingMemberships = ref(false)
const membershipSearchQuery = ref('')
const membershipStatusFilter = ref('')
const selectedSemester = ref(null)
const availableSemesters = ref([])
const showRenewalModal = ref(false)
const updatingMembership = ref(null)
const renewalForm = ref({
  userId: null,
  userName: '',
  status: 'pending',
  renewed: false
})
const selectedUserIds = ref([])
const showSemesterActionModal = ref(false)
const semesterAction = ref(null)
const semesterActionTitle = ref('')
const semesterActionMessage = ref('')

// Merchandise Management State
const products = ref([])
const loadingProducts = ref(false)
const showProductModal = ref(false)
const showProductDeleteModal = ref(false)
const editingProduct = ref(null)
const productToDelete = ref(null)
const savingProduct = ref(false)
const availableSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL']
const productForm = ref({
  name: '',
  description: '',
  category: '',
  price: 0,
  original_price: null,
  image_url: '',
  sizes: [],
  badge: '',
  in_stock: true,
  is_featured: false
})
const selectedImage = ref(null)
const imagePreview = ref(null)
const uploadingImage = ref(false)
const semesterActionColor = ref('')

// Payment Verification State
const paymentTab = ref('membership')
const loadingPayments = ref(false)
const pendingReceipts = ref([])
const loadingGuestPayments = ref(false)
const pendingGuestPayments = ref([])
const processingGuestPayment = ref(null)
const showVerifyModal = ref(false)
const processingPayment = ref(null)
const verifyAction = ref('verify')
const verifyForm = ref({
  userId: null,
  userName: '',
  semester: '',
  year: null,
  referenceNumber: '',
  rejectionReason: ''
})

// Session Management State
const loadingSessions = ref(false)
const sessionView = ref('calendar')
const showCreateSessionModal = ref(false)
const showSessionDetailsModal = ref(false)
const selectedSession = ref(null)
const editingSession = ref(null)
const savingSession = ref(false)
const currentMonth = ref(new Date().getMonth())
const currentYear = ref(new Date().getFullYear())

// Generate time options (every 30 minutes)
const timeOptions = computed(() => {
  const options = []
  for (let hour = 0; hour < 24; hour++) {
    for (let minute = 0; minute < 60; minute += 30) {
      const timeStr = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`
      options.push(timeStr)
    }
  }
  return options
})

const sessionForm = ref({
  title: '',
  description: '',
  type: 'drop-in',
  date: '',
  start_time: '',
  end_time: '',
  location: '',
  max_participants: 20,
  price: 0,
  is_active: true
})

// User Management Computed
const filteredUsers = computed(() => {
  let users = userStore.users

  // Exclude admin users
  users = users.filter(user => user.role !== 'admin')

  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    users = users.filter(user => 
      user.name.toLowerCase().includes(query) ||
      user.email.toLowerCase().includes(query)
    )
  }

  // Status filter
  if (filterStatus.value) {
    const isActive = filterStatus.value === 'active'
    users = users.filter(user => user.is_active === isActive)
  }

  return users
})

// Session Management Computed
const sortedSessions = computed(() => {
  return [...sessionStore.sessions].sort((a, b) => {
    return new Date(a.date) - new Date(b.date)
  })
})

const currentMonthYear = computed(() => {
  const date = new Date(currentYear.value, currentMonth.value)
  return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
})

const calendarDays = computed(() => {
  console.log('Computing calendar days for:', currentYear.value, currentMonth.value + 1)
  console.log('Total sessions in store:', sessionStore.sessions.length)
  
  const firstDay = new Date(currentYear.value, currentMonth.value, 1)
  const lastDay = new Date(currentYear.value, currentMonth.value + 1, 0)
  const prevLastDay = new Date(currentYear.value, currentMonth.value, 0)
  
  const firstDayIndex = firstDay.getDay()
  const lastDayIndex = lastDay.getDay()
  const nextDays = 7 - lastDayIndex - 1
  
  const days = []
  
  // Previous month days
  for (let x = firstDayIndex; x > 0; x--) {
    days.push({
      date: prevLastDay.getDate() - x + 1,
      isCurrentMonth: false,
      isToday: false,
      fullDate: new Date(currentYear.value, currentMonth.value - 1, prevLastDay.getDate() - x + 1),
      sessions: []
    })
  }
  
  // Current month days
  const today = new Date()
  today.setHours(0, 0, 0, 0) // Reset time to midnight for accurate comparison
  
  for (let i = 1; i <= lastDay.getDate(); i++) {
    const fullDate = new Date(currentYear.value, currentMonth.value, i)
    // Create date string manually to avoid timezone issues
    const year = currentYear.value
    const month = String(currentMonth.value + 1).padStart(2, '0')
    const day = String(i).padStart(2, '0')
    const dateStr = `${year}-${month}-${day}`
    
    const daySessions = sessionStore.sessions.filter(session => {
      // Handle different date formats from backend
      let sessionDate = session.date
      
      // If it's a Date object or timestamp, convert to string
      if (sessionDate instanceof Date) {
        const y = sessionDate.getFullYear()
        const m = String(sessionDate.getMonth() + 1).padStart(2, '0')
        const d = String(sessionDate.getDate()).padStart(2, '0')
        sessionDate = `${y}-${m}-${d}`
      } else if (typeof sessionDate === 'string') {
        // Extract just the date part if it includes time (e.g., "2025-12-11T00:00:00.000Z")
        sessionDate = sessionDate.split('T')[0]
      }
      
      const matches = sessionDate === dateStr
      
      // Debug logging for all days with sessions
      if (matches) {
        console.log(`✓ Session "${session.title}" matches ${dateStr}`)
      }
      
      return matches
    })
    
    const checkDate = new Date(currentYear.value, currentMonth.value, i)
    checkDate.setHours(0, 0, 0, 0)
    
    days.push({
      date: i,
      isCurrentMonth: true,
      isToday: checkDate.getTime() === today.getTime(),
      fullDate: fullDate,
      sessions: daySessions
    })
  }
  
  // Next month days
  for (let j = 1; j <= nextDays; j++) {
    days.push({
      date: j,
      isCurrentMonth: false,
      isToday: false,
      fullDate: new Date(currentYear.value, currentMonth.value + 1, j),
      sessions: []
    })
  }
  
  return days
})

// Membership Management Computed
const filteredMemberships = computed(() => {
  let memberships = membershipStore.memberships

  // Exclude admin users
  memberships = memberships.filter(m => m.role !== 'admin')

  // Search filter
  if (membershipSearchQuery.value) {
    const query = membershipSearchQuery.value.toLowerCase()
    memberships = memberships.filter(m => 
      m.name.toLowerCase().includes(query) ||
      m.email.toLowerCase().includes(query)
    )
  }

  // Status filter for selected semester
  if (membershipStatusFilter.value && selectedSemester.value) {
    memberships = memberships.filter(m => {
      const semesterData = m.semester_memberships?.find(
        s => s.semester === selectedSemester.value.semester && 
             s.year === selectedSemester.value.year
      )
      return semesterData?.status === membershipStatusFilter.value
    })
  }

  return memberships
})

const isAllSelected = computed(() => {
  return filteredMemberships.value.length > 0 && 
         selectedUserIds.value.length === filteredMemberships.value.length
})

// User Management Methods
const fetchUsers = async () => {
  loading.value = true
  error.value = null
  try {
    await userStore.fetchUsers()
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load users'
  } finally {
    loading.value = false
  }
}

const toggleUserStatus = async (user) => {
  updatingUser.value = user._id
  try {
    await userStore.updateUser(user._id, {
      is_active: !user.is_active
    })
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to update user status'
  } finally {
    updatingUser.value = null
  }
}

const openEditModal = (user) => {
  editForm.value = {
    _id: user._id,
    name: user.name,
    email: user.email,
    phone: user.phone || '',
    role: user.role
  }
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
  editForm.value = {
    _id: null,
    name: '',
    email: '',
    phone: '',
    role: 'player'
  }
}

const updateUser = async () => {
  updatingUser.value = editForm.value._id
  try {
    await userStore.updateUser(editForm.value._id, {
      name: editForm.value.name,
      email: editForm.value.email,
      phone: editForm.value.phone,
      role: editForm.value.role
    })
    closeEditModal()
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to update user'
  } finally {
    updatingUser.value = null
  }
}

const confirmDelete = (user) => {
  userToDelete.value = user
  showDeleteModal.value = true
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  userToDelete.value = null
}

const deleteUser = async () => {
  if (!userToDelete.value) return
  
  deletingUser.value = userToDelete.value._id
  try {
    await userStore.deleteUser(userToDelete.value._id)
    closeDeleteModal()
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to delete user'
  } finally {
    deletingUser.value = null
  }
}

// Membership Management Methods
const fetchMemberships = async () => {
  loadingMemberships.value = true
  try {
    await membershipStore.fetchMemberships()
    await membershipStore.fetchSemesters()
    availableSemesters.value = membershipStore.semesters
    
    // Set default semester to current one (Semester 1: Jan-Jun, Semester 2: Jul-Dec)
    const currentMonth = new Date().getMonth()
    const currentYear = new Date().getFullYear()
    const currentSemester = currentMonth >= 0 && currentMonth <= 5 ? 'Semester 1' : 'Semester 2'
    
    const defaultSem = availableSemesters.value.find(
      s => s.semester === currentSemester && s.year === currentYear
    )
    if (defaultSem) {
      selectedSemester.value = defaultSem
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load memberships'
  } finally {
    loadingMemberships.value = false
  }
}

const getMembershipStatus = (membership) => {
  if (!selectedSemester.value) return 'pending'
  
  const semesterData = membership.semester_memberships?.find(
    s => s.semester === selectedSemester.value.semester && 
         s.year === selectedSemester.value.year
  )
  
  return semesterData?.status || 'pending'
}

const getMembershipRenewed = (membership) => {
  if (!selectedSemester.value) return false
  
  const semesterData = membership.semester_memberships?.find(
    s => s.semester === selectedSemester.value.semester && 
         s.year === selectedSemester.value.year
  )
  
  return semesterData?.renewed || false
}

const getMembershipRenewalDate = (membership) => {
  if (!selectedSemester.value) return '-'
  
  const semesterData = membership.semester_memberships?.find(
    s => s.semester === selectedSemester.value.semester && 
         s.year === selectedSemester.value.year
  )
  
  if (semesterData?.renewal_date) {
    return new Date(semesterData.renewal_date).toLocaleDateString()
  }
  
  return '-'
}

const openRenewalModal = (membership) => {
  const semesterData = membership.semester_memberships?.find(
    s => s.semester === selectedSemester.value.semester && 
         s.year === selectedSemester.value.year
  )
  
  renewalForm.value = {
    userId: membership.user_id,
    userName: membership.name,
    status: semesterData?.status || 'pending',
    renewed: semesterData?.renewed || false
  }
  showRenewalModal.value = true
}

const closeRenewalModal = () => {
  showRenewalModal.value = false
  renewalForm.value = {
    userId: null,
    userName: '',
    status: 'pending',
    renewed: false
  }
}

const updateMembershipStatus = async () => {
  if (!selectedSemester.value || !renewalForm.value.userId) return
  
  updatingMembership.value = renewalForm.value.userId
  try {
    await membershipStore.updateSemesterMembership(renewalForm.value.userId, {
      semester: selectedSemester.value.semester,
      year: selectedSemester.value.year,
      status: renewalForm.value.status,
      renewed: renewalForm.value.renewed,
      renewal_date: renewalForm.value.renewed ? new Date().toISOString() : null
    })
    closeRenewalModal()
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to update membership'
  } finally {
    updatingMembership.value = null
  }
}

const goToHome = () => {
  router.push('/')
}

const handleLogout = () => {
  authStore.logout()
  router.push('/auth/login')
}

// Semester Management Methods
const toggleSelectAll = () => {
  if (isAllSelected.value) {
    selectedUserIds.value = []
  } else {
    selectedUserIds.value = filteredMemberships.value.map(m => m.user_id)
  }
}

const clearSelection = () => {
  selectedUserIds.value = []
}

const confirmStartSemester = () => {
  if (!selectedSemester.value) return
  
  semesterAction.value = 'start'
  semesterActionTitle.value = 'Start Semester'
  semesterActionMessage.value = `Are you sure you want to start <strong>${selectedSemester.value.label}</strong>?<br><br>This will create pending membership records for all users.`
  semesterActionColor.value = 'bg-green-600 hover:bg-green-700'
  showSemesterActionModal.value = true
}

const confirmEndSemester = () => {
  if (!selectedSemester.value) return
  
  semesterAction.value = 'end'
  semesterActionTitle.value = 'End Semester'
  semesterActionMessage.value = `Are you sure you want to end <strong>${selectedSemester.value.label}</strong>?<br><br>This will expire all active memberships for this semester.`
  semesterActionColor.value = 'bg-orange-600 hover:bg-orange-700'
  showSemesterActionModal.value = true
}

const confirmBulkRenew = () => {
  if (!selectedSemester.value || selectedUserIds.value.length === 0) return
  
  semesterAction.value = 'bulkRenew'
  semesterActionTitle.value = 'Bulk Renew Memberships'
  semesterActionMessage.value = `Are you sure you want to renew <strong>${selectedUserIds.value.length} memberships</strong> for <strong>${selectedSemester.value.label}</strong>?<br><br>This will mark selected users as active and renewed.`
  semesterActionColor.value = 'bg-blue-600 hover:bg-blue-700'
  showSemesterActionModal.value = true
}

const executeSemesterAction = async () => {
  if (!selectedSemester.value) return
  
  try {
    let result
    
    if (semesterAction.value === 'start') {
      result = await membershipStore.startSemester(
        selectedSemester.value.semester,
        selectedSemester.value.year
      )
    } else if (semesterAction.value === 'end') {
      result = await membershipStore.endSemester(
        selectedSemester.value.semester,
        selectedSemester.value.year
      )
    } else if (semesterAction.value === 'bulkRenew') {
      result = await membershipStore.bulkRenewMemberships(
        selectedSemester.value.semester,
        selectedSemester.value.year,
        selectedUserIds.value
      )
      selectedUserIds.value = []
    }
    
    closeSemesterActionModal()
    
    // Show success message (you can add a toast notification here)
    console.log('Success:', result.message)
  } catch (err) {
    error.value = err.response?.data?.message || 'Operation failed'
  }
}

const closeSemesterActionModal = () => {
  showSemesterActionModal.value = false
  semesterAction.value = null
  semesterActionTitle.value = ''
  semesterActionMessage.value = ''
  semesterActionColor.value = ''
}

// Payment Verification Methods
const fetchPendingReceipts = async () => {
  loadingPayments.value = true
  try {
    const token = localStorage.getItem('token')
    const response = await axios.get(`${API_URL}/memberships/pending-receipts`, {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    pendingReceipts.value = response.data.pending_receipts || []
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load pending payments'
  } finally {
    loadingPayments.value = false
  }
}

const fetchPendingGuestPayments = async () => {
  loadingGuestPayments.value = true
  try {
    const token = localStorage.getItem('token')
    const response = await axios.get(`${API_URL}/registrations/guest-payments/pending`, {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    pendingGuestPayments.value = response.data.pending_payments || []
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load guest payments'
  } finally {
    loadingGuestPayments.value = false
  }
}

const verifyGuestPayment = async (registrationId, action) => {
  processingGuestPayment.value = registrationId
  
  try {
    const token = localStorage.getItem('token')
    await axios.post(`${API_URL}/registrations/${registrationId}/verify-guest-payment`, {
      action: action
    }, {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    
    // Refresh the list
    await fetchPendingGuestPayments()
    
    // Show success message
    const message = action === 'approve' 
      ? 'Guest payment verified successfully' 
      : 'Guest payment rejected and registration cancelled'
    
    // You can add a toast notification here if needed
    console.log(message)
    
  } catch (err) {
    error.value = err.response?.data?.message || `Failed to ${action} guest payment`
  } finally {
    processingGuestPayment.value = null
  }
}

const formatSessionDate = (dateString) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    weekday: 'short',
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  })
}

const openVerifyModal = (receipt, action) => {
  verifyAction.value = action
  verifyForm.value = {
    userId: receipt.user_id,
    userName: receipt.user_name,
    semester: receipt.receipt.semester,
    year: receipt.receipt.year,
    referenceNumber: receipt.receipt.reference_number,
    rejectionReason: ''
  }
  showVerifyModal.value = true
}

const closeVerifyModal = () => {
  showVerifyModal.value = false
  verifyForm.value = {
    userId: null,
    userName: '',
    semester: '',
    year: null,
    referenceNumber: '',
    rejectionReason: ''
  }
}

const verifyPaymentDirect = async (receipt) => {
  processingPayment.value = receipt.user_id
  
  try {
    const token = localStorage.getItem('token')
    
    const response = await axios.post(
      `${API_URL}/memberships/${receipt.user_id}/verify-payment`,
      {
        semester: receipt.receipt.semester,
        year: parseInt(receipt.receipt.year, 10),
        action: 'verify'
      },
      {
        headers: { 'Authorization': `Bearer ${token}` }
      }
    )
    
    // Refresh pending receipts list
    await fetchPendingReceipts()
    
    // Refresh memberships list
    await fetchMemberships()
    
  } catch (err) {
    console.error('Verification error:', err)
    error.value = err.response?.data?.message || 'Failed to verify payment'
  } finally {
    processingPayment.value = null
  }
}

const processPaymentVerification = async () => {
  processingPayment.value = verifyForm.value.userId
  
  try {
    const token = localStorage.getItem('token')
    
    const response = await axios.post(
      `${API_URL}/memberships/${verifyForm.value.userId}/verify-payment`,
      {
        semester: verifyForm.value.semester,
        year: parseInt(verifyForm.value.year, 10),
        action: verifyAction.value,
        rejection_reason: verifyForm.value.rejectionReason
      },
      {
        headers: { 'Authorization': `Bearer ${token}` }
      }
    )
    
    // Refresh pending receipts list
    await fetchPendingReceipts()
    
    // Refresh memberships list
    await fetchMemberships()
    
    closeVerifyModal()
    
  } catch (err) {
    console.error('Verification error:', err)
    error.value = err.response?.data?.message || 'Failed to process payment'
  } finally {
    processingPayment.value = null
  }
}

// Session Management Methods
const switchToCalendarView = () => {
  console.log('Switching to calendar view')
  sessionView.value = 'calendar'
  console.log('sessionView is now:', sessionView.value)
}

const switchToListView = () => {
  console.log('Switching to list view')
  sessionView.value = 'list'
  console.log('sessionView is now:', sessionView.value)
}

const fetchSessions = async () => {
  loadingSessions.value = true
  try {
    await sessionStore.fetchSessions()
    console.log('All sessions loaded:', sessionStore.sessions.map(s => ({ title: s.title, date: s.date, type: typeof s.date })))
  } catch (err) {
    console.error('Failed to fetch sessions:', err)
  } finally {
    loadingSessions.value = false
  }
}

const previousMonth = () => {
  if (currentMonth.value === 0) {
    currentMonth.value = 11
    currentYear.value--
  } else {
    currentMonth.value--
  }
}

const nextMonth = () => {
  if (currentMonth.value === 11) {
    currentMonth.value = 0
    currentYear.value++
  } else {
    currentMonth.value++
  }
}

const viewSessionDetails = (session) => {
  selectedSession.value = session
  showSessionDetailsModal.value = true
}

const closeSessionDetailsModal = () => {
  showSessionDetailsModal.value = false
  selectedSession.value = null
}

const editSessionFromDetails = () => {
  if (selectedSession.value) {
    closeSessionDetailsModal()
    editSession(selectedSession.value)
  }
}

const deleteSessionFromDetails = async () => {
  if (selectedSession.value) {
    closeSessionDetailsModal()
    await deleteSession(selectedSession.value)
  }
}

const editSession = (session) => {
  editingSession.value = session
  sessionForm.value = {
    title: session.title,
    description: session.description || '',
    type: session.type,
    date: session.date,
    start_time: session.start_time,
    end_time: session.end_time,
    location: session.location,
    max_participants: session.max_participants,
    price: session.price || 0,
    is_active: session.is_active !== false
  }
  showCreateSessionModal.value = true
}

const deleteSession = async (session) => {
  if (!confirm(`Are you sure you want to delete "${session.title}"?`)) return
  
  try {
    await sessionStore.deleteSession(session._id)
  } catch (err) {
    alert('Failed to delete session: ' + (err.response?.data?.message || err.message))
  }
}

const saveSession = async () => {
  savingSession.value = true
  try {
    if (editingSession.value) {
      await sessionStore.updateSession(editingSession.value._id, sessionForm.value)
    } else {
      await sessionStore.createSession(sessionForm.value)
    }
    closeSessionModal()
  } catch (err) {
    alert('Failed to save session: ' + (err.response?.data?.message || err.message))
  } finally {
    savingSession.value = false
  }
}

const closeSessionModal = () => {
  showCreateSessionModal.value = false
  editingSession.value = null
  sessionForm.value = {
    title: '',
    description: '',
    type: 'drop-in',
    date: '',
    start_time: '',
    end_time: '',
    location: '',
    max_participants: 20,
    price: 0,
    is_active: true
  }
}

const formatDate = (dateStr) => {
  const date = new Date(dateStr)
  return date.toLocaleDateString('en-US', { 
    weekday: 'short', 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  })
}

const formatTime = (timeStr) => {
  if (!timeStr) return ''
  
  // Convert to string if it's not already
  const timeString = String(timeStr)
  
  // If it's already in HH:mm format, return it
  if (timeString.match(/^\d{2}:\d{2}$/)) {
    return timeString
  }
  
  // If it's a full datetime string, extract the time
  try {
    const date = new Date(timeString)
    if (isNaN(date.getTime())) {
      return timeString
    }
    return date.toLocaleTimeString('en-US', { 
      hour: '2-digit', 
      minute: '2-digit',
      hour12: false 
    })
  } catch (e) {
    return timeString
  }
}

// Merchandise Management Methods
const fetchProducts = async () => {
  loadingProducts.value = true
  try {
    const response = await axios.get(`${API_URL}/products`, {
      headers: {
        'Authorization': `Bearer ${authStore.token}`
      }
    })
    products.value = response.data.data || response.data
    console.log('Fetched products:', products.value.length)
    if (products.value.length > 0) {
      console.log('First product structure:', products.value[0])
      console.log('First product ID:', products.value[0]._id)
    }
  } catch (err) {
    console.error('Failed to fetch products:', err)
    alert('Failed to load products: ' + (err.response?.data?.message || err.message))
  } finally {
    loadingProducts.value = false
  }
}

// Image Upload Functions
const handleImageSelect = (event) => {
  const file = event.target.files[0]
  if (!file) return

  // Validate file size (5MB)
  if (file.size > 5 * 1024 * 1024) {
    alert('Image size must be less than 5MB')
    return
  }

  // Validate file type
  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
  if (!allowedTypes.includes(file.type)) {
    alert('Only JPEG, PNG, GIF, and WebP images are allowed')
    return
  }

  selectedImage.value = file

  // Create preview
  const reader = new FileReader()
  reader.onload = (e) => {
    imagePreview.value = e.target.result
  }
  reader.readAsDataURL(file)
}

const clearImage = () => {
  selectedImage.value = null
  imagePreview.value = null
  productForm.value.image_url = ''
}

const uploadImage = async () => {
  if (!selectedImage.value) return null

  uploadingImage.value = true
  try {
    const formData = new FormData()
    formData.append('image', selectedImage.value)

    const response = await axios.post(
      `${API_URL}/upload-image`,
      formData,
      {
        headers: {
          'Authorization': `Bearer ${authStore.token}`,
          'Content-Type': 'multipart/form-data'
        }
      }
    )

    return response.data.url
  } catch (err) {
    console.error('Image upload failed:', err)
    alert('Failed to upload image: ' + (err.response?.data?.message || err.message))
    return null
  } finally {
    uploadingImage.value = false
  }
}

const openProductModal = (product = null) => {
  if (product) {
    editingProduct.value = product
    productForm.value = {
      name: product.name,
      description: product.description,
      category: product.category,
      price: product.price,
      original_price: product.original_price || null,
      image_url: product.image_url || '',
      sizes: product.sizes || [],
      badge: product.badge || '',
      in_stock: product.in_stock !== false,
      is_featured: product.is_featured || false
    }
  } else {
    editingProduct.value = null
    productForm.value = {
      name: '',
      description: '',
      category: '',
      price: 0,
      original_price: null,
      image_url: '',
      sizes: [],
      badge: '',
      in_stock: true,
      is_featured: false
    }
  }
  showProductModal.value = true
}

const closeProductModal = () => {
  showProductModal.value = false
  editingProduct.value = null
  selectedImage.value = null
  imagePreview.value = null
  productForm.value = {
    name: '',
    description: '',
    category: '',
    price: 0,
    original_price: null,
    image_url: '',
    sizes: [],
    badge: '',
    in_stock: true,
    is_featured: false
  }
}

const saveProduct = async () => {
  savingProduct.value = true
  try {
    // Upload image first if a new one is selected
    if (selectedImage.value) {
      const uploadedUrl = await uploadImage()
      if (uploadedUrl) {
        productForm.value.image_url = uploadedUrl
      } else {
        // Upload failed, stop saving
        savingProduct.value = false
        return
      }
    }

    const productData = {
      ...productForm.value,
      original_price: productForm.value.original_price || null,
      in_stock: Boolean(productForm.value.in_stock),
      is_featured: Boolean(productForm.value.is_featured),
      image_url: productForm.value.image_url || null
    }

    if (editingProduct.value) {
      // Update existing product
      await axios.put(
        `${API_URL}/products/${editingProduct.value._id}`,
        productData,
        {
          headers: {
            'Authorization': `Bearer ${authStore.token}`,
            'Content-Type': 'application/json'
          }
        }
      )
    } else {
      // Create new product
      await axios.post(
        `${API_URL}/products`,
        productData,
        {
          headers: {
            'Authorization': `Bearer ${authStore.token}`,
            'Content-Type': 'application/json'
          }
        }
      )
    }

    closeProductModal()
    await fetchProducts()
    alert(`Product ${editingProduct.value ? 'updated' : 'created'} successfully!`)
  } catch (err) {
    console.error('Failed to save product:', err)
    console.error('Full error response:', err.response?.data)
    
    // Show detailed validation errors if available
    let errorMessage = 'Failed to save product: '
    if (err.response?.data?.errors) {
      const errors = Object.values(err.response.data.errors).flat()
      errorMessage += errors.join(', ')
    } else {
      errorMessage += (err.response?.data?.message || err.message)
    }
    
    alert(errorMessage)
  } finally {
    savingProduct.value = false
  }
}

const confirmDeleteProduct = (product) => {
  productToDelete.value = product
  showProductDeleteModal.value = true
}

const deleteProduct = async () => {
  if (!productToDelete.value) return

  console.log('Deleting product:', productToDelete.value)
  console.log('Product ID:', productToDelete.value._id)

  try {
    const url = `${API_URL}/products/${productToDelete.value._id}`
    console.log('DELETE URL:', url)
    
    await axios.delete(
      url,
      {
        headers: {
          'Authorization': `Bearer ${authStore.token}`
        }
      }
    )
    
    showProductDeleteModal.value = false
    productToDelete.value = null
    await fetchProducts()
    alert('Product deleted successfully!')
  } catch (err) {
    console.error('Failed to delete product:', err)
    alert('Failed to delete product: ' + (err.response?.data?.message || err.message))
  }
}

const toggleProductFeatured = async (product) => {
  try {
    await axios.post(
      `${API_URL}/products/${product._id}/toggle-featured`,
      {},
      {
        headers: {
          'Authorization': `Bearer ${authStore.token}`
        }
      }
    )
    
    await fetchProducts()
  } catch (err) {
    console.error('Failed to toggle featured status:', err)
    alert('Failed to update featured status: ' + (err.response?.data?.message || err.message))
  }
}

// Watch for active tab changes
// Debounce search query for performance
let searchDebounceTimer = null
watch(searchQuery, (newValue) => {
  clearTimeout(searchDebounceTimer)
  searchDebounceTimer = setTimeout(() => {
    debouncedSearchQuery.value = newValue
  }, 300)
})

watch(activeTab, (newTab) => {
  if (newTab === 'payments') {
    fetchPendingReceipts()
    fetchPendingGuestPayments()
  } else if (newTab === 'merchandise') {
    fetchProducts()
  }
})

// Lifecycle
onMounted(() => {
  fetchUsers()
  fetchMemberships()
  fetchSessions()
})
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-fade-in {
  animation: fade-in 0.2s ease-out;
}
</style>
