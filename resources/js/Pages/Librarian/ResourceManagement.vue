<template>
  <Head title="Resource Management" />
  <AppLayout title="Resource Management" content-padding-classes="px-[10%] lg:px-[10%]">
    <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
      <!-- Flash Messages -->
      <div
        v-if="flashSuccess"
        class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm"
      >
        {{ flashSuccess }}
      </div>
      <div
        v-if="flashError"
        class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm"
      >
        {{ flashError }}
      </div>

      <!-- Tabs -->
      <div class="flex gap-2 mb-4 border-b border-gray-200">
        <button
          @click="switchTab('Featured')"
          :class="[
            'px-4 py-2 font-medium transition-colors',
            currentType === 'Featured'
              ? 'border-b-2 border-green-600 text-green-600'
              : 'text-gray-600 hover:text-gray-800'
          ]"
        >
          Featured
        </button>
        <button
          @click="switchTab('Community Uploads')"
          :class="[
            'px-4 py-2 font-medium transition-colors',
            currentType === 'Community Uploads'
              ? 'border-b-2 border-green-600 text-green-600'
              : 'text-gray-600 hover:text-gray-800'
          ]"
        >
          Community Uploads
        </button>
        <button
          @click="currentType = 'Reports'"
          :class="[
            'px-4 py-2 font-medium transition-colors',
            currentType === 'Reports'
              ? 'border-b-2 border-green-600 text-green-600'
              : 'text-gray-600 hover:text-gray-800'
          ]"
        >
          Reports
        </button>
      </div>

      <!-- Search Bar and Add Resource Button -->
      <div class="flex gap-4 mb-4 items-center">
        <input
          v-model="searchTerm"
          type="text"
          placeholder="Search by title, author, or status..."
          class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
        >
        <button
          v-if="currentType === 'Featured' && permissions?.add"
          @click="openAddModal"
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 kantumruy-pro-regular"
        >
          Add Resource
        </button>
      </div>

      <!-- Reports Table -->
      <div v-if="currentType === 'Reports'" class="overflow-hidden">
        <table class="w-full bg-white rounded border border-gray-200 shadow-sm">
          <thead>
            <tr class="bg-gray-200">
              <th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold">Resource</th>
              <th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold">Reported By</th>
              <th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold">Reason</th>
              <th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold">Description</th>
              <th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold">Date</th>
              <th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="report in reports"
              :key="report.id"
              class="hover:bg-gray-50"
            >
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular">
                {{ report.resource_name }}
              </td>
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular">
                <div>
                  <div class="font-medium">{{ report.reporter_name }}</div>
                  <div class="text-xs text-gray-500">{{ report.reporter_email }}</div>
                </div>
              </td>
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular">
                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                  {{ report.reason }}
                </span>
              </td>
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular">
                <div class="max-w-xs truncate" :title="report.description">
                  {{ report.description }}
                </div>
              </td>
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular">
                {{ formatDate(report.created_at) }}
              </td>
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular">
                <div class="flex gap-2" @click.stop>
                  <button
                    @click="openPreviewModal(getResourceFilePath(report.resource_id))"
                    class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700"
                  >
                    View File
                  </button>
                  <button
                    @click="deleteReportedResource(report.resource_id, report.id)"
                    class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700"
                  >
                    Delete Resource
                  </button>
                  <button
                    @click="flagReportedAccount(report.reporter_email, report.id)"
                    class="px-3 py-1 bg-orange-600 text-white text-xs rounded hover:bg-orange-700"
                  >
                    Flag Account
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="!reports || reports.length === 0">
              <td colspan="6" class="py-12 text-center text-gray-500">
                No reports found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Resources Table -->
      <div v-else class="overflow-hidden">
        <table class="w-full bg-white rounded border border-gray-200 shadow-sm table-fixed">
          <thead>
            <tr class="bg-gray-200">
              <th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold w-[18%]">Title</th>
