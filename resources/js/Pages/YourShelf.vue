<template>
  <Head title="Your Shelf" />
  <AppLayout title="" content-padding-classes="px-4 lg:px-[5%]">
    <div class="flex flex-col lg:pl-28 lg:pr-20 pt-4 content-wrapper">
      <div class="w-full">
        <h2 class="text-2xl sm:text-3xl font-extrabold kulim-park-bold tracking-tight mb-6">
          Your Shelf
        </h2>

        <!-- Owned Resources Section (Community Uploads) -->
        <div v-if="ownedResources && ownedResources.length > 0" class="mb-8">
          <h3 class="text-xl font-bold kulim-park-bold mb-4 text-gray-800">My Community Uploads</h3>
          <div class="space-y-6">
            <div
              v-for="resource in ownedResources"
              :key="resource.Resource_ID"
              class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200"
            >
              <div class="flex gap-6">
                <!-- Thumbnail -->
                <div class="w-32 h-48 flex-shrink-0 bg-gray-200 rounded-lg overflow-hidden">
                  <img
                    v-if="resource.thumbnail_path"
                    :src="`/storage/${resource.thumbnail_path}`"
                    :alt="resource.Resource_Name"
                    class="w-full h-full object-cover"
                  >
                  <div
                    v-else
                    class="w-full h-full flex items-center justify-center text-2xl font-bold text-gray-400"
                  >
                    {{ resource.Resource_Name?.substring(0, 2).toUpperCase() || 'NA' }}
                  </div>
                </div>

                <!-- Details -->
                <div class="flex-1">
                  <h4 class="text-xl font-bold kulim-park-bold mb-2">{{ resource.Resource_Name }}</h4>
                  <p class="text-sm text-gray-600 mb-4">by {{ resource.authors }}</p>

                  <!-- Approval Status -->
                  <div class="mb-4">
                    <span
                      :class="{
                        'px-3 py-1 rounded-full text-sm font-medium': true,
                        'bg-yellow-100 text-yellow-800': resource.approval_status === 'pending',
                        'bg-green-100 text-green-800': resource.approval_status === 'approved',
                        'bg-red-100 text-red-800': resource.approval_status === 'rejected',
                      }"
                    >
                      Status: {{ resource.approval_status || 'pending' }}
                    </span>
                  </div>

                  <!-- Analytics -->
                  <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-blue-50 rounded-lg p-3">
                      <p class="text-xs text-gray-600 mb-1">Views</p>
                      <p class="text-2xl font-bold text-blue-700">{{ resource.views || 0 }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3">
                      <p class="text-xs text-gray-600 mb-1">Total Borrows</p>
                      <p class="text-2xl font-bold text-green-700">{{ resource.total_borrows || 0 }}</p>
                    </div>
                  </div>

                  <!-- Borrow Requests -->
                  <div v-if="resource.borrow_requests && resource.borrow_requests.length > 0" class="mt-4">
                    <h5 class="font-semibold text-gray-700 mb-2">Pending Borrow Requests</h5>
                    <div class="space-y-2">
                      <div
                        v-for="request in resource.borrow_requests"
                        :key="request.Borrower_ID"
                        class="bg-gray-50 rounded-lg p-3 flex items-center justify-between"
                      >
                        <div>
                          <p class="font-medium text-gray-900">{{ request.user.full_name }}</p>
                          <p class="text-xs text-gray-500">{{ request.user.email }}</p>
                          <p class="text-xs text-gray-500 mt-1">
                            Expected Return: {{ formatDate(request.Return_Date) }}
                          </p>
                        </div>
                        <div class="flex gap-2">
                          <button
                            @click="approveBorrowRequest(request.Borrower_ID)"
                            class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700"
                          >
                            Approve
                          </button>
                          <button
                            @click="rejectBorrowRequest(request.Borrower_ID)"
                            class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700"
                          >
                            Reject
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <p v-else class="text-sm text-gray-500 italic">No pending borrow requests</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Community Uploads Section (for Librarians) -->
        <div v-if="userRole === 'librarian'" class="mb-8">
          <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 shadow-md">
            <h3 class="text-xl font-bold kulim-park-bold mb-3 text-gray-800 flex items-center gap-2">
              <i class="fas fa-users text-green-600"></i>
              Community Uploads Management
            </h3>
            <p class="text-gray-600 mb-4">
              Review and manage pending community upload requests from users.
            </p>
            <Link
              href="/resource-management?type=Community Uploads"
              class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl"
            >
              <i class="fas fa-arrow-right"></i>
              Go to Community Uploads
            </Link>
          </div>
        </div>

        <div v-if="borrows.length === 0" class="bg-white rounded-2xl shadow-lg p-12 text-center text-gray-600 text-lg">
          You don't have any borrows yet. Browse featured resources to start reading!
        </div>

        <div v-else>
          <!-- Pending & Rejected Requests Combined -->
          <div v-if="requestBorrows.length > 0" class="mb-8">
            <h3 class="text-xl font-bold kulim-park-bold mb-4 text-gray-800">Borrow Requests</h3>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resource</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested Date</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                      v-for="borrow in requestBorrows"
                      :key="borrow.id"
                      class="hover:bg-gray-50"
                      :class="{ 'cursor-pointer': !borrow.isRejected }"
                      @click="!borrow.isRejected && openRequestModal(borrow)"
                    >
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                          <div
                            v-if="borrow.resource?.thumbnail_path"
                            class="w-12 h-16 rounded overflow-hidden flex-shrink-0"
                          >
                            <img
                              :src="`/storage/${borrow.resource.thumbnail_path}`"
                              :alt="borrow.resource.Resource_Name"
                              class="w-full h-full object-cover"
                            >
                          </div>
                          <div
                            v-else
                            class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center uppercase text-gray-500 text-xs flex-shrink-0"
                          >
                            {{ borrow.resource?.Resource_Name?.substring(0, 2).toUpperCase() || 'NA' }}
                          </div>
                          <span class="font-semibold text-gray-900">{{ borrow.resource?.Resource_Name || 'Unknown Resource' }}</span>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        {{ formatDate(borrow.created_at) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span
                          v-if="borrow.isRejected"
                          class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-700 font-medium"
                        >
                          Rejected
                        </span>
                        <span
                          v-else
                          class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700 font-medium"
                        >
                          Pending Approval
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <button
                          v-if="borrow.isRejected"
                          @click.stop.prevent="showRejectionReason(borrow, $event)"
                          class="p-2 text-blue-600 hover:bg-blue-50 rounded-full transition-colors"
                          title="View rejection reason"
                        >
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Active Borrows -->
          <div v-if="activeBorrows.length > 0" class="mb-8">
            <h3 class="text-xl font-bold kulim-park-bold mb-4 text-gray-800">Active Borrows</h3>
            <div class="books-grid">
              <div
                v-for="borrow in activeBorrows"
                :key="borrow.id"
                class="book-card cursor-pointer"
                @click="openBookModal(borrow)"
              >
                <div class="book-cover bg-gray-400">
                  <img
                    v-if="borrow.resource?.thumbnail_path"
                    :src="`/storage/${borrow.resource.thumbnail_path}`"
                    :alt="borrow.resource.Resource_Name"
                    class="w-full h-full object-cover"
                  >
                  <span v-else class="text-white text-4xl font-bold">
                    {{ borrow.resource?.Resource_Name?.substring(0, 2).toUpperCase() || 'NA' }}
                  </span>
                </div>
                <div class="book-info">
                  <h3 class="book-title">{{ borrow.resource?.Resource_Name || 'Unknown Resource' }}</h3>
                  <p class="text-xs text-gray-500">
                    Borrowed: {{ formatDate(borrow.Approved_Date) }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Returned Books -->
          <div v-if="returnedBorrows.length > 0">
            <h3 class="text-xl font-bold kulim-park-bold mb-4 text-gray-800">Returned Books</h3>
            <div class="books-grid">
              <div
                v-for="borrow in returnedBorrows"
                :key="borrow.id"
                class="book-card cursor-pointer"
                @click="openRatingModal(borrow)"
              >
                <div class="book-cover bg-gray-400">
                  <img
                    v-if="borrow.resource?.thumbnail_path"
                    :src="`/storage/${borrow.resource.thumbnail_path}`"
                    :alt="borrow.resource.Resource_Name"
                    class="w-full h-full object-cover"
                  >
                  <span v-else class="text-white text-4xl font-bold">
                    {{ borrow.resource?.Resource_Name?.substring(0, 2).toUpperCase() || 'NA' }}
                  </span>
                </div>
                <div class="book-info">
                  <h3 class="book-title">{{ borrow.resource?.Resource_Name || 'Unknown Resource' }}</h3>
                  <p class="text-xs text-gray-500">
                    Returned: {{ borrow.Return_Date ? formatDate(borrow.Return_Date) : 'N/A' }}
                  </p>
                  <div class="flex items-center gap-1 mt-2">
                    <span class="text-yellow-500 text-sm">★</span>
                    <span class="text-xs text-gray-600">
                      {{ borrow.resource?.average_rating ?? '0.0' }}
                    </span>
                    <span v-if="borrow.userRating" class="text-xs text-green-600 ml-2">
                      (You rated: {{ borrow.userRating }})
                    </span>
                  </div>
                  <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 inline-block mt-1">
                    Returned
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Book Modal -->
    <div
      v-if="selectedBorrow"
      id="shelfModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] my-4 overflow-y-auto mx-auto">
        <div class="flex h-full min-h-0">
          <!-- Left: Thumbnail -->
          <div class="w-72 flex-shrink-0 bg-gradient-to-br from-gray-100 to-gray-200 p-6 flex items-center justify-center border-r border-gray-200">
            <div class="w-60 h-96 bg-gray-100 border-8 border-gray-300 rounded-lg shadow-xl flex items-center justify-center overflow-hidden">
              <img
                v-if="selectedBorrow.resource?.thumbnail_path"
                :src="`/storage/${selectedBorrow.resource.thumbnail_path}`"
                alt="Book Cover"
                class="w-full h-full object-cover"
              >
              <div
                v-else
                class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-6xl font-bold text-white"
              >
                {{ selectedBorrow.resource?.Resource_Name?.substring(0, 2).toUpperCase() || 'BOOK' }}
              </div>
            </div>
          </div>

          <!-- Right: Details -->
          <div class="flex-1 p-8 flex flex-col overflow-y-auto min-h-0">
            <h2 class="text-3xl kulim-park-bold font-bold text-gray-900 mb-6 leading-tight">
              {{ selectedBorrow.resource?.Resource_Name || 'Unknown Resource' }}
            </h2>

            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-2 text-yellow-500">
                <span class="text-4xl font-bold">★ {{ selectedBorrow.resource?.average_rating ?? '0.0' }}</span>
              </div>
              <div class="text-sm text-gray-500 font-medium">
                {{ selectedBorrow.resource?.views || 0 }} views
              </div>
            </div>

            <div class="space-y-3 mb-8 text-gray-800">
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Author</span>
                <span class="font-medium text-lg">{{ formatAuthors(selectedBorrow.resource?.authors) }}</span>
              </p>
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Originally published</span>
                <span class="font-medium text-lg">{{ selectedBorrow.resource?.formatted_publish_date ?? 'N/A' }}</span>
              </p>
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Borrowed</span>
                <span class="font-medium text-lg">{{ formatDate(selectedBorrow.Approved_Date) }}</span>
              </p>
            </div>

            <div class="mb-8">
              <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-3">Tags</p>
              <div class="flex flex-wrap gap-2">
                <template v-if="getTags(selectedBorrow.resource?.tags).length > 0">
                  <span
                    v-for="tag in getTags(selectedBorrow.resource?.tags)"
                    :key="tag"
                    class="bg-[#10B981] text-white px-3.5 py-1.5 rounded-full text-xs font-medium uppercase tracking-wide"
                  >
                    {{ tag }}
                  </span>
                </template>
                <span
                  v-else
                  class="text-gray-400 text-sm italic"
                >
                  No tags
                </span>
              </div>
            </div>

            <div class="flex-1 mb-10">
              <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-4">Description</p>
              <p
                v-if="selectedBorrow.resource?.Description"
                class="text-gray-700 text-sm leading-relaxed max-h-48 overflow-y-auto pr-2"
              >
                {{ selectedBorrow.resource.Description }}
              </p>
              <p
                v-else
                class="text-gray-400 text-sm italic"
              >
                No description available.
              </p>
            </div>

            <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
              <button
                type="button"
                @click="closeModal"
                class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-medium text-sm shadow-sm"
              >
                Close
              </button>
              <button
                v-if="selectedBorrow.Borrower_ID && selectedBorrow.Approved_Date && !selectedBorrow.isReturned"
                type="button"
                @click="returnBook"
                :disabled="isReturning"
                class="px-12 py-3 bg-gradient-to-r from-[#dc2626] to-[#b91c1c] text-white rounded-xl hover:from-[#b91c1c] hover:to-[#991b1b] transition-all font-medium text-sm shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ isReturning ? 'Returning...' : 'Return Book' }}
              </button>
              <a
                v-if="selectedBorrow.resource?.Resource_ID && selectedBorrow.Approved_Date"
                :href="`/viewer/${selectedBorrow.resource.Resource_ID}`"
                target="_blank"
                rel="noopener noreferrer"
                class="px-12 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl inline-block text-center no-underline"
                @click.stop
              >
                View Book
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Request Details Modal (Pending/Rejected) -->
    <div
      v-if="selectedRequest"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="closeRequestModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] my-4 overflow-y-auto mx-auto">
        <div class="flex h-full min-h-0">
          <!-- Left: Thumbnail -->
          <div class="w-72 flex-shrink-0 bg-gradient-to-br from-gray-100 to-gray-200 p-6 flex items-center justify-center border-r border-gray-200">
            <div class="w-60 h-96 bg-gray-100 border-8 border-gray-300 rounded-lg shadow-xl flex items-center justify-center overflow-hidden">
              <img
                v-if="selectedRequest.resource?.thumbnail_path"
                :src="`/storage/${selectedRequest.resource.thumbnail_path}`"
                alt="Book Cover"
                class="w-full h-full object-cover"
              >
              <div
                v-else
                class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-6xl font-bold text-white"
              >
                {{ selectedRequest.resource?.Resource_Name?.substring(0, 2).toUpperCase() || 'BOOK' }}
              </div>
            </div>
          </div>

          <!-- Right: Details -->
          <div class="flex-1 p-8 flex flex-col overflow-y-auto min-h-0">
            <h2 class="text-3xl kulim-park-bold font-bold text-gray-900 mb-6 leading-tight">
              {{ selectedRequest.resource?.Resource_Name || 'Unknown Resource' }}
            </h2>

            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-2 text-yellow-500">
                <span class="text-4xl font-bold">★ {{ selectedRequest.resource?.average_rating ?? '0.0' }}</span>
              </div>
              <div class="text-sm text-gray-500 font-medium">
                {{ selectedRequest.resource?.views || 0 }} views
              </div>
            </div>

            <div class="space-y-3 mb-8 text-gray-800">
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Author</span>
                <span class="font-medium text-lg">{{ formatAuthors(selectedRequest.resource?.authors) }}</span>
              </p>
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Originally published</span>
                <span class="font-medium text-lg">{{ selectedRequest.resource?.formatted_publish_date ?? 'N/A' }}</span>
              </p>
              <p class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Request Date</span>
                <span class="font-medium text-lg">{{ formatDate(selectedRequest.created_at) }}</span>
              </p>
              <p v-if="selectedRequest.Return_Date" class="flex items-center gap-3">
                <span class="font-semibold text-sm text-gray-600 uppercase tracking-wide">Expected Return</span>
                <span class="font-medium text-lg">{{ formatDate(selectedRequest.Return_Date) }}</span>
              </p>
            </div>

            <!-- Rejection Status Box -->
            <div
              v-if="selectedRequest.isRejected && selectedRequest.rejection_reason"
              class="mb-8 p-5 bg-red-50 border-2 border-red-300 rounded-xl"
            >
              <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                  <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="font-bold text-lg text-red-800 mb-2">Request Rejected</p>
                  <p class="text-red-700 font-medium">{{ selectedRequest.rejection_reason }}</p>
                </div>
              </div>
            </div>

            <div class="mb-8">
              <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-3">Tags</p>
              <div class="flex flex-wrap gap-2">
                <template v-if="getTags(selectedRequest.resource?.tags).length > 0">
                  <span
                    v-for="tag in getTags(selectedRequest.resource?.tags)"
                    :key="tag"
                    class="bg-[#10B981] text-white px-3.5 py-1.5 rounded-full text-xs font-medium uppercase tracking-wide"
                  >
                    {{ tag }}
                  </span>
                </template>
                <span
                  v-else
                  class="text-gray-400 text-sm italic"
                >
                  No tags
                </span>
              </div>
            </div>

            <div class="flex-1 mb-10">
              <p class="font-semibold text-gray-800 text-sm uppercase tracking-wide mb-4">Description</p>
              <p
                v-if="selectedRequest.resource?.Description"
                class="text-gray-700 text-sm leading-relaxed max-h-48 overflow-y-auto pr-2"
              >
                {{ selectedRequest.resource.Description }}
              </p>
              <p
                v-else
                class="text-gray-400 text-sm italic"
              >
                No description available.
              </p>
            </div>

            <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
              <button
                type="button"
                @click="closeRequestModal"
                class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-medium text-sm shadow-sm"
              >
                Close
              </button>
              <button
                v-if="selectedRequest.isRejected"
                type="button"
                @click="showReturnDateModal = true"
                class="px-8 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl"
              >
                Request Again
              </button>
              <button
                v-else
                type="button"
                @click="cancelRequest"
                :disabled="isCancelling"
                class="px-8 py-3 bg-gradient-to-r from-[#dc2626] to-[#b91c1c] text-white rounded-xl hover:from-[#b91c1c] hover:to-[#991b1b] transition-all font-medium text-sm shadow-lg hover:shadow-xl disabled:opacity-50"
              >
                {{ isCancelling ? 'Cancelling...' : 'Cancel Request' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Return Date Modal for Re-request -->
    <div
      v-if="showReturnDateModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
      @click.self="showReturnDateModal = false"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] my-4 overflow-y-auto mx-auto">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold kulim-park-bold">Select Return Date</h3>
        </div>
        <div class="p-6">
          <div class="mb-4">
            <label class="block font-medium mb-2">Expected Return Date</label>
            <input
              v-model="returnDate"
              type="datetime-local"
              :min="minReturnDate"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
              required
            >
            <p class="text-xs text-gray-500 mt-1">Please select when you plan to return this resource</p>
          </div>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-end gap-2">
          <button
            type="button"
            @click="showReturnDateModal = false; returnDate = ''"
            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
          >
            Cancel
          </button>
          <button
            type="button"
            @click="submitBorrowRequest"
            :disabled="!returnDate || isSubmittingBorrow"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
          >
            {{ isSubmittingBorrow ? 'Submitting...' : 'Confirm' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Rating Modal -->
    <div
      v-if="ratingBorrow"
      id="ratingModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
      @click.self="closeRatingModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] my-4 overflow-y-auto mx-auto">
        <div class="p-8">
          <h2 class="text-2xl kulim-park-bold font-bold text-gray-900 mb-6">
            Rate This Book
          </h2>
          
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">
              {{ ratingBorrow.resource?.Resource_Name || 'Unknown Resource' }}
            </h3>
            <p class="text-sm text-gray-600">
              by {{ formatAuthors(ratingBorrow.resource?.authors) }}
            </p>
          </div>

          <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-4">
              Your Rating
            </label>
            <div class="flex gap-2 justify-center">
              <button
                v-for="star in 5"
                :key="star"
                type="button"
                @click="selectedRating = star"
                class="text-5xl transition-all hover:scale-110"
                :class="star <= selectedRating ? 'text-yellow-400' : 'text-gray-300'"
              >
                ★
              </button>
            </div>
            <p class="text-center text-sm text-gray-600 mt-2">
              {{ selectedRating ? `${selectedRating} out of 5 stars` : 'Select a rating' }}
            </p>
          </div>

          <div class="flex gap-4 justify-end pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="closeRatingModal"
              class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-medium text-sm shadow-sm"
            >
              Cancel
            </button>
            <button
              type="button"
              @click="submitRating"
              :disabled="!selectedRating || isSubmittingRating"
              class="px-12 py-3 bg-gradient-to-r from-[#22C55E] to-[#16A34A] text-white rounded-xl hover:from-[#16A34A] hover:to-[#15803D] transition-all font-medium text-sm shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isSubmittingRating ? 'Submitting...' : 'Submit Rating' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, usePage, router, Head } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';

const props = defineProps({
  borrows: {
    type: Array,
    required: true,
  },
  ownedResources: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();
const userRole = computed(() => {
  return page.props.auth?.user?.role || null;
});

const selectedBorrow = ref(null);
const selectedRequest = ref(null);
const ratingBorrow = ref(null);
const selectedRating = ref(0);
const isSubmittingRating = ref(false);
const isReturning = ref(false);
const isCancelling = ref(false);
const showReturnDateModal = ref(false);
const returnDate = ref('');
const isSubmittingBorrow = ref(false);
const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content ?? '';

const requestBorrows = computed(() => {
  return props.borrows.filter(borrow => !borrow.Approved_Date);
});

const activeBorrows = computed(() => {
  // Only show active borrows that are approved and not returned
  return props.borrows.filter(borrow => 
    borrow.Approved_Date && 
    !borrow.isReturned && 
    !borrow.isRejected
  );
});

const returnedBorrows = computed(() => {
  // Show books that are actually returned (isReturned = true/1) and were approved
  return props.borrows.filter(borrow => {
    // Check if it's actually returned (handle both boolean and integer)
    const isReturned = borrow.isReturned === true || borrow.isReturned === 1;
    // Must have been approved and not rejected
    const wasApproved = borrow.Approved_Date && !borrow.isRejected;
    
    // Return if it's marked as returned AND was approved
    return isReturned && wasApproved;
  });
});

const formatDate = (date) => {
  if (!date) {
    return 'N/A';
  }
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

const formatAuthors = (authors) => {
  if (!authors) {
    return 'Unknown Author';
  }
  if (Array.isArray(authors)) {
    return authors.map(a => a.name || a).join(', ') || 'Unknown Author';
  }
  if (typeof authors === 'string') {
    return authors;
  }
  return 'Unknown Author';
};

const getTags = (tags) => {
  if (!tags) {
    return [];
  }
  if (Array.isArray(tags)) {
    return tags.map(t => {
      if (typeof t === 'string') {
        return t;
      }
      if (typeof t === 'object' && t !== null) {
        return t.name || t.Name || String(t);
      }
      return String(t);
    }).filter(Boolean);
  }
  return [];
};

const openBookModal = (borrow) => {
  selectedBorrow.value = borrow;
  document.body.style.overflow = 'hidden';
};

const openRequestModal = (borrow) => {
  selectedRequest.value = borrow;
  document.body.style.overflow = 'hidden';
};

const closeModal = () => {
  selectedBorrow.value = null;
  document.body.style.overflow = '';
};

const closeRequestModal = () => {
  selectedRequest.value = null;
  document.body.style.overflow = '';
};

const showRejectionReason = (borrow, event) => {
  if (event) {
    event.stopPropagation();
    event.preventDefault();
  }
  // Just open the main request modal - rejection reason will be shown there
  openRequestModal(borrow);
};

const cancelRequest = () => {
  if (!selectedRequest.value || !selectedRequest.value.Borrower_ID || isCancelling.value) {
    return;
  }

  if (!confirm('Are you sure you want to cancel this request?')) {
    return;
  }

  isCancelling.value = true;
  router.delete(`/borrow/cancel/${selectedRequest.value.Borrower_ID}`, {
    onFinish: () => {
      isCancelling.value = false;
    },
    onSuccess: () => {
      closeRequestModal();
      router.reload();
    },
  });
};

const minReturnDate = computed(() => {
  const now = new Date();
  now.setMinutes(now.getMinutes() + 1); // At least 1 minute from now
  // Format as datetime-local (YYYY-MM-DDTHH:mm)
  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, '0');
  const day = String(now.getDate()).padStart(2, '0');
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  return `${year}-${month}-${day}T${hours}:${minutes}`;
});

const submitBorrowRequest = () => {
  if (!selectedRequest.value || !returnDate.value || isSubmittingBorrow.value) {
    return;
  }

  const selectedDate = new Date(returnDate.value);
  const now = new Date();
  
  if (selectedDate <= now) {
    alert('Return date and time must be in the future');
    return;
  }

  isSubmittingBorrow.value = true;
  router.post('/borrow/request', {
    resource_id: selectedRequest.value.resource?.Resource_ID,
    return_date: returnDate.value,
  }, {
    onFinish: () => {
      isSubmittingBorrow.value = false;
    },
    onSuccess: (page) => {
      const successMessage = page?.props?.flash?.success;
      if (successMessage) {
        alert(successMessage);
      }
      showReturnDateModal.value = false;
      returnDate.value = '';
      closeRequestModal();
      router.reload();
    },
    onError: (errors) => {
      const messages = errors ? Object.values(errors).flat().join('\n') : 'Unable to submit borrow request. Please try again.';
      alert(messages);
    },
  });
};

const openRatingModal = (borrow) => {
  ratingBorrow.value = borrow;
  selectedRating.value = borrow.userRating || 0;
  document.body.style.overflow = 'hidden';
};

const closeRatingModal = () => {
  ratingBorrow.value = null;
  selectedRating.value = 0;
  document.body.style.overflow = '';
};

const returnBook = () => {
  if (!selectedBorrow.value || !selectedBorrow.value.Borrower_ID || isReturning.value) {
    return;
  }

  if (!confirm('Are you sure you want to return this book?')) {
    return;
  }

  isReturning.value = true;
  router.post(`/return/${selectedBorrow.value.Borrower_ID}`, {}, {
    onFinish: () => {
      isReturning.value = false;
    },
    onSuccess: () => {
      closeModal();
      // Refresh the page to update the borrow list
      router.reload();
    },
  });
};

const submitRating = () => {
  if (!ratingBorrow.value || !selectedRating.value || isSubmittingRating.value) {
    return;
  }

  isSubmittingRating.value = true;
  router.post('/ratings', {
    resource_id: ratingBorrow.value.resource.Resource_ID,
    rating: selectedRating.value,
  }, {
    onFinish: () => {
      isSubmittingRating.value = false;
    },
    onSuccess: () => {
      // Update the borrow with the new rating
      if (ratingBorrow.value) {
        ratingBorrow.value.userRating = selectedRating.value;
      }
      closeRatingModal();
      // Reload to get updated average rating
      router.reload({ only: ['borrows'] });
    },
  });
};

const approveBorrowRequest = (borrowerId) => {
  if (!confirm('Are you sure you want to approve this borrow request?')) {
    return;
  }

  router.post(`/borrow/approve/${borrowerId}`, {}, {
    onSuccess: () => {
      router.reload();
    },
  });
};

const rejectBorrowRequest = (borrowerId) => {
  const reason = prompt('Please provide a reason for rejection:');
  if (!reason || reason.trim() === '') {
    return;
  }

  router.post(`/borrow/reject/${borrowerId}`, {
    rejection_reason: reason,
  }, {
    onSuccess: () => {
      router.reload();
    },
  });
};

const handleEscape = (e) => {
  if (e.key === 'Escape') {
    if (selectedBorrow.value) {
      closeModal();
    }
    if (ratingBorrow.value) {
      closeRatingModal();
    }
  }
};

onMounted(() => {
  window.addEventListener('keydown', handleEscape);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleEscape);
  document.body.style.overflow = '';
});
</script>

<style scoped>
.books-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
  width: 100%;
}

@media (min-width: 640px) {
  .books-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 1024px) {
  .books-grid {
    grid-template-columns: repeat(5, 1fr);
  }
}

.book-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid #e5e7eb;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.book-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15), 0 4px 8px rgba(0, 0, 0, 0.1);
  border-color: #d1d5db;
}

.book-cover {
  width: 100%;
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.book-info {
  padding: 1rem;
}

.book-title {
  font-weight: 600;
  font-size: 0.875rem;
  margin-bottom: 0.5rem;
  line-height: 1.3;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
}

#shelfModal {
  backdrop-filter: blur(4px);
}

#shelfModal .overflow-y-auto::-webkit-scrollbar {
  width: 4px;
}

#shelfModal .overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

#shelfModal .overflow-y-auto::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 2px;
}

@media (max-width: 768px) {
  #shelfModal .bg-white {
    margin: 1rem;
    border-radius: 1rem;
  }
  #shelfModal .w-72 {
    width: 100%;
    height: 280px;
  }
}
</style>

