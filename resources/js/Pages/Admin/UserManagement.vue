<template>
  <Head title="User Management" />
  <AdminLayout title="">
    <div class="homediv lg:mx-[10%] rounded-2xl bg-white shadow-lg p-6">
      <h2 class="text-3xl font-extrabold kulim-park-bold mb-6">User Management</h2>
      
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

      <!-- TABS NAVIGATION -->
      <ul class="flex flex-wrap border-b border-gray-200 mb-6">
        <li class="mr-1">
          <button
            type="button"
            @click.stop="navigateToTab(null)"
            :class="[
              'tab-link inline-block py-2 px-4 font-semibold',
              !currentRole ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-gray-700'
            ]"
          >
            All
          </button>
        </li>
        <li class="mr-1">
          <button
            type="button"
            @click.stop="navigateToTab('librarian')"
            :class="[
              'tab-link inline-block py-2 px-4 font-semibold',
              currentRole === 'librarian' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-gray-700'
            ]"
          >
            Librarians & Roles
          </button>
        </li>
        <li class="mr-1">
          <button
            type="button"
            @click.stop="navigateToTab('admin')"
            :class="[
              'tab-link inline-block py-2 px-4 font-semibold',
              currentRole === 'admin' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-gray-700'
            ]"
          >
            Administrators
          </button>
        </li>
        <li class="mr-1">
          <button
            type="button"
            @click.stop="navigateToTab('faculty')"
            :class="[
              'tab-link inline-block py-2 px-4 font-semibold',
              currentRole === 'faculty' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-gray-700'
            ]"
          >
            Faculty
          </button>
        </li>
        <li class="mr-1">
          <button
            type="button"
            @click.stop="navigateToTab('student')"
            :class="[
              'tab-link inline-block py-2 px-4 font-semibold',
              currentRole === 'student' ? 'text-green-600 border-b-2 border-green-600' : 'text-gray-500 hover:text-gray-700'
            ]"
          >
            Students
          </button>
        </li>
      </ul>

      <!-- ACCORDION FOR MANAGE LIBRARIAN ROLES (Only in Librarians Tab) -->
      <div v-if="currentRole === 'librarian'" class="accordion mb-6">
        <div
          class="accordion-header kantumruy-pro-regular cursor-pointer"
          @click="showPositionAccordion = !showPositionAccordion"
        >
          <span>Manage Librarian Positions</span>
          <span class="accordion-icon" :class="{ open: showPositionAccordion }">▼</span>
        </div>
        <div class="accordion-content" :class="{ open: showPositionAccordion }">
          <form @submit.prevent="savePosition" class="space-y-4">
            <div>
              <label for="position_select" class="block font-medium">Select Position to Edit (or create new)</label>
              <select
                id="position_select"
                v-model="positionForm.position_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                @change="loadPosition"
              >
                <option :value="null">Create New Position</option>
                <option
                  v-for="position in positions"
                  :key="position.id"
                  :value="position.id"
                >
                  {{ position.name }}
                </option>
              </select>
            </div>
            <div>
              <label for="name" class="block font-medium">Position Name</label>
              <input
                id="name"
                v-model="positionForm.name"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                required
              >
            </div>
            <div>
              <label class="block font-medium">Permissions</label>
              <div class="flex gap-4 mt-2">
                <label class="flex items-center gap-2">
                  <input
                    v-model="positionForm.permissions.add"
                    type="checkbox"
                  >
                  <span>Add</span>
                </label>
                <label class="flex items-center gap-2">
                  <input
                    v-model="positionForm.permissions.edit"
                    type="checkbox"
                  >
                  <span>Edit</span>
                </label>
                <label class="flex items-center gap-2">
                  <input
                    v-model="positionForm.permissions.archive"
                    type="checkbox"
                  >
                  <span>Archive</span>
                </label>
                <label class="flex items-center gap-2">
                  <input
                    v-model="positionForm.permissions.delete"
                    type="checkbox"
                  >
                  <span>Delete</span>
                </label>
              </div>
            </div>
            <button
              type="submit"
              class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
            >
              Save Position
            </button>
            <button
              v-if="positionForm.position_id"
              type="button"
              @click="deletePosition"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 ml-2"
            >
              Delete Position
            </button>
          </form>
        </div>
      </div>

      <!-- SEARCH + FILTERS -->
      <div class="mb-6 space-y-4 md:space-y-0 md:flex md:gap-4">
        <input
          v-model="searchTerm"
          type="text"
          placeholder="Search by name or email..."
          class="kantumruy-pro-regular tracking-tight flex-1 px-4 py-2 border-2 border-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"
          @input="debouncedSearch"
        >

        <select
          v-model="roleFilter"
          class="kantumruy-pro-regular tracking-tight px-10 py-2 border-2 border-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"
          @change="applyFilters"
        >
          <option value="">All Roles</option>
          <option value="admin">Admin</option>
          <option value="librarian">Librarian</option>
          <option value="faculty">Faculty</option>
          <option value="student">Student</option>
        </select>

        <select
          v-model="campusFilter"
          class="kantumruy-pro-regular tracking-tight px-10 py-2 border-2 border-gray-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"
          @change="applyFilters"
        >
          <option value="">All Campuses</option>
          <option
            v-for="campus in campuses"
            :key="campus.Campus_ID"
            :value="campus.Campus_ID"
          >
            {{ campus.Campus_Name }}
          </option>
        </select>

        <button
          type="button"
          @click="clearFilters"
          class="kantumruy-pro-regular bg-gray-600 text-white h-11 w-15 rounded-xl hover:bg-gray-700"
        >
          Clear
        </button>
      </div>

      <!-- TABLE -->
      <div v-if="users.data && users.data.length > 0" class="overflow-x-auto kantumruy-pro-regular tracking-tight">
        <table class="w-full bg-white shadow rounded border border-gray-200 min-w-[1000px]">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-3 text-left">Name</th>
              <th class="p-3 text-left">Email</th>
              <th class="p-3 text-left">Role</th>
              <th class="p-3 text-left">Campus</th>
              <th v-if="currentRole === 'librarian'" class="p-3 text-left">Position</th>
              <th v-if="currentRole === 'librarian'" class="p-3 text-left">Permissions</th>
              <th class="p-3 text-left">Created At</th>
              <th class="p-3 text-left">Last Login</th>
              <th class="p-3 text-left">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="user in users.data"
              :key="user.id"
              class="border-b border-gray-400 hover:bg-gray-100 cursor-pointer"
              @click.stop="openEditModal(user)"
            >
              <td class="p-3">
                <div class="flex items-center gap-2">
                  <img
                    v-if="user.profile_picture"
                    :src="`/storage/${user.profile_picture}`"
                    :alt="user.full_name"
                    class="w-8 h-8 rounded-full object-cover"
                    @error="$event.target.style.display = 'none'"
                  >
                  <div
                    v-else
                    class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-xs font-semibold text-gray-600"
                  >
                    {{ user.full_name ? user.full_name.substring(0, 2).toUpperCase() : 'U' }}
                  </div>
                  {{ user.full_name }}
                </div>
              </td>
              <td class="p-3">{{ user.email }}</td>
              <td class="p-3">{{ user.role ? user.role.charAt(0).toUpperCase() + user.role.slice(1) : 'N/A' }}</td>
              <td class="p-3">{{ user.campus?.Campus_Name ?? 'N/A' }}</td>
              <td v-if="currentRole === 'librarian'" class="p-3">
                {{ user.librarian?.position?.name ?? 'Unassigned' }}
              </td>
              <td v-if="currentRole === 'librarian'" class="p-3">
                <span v-if="user.librarian?.position?.permissions">
                  Add: {{ user.librarian.position.permissions.add ? 'Yes' : 'No' }} |
                  Archive: {{ user.librarian.position.permissions.archive ? 'Yes' : 'No' }} |
                  Delete: {{ user.librarian.position.permissions.delete ? 'Yes' : 'No' }}
                </span>
                <span v-else>N/A</span>
              </td>
              <td class="p-3">{{ user.created_at ?? 'Never' }}</td>
              <td class="p-3">
                <div class="flex flex-col gap-1">
                  <span class="text-xs text-gray-600">{{ user.last_login_formatted ?? 'Never' }}</span>
                  <span
                    v-if="user.is_online"
                    class="inline-flex items-center gap-1 text-xs"
                  >
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    Online
                  </span>
                  <span
                    v-else
                    class="inline-flex items-center gap-1 text-xs text-gray-500"
                  >
                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                    Offline
                  </span>
                </div>
              </td>
              <td class="p-3">
                <span
                  :class="[
                    'px-2 py-1 text-xs rounded-full',
                    user.is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                  ]"
                >
                  {{ user.is_approved ? 'Approved' : 'Pending' }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="users.links && users.links.length > 3" class="mt-4 flex justify-center gap-2">
          <Link
            v-for="link in users.links"
            :key="link.label"
            :href="link.url || '#'"
            :class="[
              'px-4 py-2 rounded',
              link.active ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
              !link.url ? 'opacity-50 cursor-not-allowed' : ''
            ]"
            v-html="link.label"
          />
        </div>
      </div>
      <p v-else class="text-gray-600">No users found.</p>
    </div>

    <!-- EDIT MODAL -->
    <div
      v-if="showEditModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto"
      @click.self="closeEditModal"
    >
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] my-4 overflow-y-auto mx-auto">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-bold kulim-park-bold">Edit User</h3>
        </div>
        <form @submit.prevent="updateUser" class="p-6 space-y-4">
          <div>
            <label class="block font-medium">First Name</label>
            <input
              v-model="editForm.first_name"
              type="text"
              class="w-full px-3 py-2 border rounded-lg"
              required
            >
          </div>
          <div>
            <label class="block font-medium">Last Name</label>
            <input
              v-model="editForm.last_name"
              type="text"
              class="w-full px-3 py-2 border rounded-lg"
              required
            >
          </div>
          <div>
            <label class="block font-medium">Email</label>
            <input
              v-model="editForm.email"
              type="email"
              class="w-full px-3 py-2 border rounded-lg"
              required
            >
          </div>
          <div>
            <label class="block font-medium">Role</label>
            <select
              v-model="editForm.role"
              class="w-full px-3 py-2 border rounded-lg"
              @change="togglePositionField"
            >
              <option value="admin">Admin</option>
              <option value="librarian">Librarian</option>
              <option value="faculty">Faculty</option>
              <option value="student">Student</option>
            </select>
          </div>
          <div v-if="editForm.role === 'librarian'">
            <label class="block font-medium">Position</label>
            <select
              v-model="editForm.position_id"
              class="w-full px-3 py-2 border rounded-lg"
            >
              <option :value="null">Unassigned</option>
              <option
                v-for="position in positions"
                :key="position.id"
                :value="position.id"
              >
                {{ position.name }}
              </option>
            </select>
          </div>
          <div>
            <label class="block font-medium">Campus</label>
            <select
              v-model="editForm.campus_id"
              class="w-full px-3 py-2 border rounded-lg"
            >
              <option
                v-for="campus in campuses"
                :key="campus.Campus_ID"
                :value="campus.Campus_ID"
              >
                {{ campus.Campus_Name }}
              </option>
            </select>
          </div>
          <div>
            <label class="flex items-center gap-2">
              <input
                v-model="editForm.is_approved"
                type="checkbox"
                :value="true"
              >
              <span>Approved</span>
            </label>
          </div>
          <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
            <button
              type="button"
              @click="closeEditModal"
              class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 disabled:opacity-50"
            >
              Save
            </button>
            <button
              type="button"
              @click="deleteUser"
              class="px-4 py-2 bg-red-700 text-white rounded-lg hover:bg-red-800"
            >
              Delete User
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { Link, router, usePage, Head} from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const props = defineProps({
  users: {
    type: Object,
    required: true,
  },
  role: {
    type: String,
    default: null,
  },
  campuses: {
    type: Array,
    default: () => [],
  },
  positions: {
    type: Array,
    default: () => [],
  },
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success ?? page.props.flash?.status ?? null);
const flashError = computed(() => page.props.flash?.error ?? null);

// Get role from URL query params or props
const currentRole = computed(() => {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get('role') || props.role || null;
});
const searchTerm = ref('');
const roleFilter = ref(props.role || '');
const campusFilter = ref('');
const showPositionAccordion = ref(false);
const showEditModal = ref(false);
const isSubmitting = ref(false);

const editForm = ref({
  id: null,
  first_name: '',
  last_name: '',
  email: '',
  role: '',
  campus_id: null,
  is_approved: false,
  position_id: null,
});

const positionForm = ref({
  position_id: null,
  name: '',
  permissions: {
    add: false,
    edit: false,
    archive: false,
    delete: false,
  },
});

let searchTimeout = null;

const navigateToTab = (role, event) => {
  if (event) {
    event.preventDefault();
    event.stopPropagation();
  }
  
  // Build URL with query params
  const url = new URL('/admin/users', window.location.origin);
  if (role) {
    url.searchParams.set('role', role);
  }
  if (searchTerm.value) {
    url.searchParams.set('search', searchTerm.value);
  }
  if (campusFilter.value) {
    url.searchParams.set('campus', campusFilter.value);
  }
  
  // Use visit instead of get to ensure proper Inertia handling
  router.visit(url.pathname + url.search, {
    preserveState: false,
    preserveScroll: false,
    only: ['users', 'role', 'campuses', 'positions'],
  });
};

const debouncedSearch = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 300);
};

