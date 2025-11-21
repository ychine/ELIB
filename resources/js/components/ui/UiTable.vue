<template>
  <div class="ui-table-wrapper">
    <table :class="['ui-table', { 'ui-table--striped': striped, 'ui-table--bordered': bordered }]">
      <thead v-if="headers && headers.length > 0">
        <tr>
          <th
            v-for="(header, index) in headers"
            :key="index"
            class="ui-table__header"
          >
            {{ header }}
          </th>
        </tr>
      </thead>
      <tbody>
        <slot name="body"></slot>
        <tr v-if="!$slots.body && data && data.length === 0">
          <td :colspan="headers ? headers.length : 1" class="ui-table__empty">
            {{ emptyText }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  headers: {
    type: Array,
    default: () => [],
  },
  data: {
    type: Array,
    default: () => [],
  },
  striped: {
    type: Boolean,
    default: false,
  },
  bordered: {
    type: Boolean,
    default: true,
  },
  emptyText: {
    type: String,
    default: 'No data available',
  },
});
</script>

<style scoped>
.ui-table-wrapper {
  width: 100%;
  overflow-x: auto;
  border-radius: 0.5rem;
  background: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.ui-table {
  width: 100%;
  border-collapse: collapse;
  font-family: 'Kantumruy Pro', sans-serif;
}

.ui-table--bordered {
  border: 1px solid #e5e7eb;
}

.ui-table__header {
  padding: 0.75rem 1rem;
  text-align: left;
  font-weight: 600;
  font-size: 0.875rem;
  color: #374151;
  background: #f9fafb;
  border-bottom: 2px solid #e5e7eb;
  white-space: nowrap;
}

.ui-table--striped tbody tr:nth-child(even) {
  background: #f9fafb;
}

.ui-table--bordered td {
  border: 1px solid #e5e7eb;
}

.ui-table tbody td {
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  color: #1f2937;
  border-bottom: 1px solid #e5e7eb;
}

.ui-table tbody tr:hover {
  background: #f3f4f6;
}

.ui-table__empty {
  text-align: center;
  padding: 2rem;
  color: #6b7280;
  font-style: italic;
}
</style>




