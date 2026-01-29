<template>
  <div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-8">
      <a href="/tickets" class="text-blue-600 hover:underline text-sm mb-2 inline-block">← Back to Tickets</a>
      <h1 class="text-2xl font-bold mt-2">Create New Ticket</h1>
    </div>

    <form @submit.prevent="submitForm" class="bg-gray-50 rounded-lg p-6">
      <div class="mb-5">
        <label for="title" class="block font-semibold mb-2 text-sm">Title</label>
        <input
          id="title"
          v-model="form.title"
          type="text"
          class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          placeholder="Ticket title"
          required
        />
        <span v-if="form.errors.title" class="text-red-600 text-xs mt-1 block">{{ form.errors.title }}</span>
      </div>

      <div class="mb-5">
        <label for="description" class="block font-semibold mb-2 text-sm">Description</label>
        <textarea
          id="description"
          v-model="form.description"
          class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          placeholder="Describe the issue"
          rows="6"
          required
        ></textarea>
        <span v-if="form.errors.description" class="text-red-600 text-xs mt-1 block">{{ form.errors.description }}</span>
      </div>

      <div class="mb-5">
        <label for="priority" class="block font-semibold mb-2 text-sm">Priority</label>
        <select
          id="priority"
          v-model="form.priority"
          class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          required
        >
          <option value="">Select priority...</option>
          <option value="low">Low</option>
          <option value="normal">Normal</option>
          <option value="high">High</option>
          <option value="urgent">Urgent</option>
        </select>
        <span v-if="form.errors.priority" class="text-red-600 text-xs mt-1 block">{{ form.errors.priority }}</span>
      </div>

      <div class="flex gap-2 mt-6">
        <button
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-500 text-white px-4 py-2 rounded text-sm font-medium"
          :disabled="form.processing"
        >
          {{ form.processing ? 'Creating...' : 'Create Ticket' }}
        </button>
        <a href="/tickets" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm font-medium flex-1 text-center">Cancel</a>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

interface FormData {
  title: string;
  description: string;
  priority: string;
}

const form = useForm<FormData>({
  title: '',
  description: '',
  priority: '',
});

const submitForm = (): void => {
  form.post('/tickets', {
    onSuccess: () => {
      // Redirect handled by Laravel redirect after store
    },
  });
};
</script>
