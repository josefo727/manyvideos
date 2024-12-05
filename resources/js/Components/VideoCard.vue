<script setup>
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    video: {
        type: Object,
        required: true,
    },
});

const navigateToVideo = () => {
    Inertia.visit(`/videos/${props.video.id}`);
};
</script>

<template>
    <div class="border rounded-lg shadow-md hover:shadow-lg transition duration-300">
        <!-- Thumbnail -->
        <div
            class="relative cursor-pointer group"
            @click="navigateToVideo"
        >
            <img
                :src="video.thumbnail_path"
                alt="Video Thumbnail"
                class="w-full h-48 object-cover rounded-t-lg"
            />
            <!-- Play Button Overlay -->
            <div
                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 transition duration-300"
            >
                <span
                    class="text-white text-4xl bg-black bg-opacity-50 p-3 rounded-full"
                >
                    ▶
                </span>
            </div>
        </div>

        <!-- Video Info -->
        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-800 truncate" title="Click to view video">
                {{ video.title }}
            </h3>
            <div class="text-sm text-gray-600 mt-1">
                <span>Title: {{ video.name }}</span>
                <br />
                <span>Duration: {{ video.formatted_duration }} m:s</span>
                <br />
                <span>Size: {{ video.formatted_size }}</span>
            </div>

            <!-- Tags -->
            <div class="mt-3 flex flex-wrap justify-end gap-2">
                <span
                    v-for="tag in video.tags"
                    :key="tag.id"
                    class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full cursor-pointer hover:bg-blue-200"
                    @click="$emit('tag-click', tag.id)"
                >
                    {{ tag.name }}
                </span>
            </div>
        </div>
    </div>
</template>

