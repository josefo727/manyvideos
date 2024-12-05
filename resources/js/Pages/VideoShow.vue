<script setup>
import { ref, onMounted } from 'vue';
import videojs from 'video.js';
import 'video.js/dist/video-js.css';
import Notifications from "@/Components/Notifications.vue";

const props = defineProps({
    video: {
        type: Object,
        required: true,
    },
});

const player = ref(null);

onMounted(() => {
    player.value = videojs(document.getElementById('video-player'), {
        autoplay: true,
        controls: true,
        preload: 'auto',
        fluid: true,
    });
});
</script>

<template>
    <div class="container mx-auto py-8">
        <!-- Header -->
        <header class="flex justify-between items-center py-4">
            <a href="/" class="text-sm font-medium text-black hover:underline">
                <h1 class="text-3xl font-bold">ManyVideos</h1>
            </a>
            <Notifications />
        </header>

        <section class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">{{ video.name }}</h2>
                <a href="/" class="text-sm font-medium text-black hover:underline">
                    <span class="text-md font-bold">Back to home</span>
                </a>
            </div>
            <p class="text-gray-600">Uploaded by: <strong>{{ video.user.name }}</strong></p>
            <div class="flex space-x-2 mt-2">
                <span
                    v-for="tag in video.tags"
                    :key="tag.id"
                    class="bg-gray-200 text-gray-800 px-2 py-1 text-sm rounded-full"
                >
                    {{ tag.name }}
                </span>
            </div>
        </section>

        <!-- Video Player -->
        <div class="relative">
            <video
                id="video-player"
                class="video-js vjs-default-skin vjs-big-play-centered"
                controls
                preload="auto"
                :poster="video.thumbnail_path"
            >
                <source :src="`/storage/${video.path}`" type="video/mp4" />
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</template>

<style scoped>
.container {
    max-width: 800px;
    margin: 0 auto;
}
</style>
