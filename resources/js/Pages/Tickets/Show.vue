<template>
  <div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-start">
      <div>
        <a href="/tickets" class="text-blue-600 hover:underline text-sm mb-2 inline-block">← Back to Tickets</a>
        <h1 class="text-3xl font-bold mt-2">{{ ticket.title }}</h1>
      </div>

      <button
        @click="toggleEdit"
        class="text-sm bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded"
      >
        {{ isEditing ? 'Cancel' : 'Edit' }}
      </button>
    </div>

    <div class="bg-gray-50 rounded-lg p-6">
      <div class="mb-6">
        <h2 class="text-lg font-semibold mb-3">Description</h2>
        <p class="text-gray-700 leading-relaxed">{{ ticket.description }}</p>
      </div>

      <div class="grid grid-cols-2 gap-6 mb-6 py-4 px-0 border-y border-gray-200">
        <!-- STATUS -->
        <div>
          <label class="font-semibold text-xs text-gray-600 mb-2 block">Status</label>

          <span v-if="!isEditing" :class="getStatusClasses(ticket.status)">
            {{ formatStatus(ticket.status) }}
          </span>

          <select
            v-else
            v-model="form.status"
            class="border rounded px-2 py-1 text-sm w-full"
          >
            <option value="open">Open</option>
            <option value="in_progress">In Progress</option>
            <option value="resolved">Resolved</option>
            <option value="closed">Closed</option>
          </select>
        </div>

        <!-- PRIORITY -->
        <div>
          <label class="font-semibold text-xs text-gray-600 mb-2 block">Priority</label>

          <span class="font-medium text-gray-800">
            {{ ticket.priority }}
          </span>
        </div>
      </div>

      <div v-if="ticket.first_response_due_at || ticket.resolution_due_at" class="mb-6 p-4 bg-white rounded border-l-4 border-blue-500">
        <h3 class="font-semibold mb-3 text-sm">SLA Targets</h3>
        <div class="grid grid-cols-2 gap-4">
          <div v-if="ticket.first_response_due_at">
            <label class="font-semibold text-xs text-gray-600 mb-1 block">First Response Due</label>
            <span class="text-sm text-gray-800">{{ formatDateTime(ticket.first_response_due_at) }}</span>
          </div>
          <div v-if="ticket.resolution_due_at">
            <label class="font-semibold text-xs text-gray-600 mb-1 block">Resolution Due</label>
            <span class="text-sm text-gray-800">{{ formatDateTime(ticket.resolution_due_at) }}</span>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-200">
        <div>
          <label class="font-semibold text-xs text-gray-600 mb-1 block">Ticket ID</label>
          <span class="text-sm text-gray-800">{{ ticket.id }}</span>
        </div>
        <div>
          <label class="font-semibold text-xs text-gray-600 mb-1 block">Created</label>
          <span class="text-sm text-gray-800">{{ formatDateTime(ticket.created_at) }}</span>
        </div>
        <div>
          <label class="font-semibold text-xs text-gray-600 mb-1 block">Updated</label>
          <span class="text-sm text-gray-800">{{ formatDateTime(ticket.updated_at) }}</span>
        </div>
      </div>

      <!-- SAVE -->
      <div v-if="isEditing" class="mt-6 text-right">
        <button
          @click="save"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
        >
          Save changes
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

interface Ticket {
  id: number;
  title: string;
  description: string;
  status: string;
  priority: string;
  first_response_due_at: string | null;
  resolution_due_at: string | null;
  created_at: string;
  updated_at: string;
}

const props = defineProps<{
  ticket: Ticket;
}>();

const isEditing = ref(false)

const form = ref({
  status: props.ticket.status,
  priority: props.ticket.priority,
})

const toggleEdit = () => {
  isEditing.value = !isEditing.value

  if (!isEditing.value) {
    form.value.status = props.ticket.status
  }
}

const save = () => {
  router.patch(`/tickets/${props.ticket.id}/status`, {
    status: form.value.status,
  })

  isEditing.value = false
}

const formatStatus = (status: string): string => {
  const map: Record<string, string> = {
    open: 'Open',
    in_progress: 'In Progress',
    resolved: 'Resolved',
    closed: 'Closed',
  };
  return map[status] || status;
};

const getStatusClasses = (status: string): string => {
  const baseClasses = 'inline-block px-3 py-1 rounded font-medium text-sm';
  const statusClasses: Record<string, string> = {
    open: 'bg-green-100 text-green-800',
    in_progress: 'bg-blue-100 text-blue-800',
    resolved: 'bg-yellow-100 text-yellow-800',
    closed: 'bg-red-100 text-red-800',
  };
  return `${baseClasses} ${statusClasses[status] || 'bg-gray-100 text-gray-800'}`;
};

const formatDateTime = (datetime: string | null): string => {
  if (!datetime) return '';
  const date = new Date(datetime);
  return date.toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>