<th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold w-[14%]">Author(s)</th>
<th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold w-[16%]">Tags</th>
<th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold w-[12%]">Published Date</th>
<th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold w-[12%]">Upload Date</th>
              <th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold w-[14%]">Uploaded By</th>
              <th v-if="currentType === 'Community Uploads'" class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold w-[12%]">Approval Status</th>
              <th class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold w-[14%]">Status</th>
              <th v-if="currentType === 'Community Uploads'" class="py-3 px-4 border-b border-gray-400 text-left kantumruy-pro-regular font-semibold w-[12%]">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="resource in filteredResources"
              :key="resource.Resource_ID"
              class="hover:bg-gray-50 transition-colors"
              :class="{ 'cursor-pointer': permissions?.edit }"
              @click="permissions?.edit && openEditModal(resource)"
            >
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular">
                <div class="flex items-center gap-2">
                  <div class="flex-1 min-w-0">
                    <div class="truncate font-medium" :title="resource.Resource_Name">
                      {{ resource.Resource_Name }}
                    </div>
                  </div>
                  <button
                    class="text-green-700 hover:text-green-800 hover:no-underline text-sm font-medium whitespace-nowrap flex-shrink-0 px-2 py-1 rounded hover:bg-green-50"
                    @click.stop="openPreviewModal(resource.File_Path)"
                  >
                    View File
                  </button>
                </div>
              </td>
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular tracking-tight">
                <div class="truncate" :title="resource.authors">
                  {{ resource.authors }}
                </div>
              </td>
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular tracking-tight">
                <div class="flex flex-wrap gap-1 max-w-xs">
                  <span
                    v-for="tag in (resource.tags || []).slice(0, 3)"
                    :key="tag"
                    class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full whitespace-nowrap"
                  >
                    {{ tag }}
                  </span>
                  <span v-if="resource.tags && resource.tags.length > 3" class="text-gray-400 text-xs">
                    +{{ resource.tags.length - 3 }} more
                  </span>
                  <span v-if="!resource.tags || resource.tags.length === 0" class="text-gray-400 text-xs">No tags</span>
                </div>
              </td>
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular tracking-tight">
                {{ resource.formatted_publish_date ?? 'N/A' }}
              </td>
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular tracking-tight">
                {{ formatDate(resource.created_at) }}
              </td>
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular tracking-tight">
                <div class="truncate" :title="resource.uploaded_by">
                  {{ resource.uploaded_by }}
                </div>
              </td>
              <td v-if="currentType === 'Community Uploads'" class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular tracking-tight">
                <span
                  :class="{
                    'px-2 py-1 rounded-full text-xs font-medium': true,
                    'bg-yellow-100 text-yellow-800': resource.approval_status === 'pending',
                    'bg-green-100 text-green-800': resource.approval_status === 'approved',
                    'bg-red-100 text-red-800': resource.approval_status === 'rejected',
                  }"
                >
                  {{ resource.approval_status || 'approved' }}
                </span>
              </td>
              <td class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular tracking-tight">
                <span
                  :class="[
                    'px-2 py-1 text-xs rounded-full font-medium',
                    resource.status === 'Available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                  ]"
                >
                  {{ resource.status }}
                </span>
              </td>
              <td v-if="currentType === 'Community Uploads'" class="py-3 px-6 border-b border-gray-300 kantumruy-pro-regular tracking-tight">
                <div class="flex gap-2" @click.stop>
                  <button
                    v-if="resource.approval_status === 'pending'"
                    @click="openApprovalModal(resource)"
                    class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700"
                  >
                    Review
                  </button>
                  <button
                    v-else
                    @click="openApprovalModal(resource)"
                    class="px-3 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700"
                  >
                    View
                  </button>
                  <button
                    v-if="resource.approval_status === 'approved' && permissions?.delete"
                    @click="deleteCommunityResource(resource.Resource_ID)"
                    class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700"
                  >
                    Delete
                  </button>
                  <button
                    v-if="resource.approval_status === 'approved' && permissions?.delete"
                    @click="flagCommunityResource(resource.Resource_ID)"
                    class="px-3 py-1 bg-orange-600 text-white text-xs rounded hover:bg-orange-700"
                  >
                    Flag
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredResources.length === 0">
              <td colspan="6" class="py-12 text-center text-gray-500">
                No resources found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="resources.links && resources.links.length > 3" class="mt-4 flex justify-center gap-2">
        <Link
          v-for="link in resources.links"
          :key="link.label"
          :href="link.url"
          :class="[
            'px-4 py-2 rounded',
            link.active ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
            !link.url ? 'opacity-50 cursor-not-allowed' : ''
          ]"
          v-html="link.label"
        />
      </div>
    </div>

    <!-- Add Resource Modal -->
    <div
      v-if="showAddModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="closeAddModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] my-4 overflow-y-auto mx-auto">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold kulim-park-bold">Add New Resource</h3>
        </div>
        <form @submit.prevent="submitAddForm" class="p-6 overflow-y-auto max-h-[70vh]">
          <div class="space-y-4">
            <div>
              <label class="block font-medium mb-1">Title</label>
              <input
                v-model="addForm.Resource_Name"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                required
              >
            </div>
            <div>
              <label class="block font-medium mb-1">Author(s) (comma-separated)</label>
              <input
                v-model="addForm.authors"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                required
              >
            </div>
            <div>
              <label class="block font-medium mb-1">Description</label>
              <textarea
                v-model="addForm.Description"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                rows="4"
                required
              />
            </div>
            <div>
              <label class="block font-medium mb-1">Tags (comma-separated)</label>
              <input
                v-model="addForm.tags"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="e.g., science, research, academic"
              >
            </div>
            <div>
              <label class="block font-medium mb-1">Publish Date</label>
              <div class="flex gap-2">
                <select
                  v-model="addForm.publish_year"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                  <option value="">Year (optional)</option>
                  <option
                    v-for="year in years"
                    :key="year"
                    :value="year"
                  >
                    {{ year }}
                  </option>
                </select>
                <select
                  v-if="addForm.publish_year"
                  v-model="addForm.publish_month"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                  <option value="">Month</option>
                  <option
                    v-for="(month, index) in months"
                    :key="index"
                    :value="index + 1"
                  >
                    {{ month }}
                  </option>
                </select>
                <select
                  v-if="addForm.publish_month"
                  v-model="addForm.publish_day"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                  <option value="">Day</option>
                  <option
                    v-for="day in days"
                    :key="day"
                    :value="day"
                  >
                    {{ day }}
                  </option>
                </select>
              </div>
            </div>
            <div>
              <label class="block font-medium mb-1">File</label>
              <div
                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-green-500 transition-colors"
                @click="$refs.addFileInput.click()"
                @dragover.prevent="addDragOver = true"
                @dragleave.prevent="addDragOver = false"
                @drop.prevent="handleAddFileDrop"
                :class="{ 'border-green-500 bg-green-50': addDragOver }"
              >
                <p v-if="!addForm.file">Drag & drop file here or click to upload</p>
                <p v-else class="text-green-700 font-medium">{{ addForm.file.name }}</p>
              </div>
              <input
                ref="addFileInput"
                type="file"
                class="hidden"
                accept=".pdf,.doc,.docx,.zip"
                @change="handleAddFileSelect"
              >
            </div>
          </div>
          <div class="flex gap-2 justify-end mt-6 pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="closeAddModal"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
            >
              {{ isSubmitting ? 'Uploading...' : 'Upload' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Resource Modal -->
    <div
      v-if="showEditModal && selectedResource"
      class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/50"
      @click.self="closeEditModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold kulim-park-bold">Edit Resource</h3>
        </div>
        <form @submit.prevent="submitEditForm" class="p-6 overflow-y-auto max-h-[70vh]">
          <div class="space-y-4">
            <div>
              <label class="block font-medium mb-1">Title</label>
              <input
                v-model="editForm.Resource_Name"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                required
              >
            </div>
            <div>
              <label class="block font-medium mb-1">Author(s) (comma-separated)</label>
              <input
                v-model="editForm.authors"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                required
              >
            </div>
            <div>
              <label class="block font-medium mb-1">Description</label>
              <textarea
                v-model="editForm.Description"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                rows="4"
                required
              />
            </div>
            <div>
              <label class="block font-medium mb-1">Tags (press Space or + to add)</label>
              <div class="flex flex-wrap gap-1.5 mb-2">
                <span
                v-for="(tag, index) in editFormTagsArray"
                :key="index"
                class="inline-flex items-center px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs"
              >
                {{ tag }}
                <span
                  @click.stop="removeEditTag(index)"
                  class="ml-1 text-green-600 hover:text-green-900 cursor-pointer leading-none"
                  title="Remove tag"
                >×</span>
              </span>
              </div>
              <input
                v-model="editFormTagsInput"
                type="text"
                placeholder="Type tag and press Space or +"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                @keydown="handleEditTagKeydown"
              >
            </div>
            <div>
              <label class="block font-medium mb-1">Status</label>
              <select
                v-model="editForm.status"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                required
              >
                <option value="Available">Available</option>
                <option value="Unavailable">Unavailable</option>
              </select>
            </div>
            <div>
              <label class="block font-medium mb-1">Publish Date</label>
              <div class="flex gap-2">
                <select
                  v-model="editForm.publish_year"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                  <option value="">Year (optional)</option>
                  <option
                    v-for="year in years"
                    :key="year"
                    :value="year"
                  >
                    {{ year }}
                  </option>
                </select>
                <select
                  v-if="editForm.publish_year"
                  v-model="editForm.publish_month"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                  <option value="">Month</option>
                  <option
                    v-for="(month, index) in months"
                    :key="index"
                    :value="index + 1"
                  >
                    {{ month }}
                  </option>
                </select>
                <select
                  v-if="editForm.publish_month"
                  v-model="editForm.publish_day"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                  <option value="">Day</option>
                  <option
                    v-for="day in days"
                    :key="day"
                    :value="day"
                  >
                    {{ day }}
                  </option>
                </select>
              </div>
            </div>
            <div>
              <label class="block font-medium mb-1">File (leave empty to keep current)</label>
              <div
                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-green-500 transition-colors"
                @click="$refs.editFileInput.click()"
                @dragover.prevent="editDragOver = true"
                @dragleave.prevent="editDragOver = false"
                @drop.prevent="handleEditFileDrop"
                :class="{ 'border-green-500 bg-green-50': editDragOver }"
              >
                <p v-if="!editForm.file">Click to upload new file (optional)</p>
                <p v-else class="text-green-700 font-medium">{{ editForm.file.name }}</p>
              </div>
              <input
                ref="editFileInput"
                type="file"
                class="hidden"
                accept=".pdf,.doc,.docx,.zip"
                @change="handleEditFileSelect"
              >
            </div>
          </div>
          <div class="flex gap-2 justify-end mt-6 pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="closeEditModal"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
            >
              Cancel
            </button>
            <button
              v-if="permissions?.delete"
              type="button"
              @click="confirmDelete"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
            >
              Delete
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
            >
              {{ isSubmitting ? 'Updating...' : 'Update' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Preview Modal -->
    <div
      v-if="previewUrl"
      class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4 overflow-y-auto"
    >
      <!-- Modal Container -->
      <div class="relative bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] my-4 overflow-y-auto mx-auto">
        <!-- Close Button - Top Right Corner of Modal -->
        <button
          @click.stop="closePreviewModal"
          class="absolute top-2 right-2 z-20 bg-white text-gray-700 rounded-full p-2 hover:bg-gray-100 shadow-lg border border-gray-200 transition-all"
          aria-label="Close preview"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        
        <!-- Document Preview -->
        <iframe
          :src="previewUrl"
          class="w-full h-[90vh] border-0"
        />
      </div>
    </div>

    <!-- Approval Modal -->
    <div
      v-if="showApprovalModal && selectedApprovalResource"
      class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="closeApprovalModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl my-4 mx-auto max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
          <h3 class="text-2xl font-bold kulim-park-bold">Review Community Upload</h3>
          <button
            @click="closeApprovalModal"
            class="text-gray-500 hover:text-gray-700 text-2xl"
          >
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="p-6 space-y-4">
          <div>
            <label class="block font-medium mb-1">Title</label>
            <p class="text-gray-700">{{ selectedApprovalResource.Resource_Name }}</p>
          </div>
          <div>
            <label class="block font-medium mb-1">Author(s)</label>
            <p class="text-gray-700">{{ selectedApprovalResource.authors }}</p>
          </div>
          <div>
            <label class="block font-medium mb-1">Description</label>
            <p class="text-gray-700">{{ selectedApprovalResource.Description }}</p>
          </div>
          <div>
            <label class="block font-medium mb-1">Uploaded By</label>
            <p class="text-gray-700">{{ selectedApprovalResource.uploaded_by }}</p>
          </div>
          <div>
            <label class="block font-medium mb-1">Current Approval Status</label>
            <span
              :class="{
                'px-3 py-1 rounded-full text-sm font-medium': true,
                'bg-yellow-100 text-yellow-800': selectedApprovalResource.approval_status === 'pending',
                'bg-green-100 text-green-800': selectedApprovalResource.approval_status === 'approved',
                'bg-red-100 text-red-800': selectedApprovalResource.approval_status === 'rejected',
              }"
            >
              {{ selectedApprovalResource.approval_status || 'approved' }}
            </span>
          </div>
          <div class="flex gap-2 justify-end pt-4 border-t border-gray-200">
            <button
              @click="closeApprovalModal"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
            >
              Close
            </button>
            <button
              v-if="selectedApprovalResource.approval_status === 'pending'"
              @click="rejectCommunityUpload"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
            >
              Reject
            </button>
            <button
              v-if="selectedApprovalResource.approval_status === 'pending'"
              @click="approveCommunityUpload"
              :disabled="isProcessingApproval"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
            >
              {{ isProcessingApproval ? 'Processing...' : 'Approve' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue';
import { router, usePage, Link, Head } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

const props = defineProps({
  resources: {
    type: Object,
    required: true,
  },
  reports: {
    type: Array,
    default: () => [],
  },
  permissions: {
    type: Object,
    default: () => ({ add: false, edit: false, delete: false, archive: false }),
  },
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success ?? null);
const flashError = computed(() => page.props.flash?.error ?? null);

const searchTerm = ref('');
const showAddModal = ref(false);
const showEditModal = ref(false);
const selectedResource = ref(null);
const previewUrl = ref(null);
const isSubmitting = ref(false);
const addDragOver = ref(false);
const editDragOver = ref(false);

// Tab and Approval
const currentType = ref(props.currentType || 'Featured');
const showApprovalModal = ref(false);
const selectedApprovalResource = ref(null);
const isProcessingApproval = ref(false);

const addForm = ref({
  Resource_Name: '',
  authors: '',
  Description: '',
  tags: '',
  publish_year: '',
  publish_month: '',
  publish_day: '',
  file: null,
});

const editForm = ref({
  Resource_Name: '',
  authors: '',
  Description: '',
  tags: '',
  status: 'Available',
  publish_year: '',
  publish_month: '',
  publish_day: '',
  file: null,
});

const editFormTagsInput = ref('');
const editFormTagsArray = computed({
  get: () => {
    if (!editForm.value.tags) return [];
    return editForm.value.tags.split(',').map(t => t.trim()).filter(t => t);
  },
  set: (tags) => {
    editForm.value.tags = tags.join(', ');
  }
});

const handleEditTagKeydown = (event) => {
  if ((event.key === ' ' || event.key === '+' || event.key === 'Enter') && editFormTagsInput.value.trim()) {
    event.preventDefault();
    const newTag = editFormTagsInput.value.trim();
    if (newTag && !editFormTagsArray.value.includes(newTag)) {
      editFormTagsArray.value = [...editFormTagsArray.value, newTag];
    }
    editFormTagsInput.value = '';
  }
};

const removeEditTag = (index) => {
  const newTags = [...editFormTagsArray.value];
  newTags.splice(index, 1);
  editFormTagsArray.value = newTags;
};

const years = computed(() => {
  const currentYear = new Date().getFullYear();
  const yearsList = [];
  for (let year = currentYear + 5; year >= 1900; year--) {
    yearsList.push(year);
  }
  return yearsList;
});

const months = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December',
];

const days = computed(() => {
  const daysList = [];
  for (let day = 1; day <= 31; day++) {
    daysList.push(day);
  }
  return daysList;
});

// Tab switching
const switchTab = (type) => {
  currentType.value = type;
  if (type !== 'Reports') {
    router.get('/resource-management', { type }, {
      preserveState: true,
      preserveScroll: true,
    });
  }
};

const getResourceFilePath = (resourceId) => {
  // Try to find in reports first
  const report = props.reports?.find(r => r.resource_id === resourceId);
  if (report?.resource_file_path) {
    return report.resource_file_path;
  }
  // Fallback to resources data
  const resource = props.resources.data?.find(r => r.Resource_ID === resourceId);
  return resource?.File_Path || null;
};

const deleteReportedResource = (resourceId, reportId) => {
  if (!confirm('Are you sure you want to delete this resource? This action cannot be undone.')) {
    return;
  }

  router.delete(`/resources/${resourceId}`, {
    onSuccess: () => {
      router.reload();
    },
  });
};

const flagReportedAccount = (email, reportId) => {
  if (!confirm('Are you sure you want to flag this account for violating community standards?')) {
    return;
  }

  // TODO: Implement account flagging logic
  alert('Account flagging feature to be implemented');
};

const deleteCommunityResource = (resourceId) => {
  if (!confirm('Are you sure you want to delete this community upload?')) {
    return;
  }

  router.delete(`/resources/${resourceId}`, {
    onSuccess: () => {
      router.reload();
    },
  });
};

const flagCommunityResource = (resourceId) => {
  if (!confirm('Are you sure you want to flag this resource and its owner?')) {
    return;
  }

  // TODO: Implement resource flagging logic
  alert('Resource flagging feature to be implemented');
};

// Approval functions
const openApprovalModal = (resource) => {
  selectedApprovalResource.value = resource;
  showApprovalModal.value = true;
  document.body.style.overflow = 'hidden';
};

const closeApprovalModal = () => {
  showApprovalModal.value = false;
  selectedApprovalResource.value = null;
  document.body.style.overflow = '';
};

const approveCommunityUpload = () => {
  if (!selectedApprovalResource.value || !confirm('Are you sure you want to approve this community upload?')) {
    return;
  }
  
  isProcessingApproval.value = true;
  router.post(`/resources/${selectedApprovalResource.value.Resource_ID}/approve-community`, {}, {
    onFinish: () => {
      isProcessingApproval.value = false;
    },
    onSuccess: () => {
      closeApprovalModal();
      router.reload({ only: ['resources'] });
    },
  });
};

const rejectCommunityUpload = () => {
  if (!selectedApprovalResource.value || !confirm('Are you sure you want to reject this community upload?')) {
    return;
  }
  
  isProcessingApproval.value = true;
  router.post(`/resources/${selectedApprovalResource.value.Resource_ID}/reject-community`, {}, {
    onFinish: () => {
      isProcessingApproval.value = false;
    },
    onSuccess: () => {
      closeApprovalModal();
      router.reload({ only: ['resources'] });
    },
  });
};

const filteredResources = computed(() => {
  if (!searchTerm.value) {
    return props.resources.data || [];
  }
  const term = searchTerm.value.toLowerCase();
  return (props.resources.data || []).filter(resource => {
    const title = resource.Resource_Name?.toLowerCase() ?? '';
    const authors = resource.authors?.toLowerCase() ?? '';
    const status = resource.status?.toLowerCase() ?? '';
    return title.includes(term) || authors.includes(term) || status.includes(term);
  });
});

const formatDate = (date) => {
  if (!date) {
    return 'N/A';
  }
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' });
};

const openAddModal = () => {
  showAddModal.value = true;
  addForm.value = {
    Resource_Name: '',
    authors: '',
    Description: '',
    tags: '',
    publish_year: '',
    publish_month: '',
    publish_day: '',
    file: null,
  };
};

const closeAddModal = () => {
  showAddModal.value = false;
  addForm.value = {
    Resource_Name: '',
    authors: '',
    Description: '',
    tags: '',
    publish_year: '',
    publish_month: '',
    publish_day: '',
    file: null,
  };
};

const openEditModal = (resource) => {
  selectedResource.value = resource;
  editForm.value = {
    Resource_Name: resource.Resource_Name,
    authors: resource.authors,
    Description: resource.Description,
    tags: resource.tags?.join(', ') || '',
    status: resource.status,
    publish_year: resource.publish_year || '',
    publish_month: resource.publish_month || '',
    publish_day: resource.publish_day || '',
    file: null,
  };
  editFormTagsInput.value = '';
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
  selectedResource.value = null;
  editForm.value = {
    Resource_Name: '',
    authors: '',
    Description: '',
    tags: '',
    status: 'Available',
    publish_year: '',
    publish_month: '',
    publish_day: '',
    file: null,
  };
};

const openPreviewModal = (filePath) => {
  if (!filePath) {
    alert('File path not available');
    return;
  }
  previewUrl.value = `/storage/${filePath}`;
};

const closePreviewModal = () => {
  previewUrl.value = null;
};

const handleAddFileSelect = (event) => {
  if (event.target.files && event.target.files[0]) {
    addForm.value.file = event.target.files[0];
    // Auto-fill title from filename
    if (!addForm.value.Resource_Name) {
      const filename = event.target.files[0].name;
      addForm.value.Resource_Name = filename.replace(/\.[^/.]+$/, '');
    }
  }
};

const handleAddFileDrop = (event) => {
  addDragOver.value = false;
  if (event.dataTransfer.files && event.dataTransfer.files[0]) {
    addForm.value.file = event.dataTransfer.files[0];
    if (!addForm.value.Resource_Name) {
      const filename = event.dataTransfer.files[0].name;
      addForm.value.Resource_Name = filename.replace(/\.[^/.]+$/, '');
    }
  }
};

const handleEditFileSelect = (event) => {
  if (event.target.files && event.target.files[0]) {
    editForm.value.file = event.target.files[0];
  }
};

const handleEditFileDrop = (event) => {
  editDragOver.value = false;
  if (event.dataTransfer.files && event.dataTransfer.files[0]) {
    editForm.value.file = event.dataTransfer.files[0];
  }
};

const submitAddForm = () => {
  if (!addForm.value.file) {
    alert('Please select a file');
    return;
  }

  isSubmitting.value = true;
  const formData = new FormData();
  formData.append('Resource_Name', addForm.value.Resource_Name);
  formData.append('authors', addForm.value.authors);
  formData.append('Description', addForm.value.Description);
  formData.append('tags', addForm.value.tags || '');
  if (addForm.value.publish_year) formData.append('publish_year', addForm.value.publish_year);
  if (addForm.value.publish_month) formData.append('publish_month', addForm.value.publish_month);
  if (addForm.value.publish_day) formData.append('publish_day', addForm.value.publish_day);
  formData.append('file', addForm.value.file);

  router.post('/resources', formData, {
    forceFormData: true,
    onFinish: () => {
      isSubmitting.value = false;
    },
    onSuccess: () => {
      closeAddModal();
    },
  });
};

const submitEditForm = () => {
  // Ensure tags are properly formatted before submission
  if (editFormTagsArray.value.length > 0) {
    editForm.value.tags = editFormTagsArray.value.join(', ');
  } else {
    editForm.value.tags = '';
  }
  if (!selectedResource.value) {
    return;
  }

  isSubmitting.value = true;
  const formData = new FormData();
  formData.append('Resource_Name', editForm.value.Resource_Name);
  formData.append('authors', editForm.value.authors);
  formData.append('Description', editForm.value.Description);
  formData.append('tags', editForm.value.tags || '');
  formData.append('status', editForm.value.status);
  if (editForm.value.publish_year) formData.append('publish_year', editForm.value.publish_year);
  if (editForm.value.publish_month) formData.append('publish_month', editForm.value.publish_month);
  if (editForm.value.publish_day) formData.append('publish_day', editForm.value.publish_day);
  if (editForm.value.file) formData.append('file', editForm.value.file);
  formData.append('_method', 'PATCH');

  router.post(`/resources/${selectedResource.value.Resource_ID}`, formData, {
    forceFormData: true,
    onFinish: () => {
      isSubmitting.value = false;
    },
    onSuccess: () => {
      closeEditModal();
    },
  });
};

const confirmDelete = () => {
  if (!selectedResource.value || !confirm('Are you sure you want to delete this resource? This action cannot be undone.')) {
    return;
  }

  router.delete(`/resources/${selectedResource.value.Resource_ID}`, {
    onSuccess: () => {
      closeEditModal();
    },
  });
};

onMounted(() => {
  // Handle escape key
  const handleEscape = (e) => {
    if (e.key === 'Escape') {
      if (showAddModal.value) closeAddModal();
      if (showEditModal.value) closeEditModal();
      if (previewUrl.value) closePreviewModal();
    }
  };
  window.addEventListener('keydown', handleEscape);
  return () => window.removeEventListener('keydown', handleEscape);
});
</script>

<style scoped>
.kantumruy-pro-regular {
  font-family: 'Kantumruy Pro', sans-serif;
}
.kulim-park-bold {
  font-family: 'Kulim Park', sans-serif;
  font-weight: 700;
}

/* Truncate text with fade effect */
.truncate {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  max-width: 100%;
}

.truncate::after {
  content: '';
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  width: 40px;
  background: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
  pointer-events: none;
}
</style>

