<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import VideoForm from './VideoForm.vue';

const props = defineProps({
    video: Object,
});

const form = useForm({
    id: props.video.id, // Necesario para usar en `form.submit`
    name: props.video.name,
    video: null,
});

const updateVideo = () => {
    form.submit('put', `/admin/videos/${props.video.id}`, {
        onSuccess: () => {
            console.log('Video updated successfully!');
        },
        onError: (errors) => {
            console.error('Error updating video:', errors);
        },
    });
};
</script>

<template>
    <AppLayout title="Edit Video">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Video
            </h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-semibold text-gray-700">Edit Video</h1>
                        <a href="/admin/videos" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">
                            Back to index
                        </a>
                    </div>
                    <div class="max-w-7xl mx-auto my-5 sm:px-6 lg:px-8">
                        <VideoForm :form="form" :isEdit="true" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
