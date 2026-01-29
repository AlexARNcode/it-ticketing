<template>
  <div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl font-bold">Tickets</h1>
      <a href="/tickets/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">Create Ticket</a>
    </div>

    <div v-if="tickets.length > 0" class="overflow-x-auto rounded-lg border border-gray-200">
      <table class="w-full">
        <thead class="bg-gray-100 border-b border-gray-200">
          <tr>
            <th class="px-4 py-3 text-left font-semibold text-sm">ID</th>
            <th class="px-4 py-3 text-left font-semibold text-sm">Title</th>
            <th class="px-4 py-3 text-left font-semibold text-sm">Status</th>
            <th class="px-4 py-3 text-left font-semibold text-sm">Priority</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ticket in tickets" :key="ticket.id" class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-4 py-3 text-sm">{{ ticket.id }}</td>
            <td class="px-4 py-3 text-sm">
              <a :href="`/tickets/${ticket.id}`" class="text-blue-600 hover:underline">
                {{ ticket.title }}
              </a>
            </td>
            <td class="px-4 py-3 text-sm">
              <span :class="getStatusClasses(ticket.status)">
                {{ formatStatus(ticket.status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-sm">{{ ticket.priority }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="text-center py-12 text-gray-600">
      <p>No tickets found.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Ticket {
  id: number;
  title: string;
  status: string;
  priority: string;
}

defineProps<{
  tickets: Ticket[];
}>();

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
  const baseClasses = 'inline-block px-2 py-1 rounded text-xs font-medium';
  const statusClasses: Record<string, string> = {
    open: 'bg-green-100 text-green-800',
    in_progress: 'bg-blue-100 text-blue-800',
    resolved: 'bg-yellow-100 text-yellow-800',
    closed: 'bg-red-100 text-red-800',
  };
  return `${baseClasses} ${statusClasses[status] || 'bg-gray-100 text-gray-800'}`;
};
</script>
