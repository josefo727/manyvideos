<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    tags: {
        type: Object,
        required: true,
    },
});

const fetchTags = async () => {
    const {data} = await axios.get('/admin/tags');
    tags.value = data;
};

onMounted(async () => {
    await fetchTags();
});
</script>

<template>
    <AppLayout title="Tags">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tags
            </h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-semibold text-gray-700">Tags</h1>
                        <a href="/admin/tags/create"
                           class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">
                            Create New Tag
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white divide-y divide-gray-200">
                            <thead>
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-sm font-medium text-gray-600 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            <tr v-for="tag in tags.data" :key="tag.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ tag.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a :href="`/admin/tags/${tag.id}/edit`">
                                        <button type="submit"
                                                class="bg-blue-500 text-white px-2 py-1 text-xs font-semibold rounded hover:bg-blue-700 mr-4">
                                            Edit
                                        </button>
                                    </a>
                                    <form :action="`/admin/tags/${tag.id}`" method="POST" class="inline">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit"
                                                class="bg-red-500 text-white px-2 py-1 text-xs font-semibold rounded hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
export default {
    props: {
        tags: Object,
    },
};
</script>
