<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-red-50 to-gray-100">
    <div class="container mx-auto px-4 py-12">
      <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="flex items-center space-x-4 mb-2">
            <div class="w-20 h-20 bg-gradient-to-br from-red-600 to-red-700 rounded-full flex items-center justify-center shadow-lg">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <h1 class="text-4xl font-bold text-gray-900">My Profile</h1>
              <p class="text-gray-600 mt-1">Manage your account and membership</p>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-20">
          <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-red-600 border-t-transparent"></div>
          <p class="mt-4 text-gray-600 text-lg">Loading profile...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border-l-4 border-red-500 rounded-r-lg p-6 mb-6 shadow-sm">
          <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-red-800 font-medium">{{ error }}</p>
          </div>
        </div>

        <!-- Profile Content -->
        <div v-else-if="user" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Left Column - Account Info -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Account Information Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
              <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-xl font-bold text-white">Account Information</h2>
                  </div>
                  <button
                    v-if="!editingAccount"
                    @click="startEditAccount"
                    class="flex items-center space-x-2 px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white rounded-lg transition-all duration-200"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit</span>
                  </button>
                </div>
              </div>

              <div class="p-6">
                <div v-if="!editingAccount" class="space-y-6">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                      <label class="block text-sm font-semibold text-gray-500 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Name
                      </label>
                      <p class="text-gray-900 font-medium text-lg">{{ user.name }}</p>
                    </div>
                    <div class="group">
                      <label class="block text-sm font-semibold text-gray-500 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Email
                      </label>
                      <p class="text-gray-900 font-medium text-lg">{{ user.email }}</p>
                    </div>
                    <div class="group">
                      <label class="block text-sm font-semibold text-gray-500 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Phone
                      </label>
                      <p class="text-gray-900 font-medium text-lg">{{ user.phone || 'Not provided' }}</p>
                    </div>
                    <div class="group">
                      <label class="block text-sm font-semibold text-gray-500 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Role
                      </label>
                      <span
                        :class="{
                          'bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800 border border-purple-300': user.role === 'admin',
                          'bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300': user.role === 'player'
                        }"
                        class="px-4 py-2 inline-flex text-sm font-bold rounded-full"
                      >
                        {{ user.role === 'admin' ? 'Administrator' : 'Player' }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Edit Account Form -->
                <form v-else @submit.prevent="saveAccountInfo" class="space-y-6">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                      <input
                        v-model="accountForm.name"
                        type="text"
                        required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                      <input
                        v-model="accountForm.email"
                        type="email"
                        required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                      <input
                        v-model="accountForm.phone"
                        type="tel"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all"
                      />
                    </div>
                  </div>

                  <div class="flex space-x-3 pt-4">
                    <button
                      type="submit"
                      :disabled="updating"
                      class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50"
                    >
                      {{ updating ? 'Saving...' : 'Save Changes' }}
                    </button>
                    <button
                      type="button"
                      @click="cancelEditAccount"
                      class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-200"
                    >
                      Cancel
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Membership History Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
              <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                <div class="flex items-center space-x-3">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <h3 class="text-xl font-bold text-white">Membership History</h3>
                </div>
              </div>

              <div class="p-6">
                <div v-if="loadingMembership" class="text-center py-8">
                  <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-red-600 border-t-transparent"></div>
                </div>

                <div v-else class="space-y-3">
                  <div
                    v-for="membership in semesterMemberships.slice().sort((a, b) => b.year - a.year || (b.semester === 'Semester 2' ? 1 : -1))"
                    :key="`${membership.semester}-${membership.year}`"
                    class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200"
                  >
                    <div class="flex items-center space-x-3">
                      <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </div>
                      <div>
                        <span class="font-semibold text-gray-900">{{ membership.semester }} {{ membership.year }}</span>
                        <p v-if="membership.renewal_date" class="text-xs text-gray-500 mt-1">
                          Renewed: {{ new Date(membership.renewal_date).toLocaleDateString() }}
                        </p>
                      </div>
                    </div>
                    <span
                      :class="{
                        'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300': membership.status === 'active',
                        'bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300': membership.status === 'expired',
                        'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border border-gray-300': membership.status === 'pending'
                      }"
                      class="px-4 py-2 inline-flex text-xs font-bold rounded-full"
                    >
                      {{ membership.status === 'active' ? 'Active' : membership.status === 'expired' ? 'Expired' : 'Pending' }}
                    </span>
                  </div>
                  <div v-if="semesterMemberships.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 font-medium">No membership history available</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column - Membership Status -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 sticky top-6">
              <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <div class="flex items-center space-x-3">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                  </svg>
                  <h2 class="text-xl font-bold text-white">Membership</h2>
                </div>
              </div>

              <div class="p-6">
                <div v-if="loadingMembership" class="text-center py-8">
                  <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-red-600 border-t-transparent"></div>
                </div>

                <div v-else class="space-y-6">
                  <!-- Current Semester Status -->
                  <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6 shadow-sm">
                    <div class="text-center mb-4">
                      <p class="text-sm font-semibold text-blue-700 uppercase tracking-wide mb-2">Current Semester</p>
                      <p class="text-2xl font-bold text-blue-900 mb-3">{{ currentSemester }}</p>
                      <div class="flex justify-center">
                        <span
                          :class="{
                            'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg': currentMembershipStatus === 'active',
                            'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg': currentMembershipStatus === 'expired',
                            'bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow-lg': currentMembershipStatus === 'pending'
                          }"
                          class="px-5 py-2 inline-flex text-sm font-bold rounded-full"
                        >
                          {{ currentMembershipStatus === 'active' ? '✓ Active' : currentMembershipStatus === 'expired' ? '✗ Expired' : '◷ Pending' }}
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- Renewal Section -->
                  <div class="space-y-4">
                    <div class="border-t-2 border-gray-100 pt-6">
                      <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Renew Membership
                      </label>
                      <select
                        v-model="selectedSemester"
                        @change="onSemesterChange"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all font-medium text-gray-900"
                      >
                        <option :value="null">Choose a semester</option>
                        <option v-for="semester in availableSemesters" :key="semester.label" :value="semester">
                          {{ semester.label }}
                        </option>
                      </select>
                    </div>

                    <!-- Payment Instructions -->
                    <div v-if="selectedSemester && !showUploadForm" class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 space-y-4">
                      <div class="text-center">
                        <h3 class="text-lg font-bold text-blue-900 mb-2">Payment Instructions</h3>
                        <p class="text-sm text-blue-700 mb-4">Please transfer the membership fee to the account below</p>
                        
                        <!-- QR Code Placeholder -->
                        <div class="bg-white p-6 rounded-lg inline-block mb-4">
                          <div class="w-48 h-48 bg-gray-100 flex items-center justify-center rounded-lg">
                            <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                          </div>
                          <p class="text-xs text-gray-500 mt-2">Scan to pay</p>
                        </div>

                        <!-- Payment Details -->
                        <div class="bg-white rounded-lg p-4 text-left space-y-2">
                          <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Bank:</span>
                            <span class="text-sm font-semibold text-gray-900">Gators Bank</span>
                          </div>
                          <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Account:</span>
                            <span class="text-sm font-semibold text-gray-900">1234-5678-9012</span>
                          </div>
                          <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Amount:</span>
                            <span class="text-sm font-semibold text-gray-900">RM 15.00</span>
                          </div>
                        </div>

                        <p class="text-xs text-blue-600 mt-3">⚠️ Please ensure the payment amount is correct</p>
                      </div>

                      <button
                        @click="showUploadForm = true"
                        class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-200"
                      >
                        I've Made Payment - Upload Receipt
                      </button>
                    </div>

                    <!-- Upload Receipt Form -->
                    <div v-if="selectedSemester && showUploadForm" class="bg-white border-2 border-gray-200 rounded-xl p-6 space-y-4">
                      <h3 class="text-lg font-bold text-gray-900 mb-4">Upload Payment Receipt</h3>
                      
                      <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-red-500 transition-colors">
                        <input
                          type="file"
                          ref="fileInput"
                          @change="onFileChange"
                          accept="image/*,.pdf"
                          class="hidden"
                        />
                        
                        <div v-if="!selectedFile" @click="$refs.fileInput.click()" class="cursor-pointer">
                          <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                          </svg>
                          <p class="text-sm text-gray-600 mb-1">Click to upload payment receipt</p>
                          <p class="text-xs text-gray-500">PNG, JPG, PDF up to 5MB</p>
                        </div>

                        <div v-else class="space-y-3">
                          <svg class="w-12 h-12 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          <p class="text-sm font-medium text-gray-900">{{ selectedFile.name }}</p>
                          <p class="text-xs text-gray-500">{{ (selectedFile.size / 1024 / 1024).toFixed(2) }} MB</p>
                          <button
                            @click="selectedFile = null"
                            class="text-sm text-red-600 hover:text-red-800"
                          >
                            Remove
                          </button>
                        </div>
                      </div>

                      <div class="flex space-x-3">
                        <button
                          @click="showUploadForm = false; selectedFile = null"
                          class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50"
                        >
                          Cancel
                        </button>
                        <button
                          @click="uploadReceipt"
                          :disabled="!selectedFile || uploadingReceipt"
                          class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold rounded-xl hover:from-red-700 hover:to-red-800 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                          <span v-if="uploadingReceipt" class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Uploading...
                          </span>
                          <span v-else>Submit Receipt</span>
                        </button>
                      </div>
                    </div>

                    <transition
                      enter-active-class="transition ease-out duration-300"
                      enter-from-class="opacity-0 transform scale-95"
                      enter-to-class="opacity-100 transform scale-100"
                      leave-active-class="transition ease-in duration-200"
                      leave-from-class="opacity-100 transform scale-100"
                      leave-to-class="opacity-0 transform scale-95"
                    >
                      <div v-if="uploadSuccess" class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-xl p-4 shadow-sm">
                        <div class="flex items-center">
                          <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          <div>
                            <p class="text-green-800 font-bold">Receipt uploaded successfully!</p>
                            <p class="text-green-700 text-sm mt-1">Your payment is under review. You'll be notified once verified.</p>
                          </div>
                        </div>
                      </div>
                    </transition>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8001/api'