const applyFilters = () => {
  // Build URL with query params
  const url = new URL('/admin/users', window.location.origin);
  
  if (searchTerm.value) {
    url.searchParams.set('search', searchTerm.value);
  }
  // Use roleFilter if set, otherwise use currentRole from URL
  if (roleFilter.value) {
    url.searchParams.set('role', roleFilter.value);
  } else if (currentRole.value) {
    url.searchParams.set('role', currentRole.value);
  }
  if (campusFilter.value) {
    url.searchParams.set('campus', campusFilter.value);
  }

  router.visit(url.pathname + url.search, {
    preserveState: true,
    preserveScroll: true,
    only: ['users', 'role', 'campuses', 'positions'],
  });
};

const clearFilters = () => {
  searchTerm.value = '';
  roleFilter.value = '';
  campusFilter.value = '';
  applyFilters();
};

const openEditModal = async (user) => {
  try {
    const response = await fetch(`/admin/users/${user.id}/edit`);
    const data = await response.json();
    editForm.value = {
      id: user.id,
      first_name: data.first_name || '',
      last_name: data.last_name || '',
      email: data.email,
      role: data.role,
      campus_id: data.campus_id || null,
      is_approved: data.is_approved || false,
      position_id: data.position_id || null,
    };
    showEditModal.value = true;
  } catch (error) {
    console.error('Error loading user:', error);
    alert('Failed to load user data.');
  }
};

