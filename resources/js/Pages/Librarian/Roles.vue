<template>
  <Head title="Roles Management" />
  <AppLayout title="Roles Management" content-padding-classes="px-[10%] lg:px-[10%]">
    <div class="rounded-2xl bg-white border border-gray-200 shadow-lg p-6">
      <div v-if="!isUniversityLibrarian" class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <p class="text-yellow-800 text-sm">
          You have view-only access. Only University Librarians can manage roles.
        </p>
      </div>

      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold kulim-park-bold">Librarian Positions</h2>
        <button
          v-if="isUniversityLibrarian"
          @click="openAddModal"
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
        >
          Add Position
        </button>
      </div>

      <!-- Positions Table -->
      <div class="overflow-x-auto mb-8">
        <table class="w-full bg-white rounded border border-gray-200 shadow-sm">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-3 text-left">Position Name</th>
              <th class="p-3 text-left">Permissions</th>
              <th class="p-3 text-left">Librarians</th>
              <th class="p-3 text-left">Created By</th>
              <th v-if="isUniversityLibrarian" class="p-3 text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="position in positions"
              :key="position.id"
              class="border-b border-gray-300 hover:bg-gray-50"
            >
              <td class="p-3">
                {{ position.name }}
                <span
                  v-if="position.is_protected"
                  class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded"
                >
                  Protected
                </span>
              </td>
              <td class="p-3 text-sm">
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="(value, key) in position.permissions"
                    :key="key"
                    :class="[
                      'px-2 py-1 rounded text-xs',
                      value ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'
                    ]"
                  >
                    {{ key }}: {{ value ? 'Yes' : 'No' }}
                  </span>
                </div>
              </td>
              <td class="p-3">{{ position.librarians_count }}</td>
              <td class="p-3">{{ position.created_by }}</td>
              <td v-if="isUniversityLibrarian" class="p-3">
                <button
                  v-if="!position.is_protected"
                  @click="openEditModal(position)"
                  class="text-blue-600 hover:text-blue-800 mr-2"
                >
                  Edit
                </button>
                <button
                  v-if="!position.is_protected"
                  @click="confirmDelete(position)"
                  class="text-red-600 hover:text-red-800"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Librarians Table -->
      <h2 class="text-2xl font-bold kulim-park-bold mb-4">Librarians</h2>
      <div class="overflow-x-auto">
        <table class="w-full bg-white rounded border border-gray-200 shadow-sm">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-3 text-left">Name</th>
              <th class="p-3 text-left">Email</th>
              <th class="p-3 text-left">Campus</th>
              <th class="p-3 text-left">Position</th>
              <th v-if="isUniversityLibrarian" class="p-3 text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="librarian in librarians"
              :key="librarian.id"
              class="border-b border-gray-300 hover:bg-gray-50"
            >
              <td class="p-3">{{ librarian.name }}</td>
              <td class="p-3">{{ librarian.email }}</td>
              <td class="p-3">{{ librarian.campus }}</td>
              <td class="p-3">{{ librarian.position }}</td>
              <td v-if="isUniversityLibrarian" class="p-3">
                <button
                  @click="openAssignModal(librarian)"
                  :disabled="librarian.id === currentUserId && librarian.position === 'University Librarian'"
                  :class="[
                    'text-green-600 hover:text-green-800',
                    { 'opacity-50 cursor-not-allowed': librarian.id === currentUserId && librarian.position === 'University Librarian' }
                  ]"
                  :title="librarian.id === currentUserId && librarian.position === 'University Librarian' ? 'Cannot assign role to yourself. Transfer head role to another librarian first.' : ''"
                >
                  Assign Role
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add/Edit Position Modal -->
    <div
      v-if="showPositionModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="closePositionModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] my-4 overflow-y-auto mx-auto">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold kulim-park-bold">
            {{ editingPosition ? 'Edit Position' : 'Add New Position' }}
          </h3>
        </div>
        <form @submit.prevent="submitPositionForm" class="p-6">
          <div class="space-y-4">
            <div>
              <label class="block font-medium mb-1">Position Name</label>
              <input
                v-model="positionForm.name"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                required
                :disabled="editingPosition?.is_protected"
              >
            </div>
            <div>
              <label class="block font-medium mb-2">Permissions</label>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input
                    v-model="positionForm.permissions.add"
                    type="checkbox"
                    class="mr-2"
                    :disabled="editingPosition?.is_protected"
                  >
                  <span>Add Resources</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="positionForm.permissions.edit"
                    type="checkbox"
                    class="mr-2"
                    :disabled="editingPosition?.is_protected"
                  >
                  <span>Edit Resources</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="positionForm.permissions.archive"
                    type="checkbox"
                    class="mr-2"
                    :disabled="editingPosition?.is_protected"
                  >
                  <span>Archive Resources</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="positionForm.permissions.delete"
                    type="checkbox"
                    class="mr-2"
                    :disabled="editingPosition?.is_protected"
                  >
                  <span>Delete Resources</span>
                </label>
              </div>
            </div>
          </div>
          <div class="flex gap-2 justify-end mt-6 pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="closePositionModal"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isSubmitting || editingPosition?.is_protected"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
            >
              {{ isSubmitting ? 'Saving...' : 'Save' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Assign Role Modal -->
    <div
      v-if="showAssignModal && selectedLibrarian"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="closeAssignModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] my-4 overflow-y-auto mx-auto">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold kulim-park-bold">Assign Role to {{ selectedLibrarian.name }}</h3>
        </div>
        <form @submit.prevent="submitAssignForm" class="p-6">
          <div class="space-y-4">
            <div
              v-if="selectedLibrarian.id === currentUserId && selectedLibrarian.position === 'University Librarian'"
              class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-4"
            >
              <p class="text-yellow-800 text-sm font-medium">
                ⚠️ You cannot assign a role to yourself. To change your role, you must first transfer the University Librarian position to another librarian.
              </p>
            </div>
            <div>
              <label class="block font-medium mb-1">Position</label>
              <select
                v-model="assignForm.position_id"
                :disabled="selectedLibrarian.id === currentUserId && selectedLibrarian.position === 'University Librarian'"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                required
              >
                <option value="">Select Position</option>
                <option
                  v-for="position in positions"
                  :key="position.id"
                  :value="position.id"
                >
                  {{ position.name }}
                </option>
              </select>
            </div>
            <div
              v-if="isTransferringHeadRole"
              class="p-4 bg-blue-50 border border-blue-200 rounded-lg"
            >
              <p class="text-blue-800 text-sm font-medium">
                ⚠️ You are transferring the University Librarian role. This will remove your University Librarian privileges. Are you sure?
              </p>
            </div>
          </div>
          <div class="flex gap-2 justify-end mt-6 pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="closeAssignModal"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isSubmitting || (selectedLibrarian.id === currentUserId && selectedLibrarian.position === 'University Librarian' && !isTransferringHeadRole)"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
            >
              {{ isSubmitting ? 'Assigning...' : 'Assign' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, usePage, Head } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

const props = defineProps({
  positions: {
    type: Array,
    required: true,
  },
  librarians: {
    type: Array,
    required: true,
  },
  isUniversityLibrarian: {
    type: Boolean,
    default: false,
  },
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success ?? null);
const currentUserId = computed(() => page.props.auth?.user?.id ?? null);
const showPositionModal = ref(false);
const showAssignModal = ref(false);
const editingPosition = ref(null);
const selectedLibrarian = ref(null);
const isSubmitting = ref(false);

const positionForm = ref({
  name: '',
  permissions: {
    add: false,
    edit: false,
    archive: false,
    delete: false,
  },
});

const assignForm = ref({
  position_id: '',
});

const openAddModal = () => {
  editingPosition.value = null;
  positionForm.value = {
    name: '',
    permissions: {
      add: false,
      edit: false,
      archive: false,
      delete: false,
    },
  };
  showPositionModal.value = true;
};

const openEditModal = (position) => {
  editingPosition.value = position;
  positionForm.value = {
    name: position.name,
    permissions: { ...position.permissions },
  };
  showPositionModal.value = true;
};

const closePositionModal = () => {
  showPositionModal.value = false;
  editingPosition.value = null;
};

const openAssignModal = (librarian) => {
  selectedLibrarian.value = librarian;
  assignForm.value = {
    position_id: librarian.position_id || '',
  };
  showAssignModal.value = true;
};

const closeAssignModal = () => {
  showAssignModal.value = false;
  selectedLibrarian.value = null;
};

const submitPositionForm = () => {
  if (!props.isUniversityLibrarian) {
    return;
  }

  isSubmitting.value = true;
  const url = editingPosition.value
    ? `/admin/positions/${editingPosition.value.id}`
    : '/admin/positions';

  const method = editingPosition.value ? 'PATCH' : 'POST';

  const formData = {
    name: positionForm.value.name,
    'permissions.add': positionForm.value.permissions.add,
    'permissions.edit': positionForm.value.permissions.edit,
    'permissions.archive': positionForm.value.permissions.archive,
    'permissions.delete': positionForm.value.permissions.delete,
  };

  if (method === 'PATCH') {
    router.patch(url, formData, {
      onFinish: () => {
        isSubmitting.value = false;
      },
      onSuccess: () => {
        closePositionModal();
      },
    });
  } else {
    router.post(url, formData, {
      onFinish: () => {
        isSubmitting.value = false;
      },
      onSuccess: () => {
        closePositionModal();
      },
    });
  }
};

const isTransferringHeadRole = computed(() => {
  if (!selectedLibrarian.value || !currentUserId.value || !assignForm.value.position_id) {
    return false;
  }
  // Check if current user is University Librarian and assigning University Librarian role to someone else
  const selectedPosition = props.positions.find(p => p.id === parseInt(assignForm.value.position_id));
  const isAssigningToOther = selectedLibrarian.value.id !== currentUserId.value;
  const isAssigningUniversityLibrarian = selectedPosition?.name === 'University Librarian';
  
  return isAssigningToOther && isAssigningUniversityLibrarian;
});

const submitAssignForm = () => {
  if (!props.isUniversityLibrarian || !selectedLibrarian.value) {
    return;
  }

  // Prevent self-assignment unless transferring head role
  if (selectedLibrarian.value.id === currentUserId.value && 
      selectedLibrarian.value.position === 'University Librarian' &&
      !isTransferringHeadRole.value) {
    return;
  }

  // Confirmation for transferring head role
  if (isTransferringHeadRole.value) {
    if (!confirm('Are you sure you want to transfer the University Librarian role? This will remove your University Librarian privileges.')) {
      return;
    }
  }

  isSubmitting.value = true;
  router.patch(`/admin/users/${selectedLibrarian.value.id}`, {
    position_id: assignForm.value.position_id,
  }, {
    onFinish: () => {
      isSubmitting.value = false;
    },
    onSuccess: () => {
      closeAssignModal();
    },
  });
};

const confirmDelete = (position) => {
  if (!props.isUniversityLibrarian || position.is_protected) {
    return;
  }

  if (!confirm(`Are you sure you want to delete "${position.name}"?`)) {
    return;
  }

  router.delete(`/admin/positions/${position.id}`, {
    onSuccess: () => {
      // Success handled by flash message
    },
  });
};
</script>

<style scoped>
.kulim-park-bold {
  font-family: 'Kulim Park', sans-serif;
  font-weight: 700;
}
</style>

