<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    videos: {
        type: Object,
        required: true,
    },
});

const localVideos = ref(props.videos);

const fetchVideos = async () => {
    const { data } = await axios.get('/admin/videos');
    localVideos.value = data;
};


onMounted(async () => {
    await fetchVideos();
});

</script>

<template>
    <AppLayout title="My Videos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Videos
            </h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-semibold text-gray-700">My Videos</h1>
                        <a href="/admin/videos/create" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">
                            Create New Video
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                        Size
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                        Duration
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                        Thumbnail
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                        Tags
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-sm font-medium text-gray-600 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="video in videos.data" :key="video.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ video.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ video.size_formated }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ video.formatted_duration }} m:s
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img :src="video.thumbnail_path" alt="Thumbnail" class="w-16 h-16 rounded-md shadow">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ video.tags.map(tag => tag.name).join(', ') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a :href="`/admin/videos/${video.id}/edit`">
                                            <button type="submit" class="bg-blue-500 text-white px-2 py-1 text-xs font-semibold rounded hover:bg-blue-700 mr-4">
                                                Edit
                                            </button>
                                        </a>
                                        <form :action="`/admin/videos/${video.id}`" method="POST" class="inline">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="bg-red-500 text-white px-2 py-1 text-xs font-semibold rounded hover:bg-red-700">
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
        videos: Object,
    },
};
</script>