const closeEditModal = () => {
  showEditModal.value = false;
  editForm.value = {
    id: null,
    first_name: '',
    last_name: '',
    email: '',
    role: '',
    campus_id: null,
    is_approved: false,
    position_id: null,
  };
};

const togglePositionField = () => {
  // Position field visibility is handled by v-if in template
};

const updateUser = () => {
  if (!editForm.value.id) {
    return;
  }

  isSubmitting.value = true;
  router.patch(`/admin/users/${editForm.value.id}`, editForm.value, {
    onFinish: () => {
      isSubmitting.value = false;
    },
    onSuccess: () => {
      closeEditModal();
      applyFilters();
    },
  });
};

const deleteUser = () => {
  if (!editForm.value.id || !confirm('Permanently delete this user?')) {
    return;
  }

  router.delete(`/admin/users/${editForm.value.id}`, {
    onSuccess: () => {
      closeEditModal();
      applyFilters();
    },
  });
};

const loadPosition = async () => {
  if (!positionForm.value.position_id) {
    positionForm.value = {
      position_id: null,
      name: '',
      permissions: {
        add: false,
        edit: false,
        archive: false,
        delete: false,
      },
    };
    return;
  }

  try {
    const response = await fetch(`/admin/positions/${positionForm.value.position_id}/edit`);
    const data = await response.json();
    positionForm.value.name = data.name;
    positionForm.value.permissions = data.permissions || {
      add: false,
      edit: false,
      archive: false,
      delete: false,
    };
  } catch (error) {
    console.error('Error loading position:', error);
    alert('Failed to load position.');
  }
};