const authStore = useAuthStore()
const user = computed(() => authStore.user)

const loading = ref(false)
const loadingMembership = ref(false)
const error = ref(null)
const updating = ref(false)
const renewingMembership = ref(false)
const renewalSuccess = ref(false)
const uploadSuccess = ref(false)
const uploadingReceipt = ref(false)
const showUploadForm = ref(false)
const selectedFile = ref(null)
const referenceNumber = ref('')

const editingAccount = ref(false)

const semesterMemberships = ref([])
const availableSemesters = ref([])
const selectedSemester = ref(null)
const currentSemester = ref('')
const currentMembershipStatus = ref('pending')

const accountForm = ref({
  name: '',
  email: '',
  phone: ''
})

const getCurrentSemester = () => {
  const now = new Date()
  const year = now.getFullYear()
  const month = now.getMonth() + 1 // 1-12
  
  // January-June = Semester 1, July-December = Semester 2
  if (month >= 1 && month <= 6) {
    return `Semester 1 ${year}`
  } else {
    return `Semester 2 ${year}`
  }
}

const fetchMembershipStatus = async () => {
  loadingMembership.value = true
  try {
    const token = localStorage.getItem('token')
    
    // Fetch available semesters
    const semestersResponse = await axios.get(`${API_URL}/memberships/semesters`, {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    availableSemesters.value = semestersResponse.data.semesters || []
    
    // Fetch user's membership data
    const response = await axios.get(`${API_URL}/memberships`, {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    
    // Find current user's memberships
    const userMembership = response.data.users?.find(u => u._id === user.value._id)
    if (userMembership && userMembership.semester_memberships) {
      semesterMemberships.value = userMembership.semester_memberships
    }
    
    // Set current semester and status
    const now = new Date()
    const year = now.getFullYear()
    const month = now.getMonth() + 1
    const currentSem = month >= 1 && month <= 6 ? 'Semester 1' : 'Semester 2'
    
    currentSemester.value = `${currentSem} ${year}`
    
    // Find current semester status from array
    const currentSemData = semesterMemberships.value.find(sm => 
      sm.semester === currentSem && sm.year === year
    )
    currentMembershipStatus.value = currentSemData?.status || 'pending'
    
  } catch (err) {
    console.error('Error fetching membership status:', err)
  } finally {
    loadingMembership.value = false
  }
}

const renewMembership = async () => {
  if (!selectedSemester.value) return
  
  renewingMembership.value = true
  renewalSuccess.value = false
  error.value = null
  
  try {
    const token = localStorage.getItem('token')
    await axios.put(
      `${API_URL}/memberships/semester`,
      {
        user_id: user.value._id,
        semester: selectedSemester.value,
        status: 'active'
      },
      {
        headers: { 'Authorization': `Bearer ${token}` }
      }
    )
    
    // Refresh membership data
    await fetchMembershipStatus()
    
    renewalSuccess.value = true
    selectedSemester.value = ''
    
    // Hide success message after 3 seconds
    setTimeout(() => {
      renewalSuccess.value = false
    }, 3000)
    
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to renew membership'
  } finally {
    renewingMembership.value = false
  }
}

const onSemesterChange = () => {
  if (selectedSemester.value) {
    // Generate reference number
    const userId = user.value._id.substring(user.value._id.length - 6).toUpperCase()
    const semesterCode = selectedSemester.value.semester[9] // "1" or "2"
    referenceNumber.value = `GATORS-${userId}-${semesterCode}${selectedSemester.value.year}`
  }
  showUploadForm.value = false
  selectedFile.value = null
  uploadSuccess.value = false
}

const onFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
      error.value = 'File size must be less than 5MB'
      return
    }
    selectedFile.value = file
  }
}

const uploadReceipt = async () => {
  if (!selectedFile.value || !selectedSemester.value) return

  uploadingReceipt.value = true
  error.value = null
  uploadSuccess.value = false

  try {
    const token = localStorage.getItem('token')
    const formData = new FormData()
    formData.append('receipt', selectedFile.value)
    formData.append('semester', selectedSemester.value.semester)
    formData.append('year', selectedSemester.value.year)

    await axios.post(
      `${API_URL}/memberships/payment-receipt`,
      formData,
      {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'multipart/form-data'
        }
      }
    )

    uploadSuccess.value = true
    showUploadForm.value = false
    selectedFile.value = null
    selectedSemester.value = null

    // Refresh membership status
    await fetchMembershipStatus()

    // Hide success message after 5 seconds
    setTimeout(() => {
      uploadSuccess.value = false
    }, 5000)

  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to upload receipt'
    console.error('Upload error:', err)
  } finally {
    uploadingReceipt.value = false
  }
}

const startEditAccount = () => {
  accountForm.value = {
    name: user.value.name,
    email: user.value.email,
    phone: user.value.phone || ''
  }
  editingAccount.value = true
}

const cancelEditAccount = () => {
  editingAccount.value = false
}

const saveAccountInfo = async () => {
  updating.value = true
  error.value = null
  
  try {
    const token = localStorage.getItem('token')
    const response = await axios.put(
      `${API_URL}/users/${user.value._id}`,
      accountForm.value,
      {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      }
    )
    
    // Update user in auth store
    authStore.user = response.data.user
    localStorage.setItem('user', JSON.stringify(response.data.user))
    
    editingAccount.value = false
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to update account information'
  } finally {
    updating.value = false
  }
}

onMounted(() => {
  fetchMembershipStatus()
})
</script>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