const savePosition = () => {
  const url = positionForm.value.position_id
    ? `/admin/positions/${positionForm.value.position_id}`
    : '/admin/positions';
  const method = positionForm.value.position_id ? 'patch' : 'post';

  router[method](url, positionForm.value, {
    onSuccess: () => {
      router.reload({ only: ['positions'] });
      positionForm.value = {
        position_id: null,
        name: '',
        permissions: {
          add: false,
          edit: false,
          archive: false,
          delete: false,
        },
      };
    },
  });
};

const deletePosition = () => {
  if (!positionForm.value.position_id || !confirm('Delete this position?')) {
    return;
  }

  router.delete(`/admin/positions/${positionForm.value.position_id}`, {
    onSuccess: () => {
      router.reload({ only: ['positions'] });
      positionForm.value = {
        position_id: null,
        name: '',
        permissions: {
          add: false,
          edit: false,
          archive: false,
          delete: false,
        },
      };
    },
  });
};

</script>

<style scoped>
.accordion-header {
  background: #f3f4f6;
  padding: 1rem;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 600;
  font-family: 'Kantumruy Pro', sans-serif;
}

.accordion-content {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease;
  padding: 0 1rem;
}

.accordion-content.open {
  max-height: 500px;
  padding: 1rem;
}

.accordion-icon {
  transition: transform 0.3s ease;
  font-size: 1.2rem;
}

.accordion-icon.open {
  transform: rotate(180deg);
}

input[type="checkbox"] {
  appearance: none;
  width: 1.25rem;
  height: 1.25rem;
  border: 2px solid #d1d5db;
  border-radius: 0.375rem;
  background-color: #f9fafb;
  transition: all 0.2s ease-in-out;
}

input[type="checkbox"]:checked {
  background-color: #22c55e;
  border-color: #22c55e;
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6 10.586l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
}
</style>

