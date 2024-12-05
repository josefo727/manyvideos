<script setup>
import { ref, onMounted } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import { Inertia } from "@inertiajs/inertia";

const notifications = ref([]);
const unreadNotificationsCount = ref(0);

onMounted(() => {
    Echo.channel('global-video-notifications')
        .listen('VideoPublished', (data) => {

            notifications.value.push({
                id: Date.now(),
                user: data.message.user,
                videoTitle: data.message.video.title,
                videoThumbnail: data.message.video.thumbnail,
                path: `/videos/${data.message.video.id}`,
                read: false,
            });

            updateUnreadNotificationsCount();
        });
});

const navigateTo = (notificationId) => {
    const notification = notifications.value.find(n => n.id === notificationId);
    if (notification) {
        Inertia.visit(notification.path);
        notification.read = true;
        updateUnreadNotificationsCount();
    }
}

const updateUnreadNotificationsCount = () => {
    unreadNotificationsCount.value = notifications.value.filter(n => !n.read).length;
}
</script>

<template>
    <Dropdown align="right" width="96" :key="'notifications-menu'">
        <template #trigger>
            <button class="relative inline-flex items-center">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118.6 14.6L15 11V7h1a2 2 0 100-4h-4a2 2 0 000 4h1v4.345M5 18.5a1.5 1.5 0 11-3 0H5z" />
                </svg>
                <span v-if="unreadNotificationsCount > 0" class="absolute top-0 right-0 inline-block w-4 h-4 bg-red-600 text-white text-xs leading-tight font-bold text-center rounded-full">
                    {{ unreadNotificationsCount }}
                </span>
            </button>
        </template>

        <template #content>
            <div class="divide-y divide-gray-100">
                <div v-if="notifications.length > 0">
                    <div
                        v-for="notification in notifications"
                        :key="notification.id"
                        class="py-2 px-4 cursor-pointer"
                        :class="{'bg-gray-100': !notification.read, 'cursor-pointer': true}"
                        @click="navigateTo(notification.id)"
                    >
                        <div class="flex items-center">
                            <img :src="notification.videoThumbnail" alt="Thumbnail" class="w-10 h-10 rounded-md mr-3">
                            <div>
                                <strong>{{ notification.user }}</strong> has published a new video: <em>{{ notification.videoTitle }}</em>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="py-2 px-4">No notifications</div>
            </div>
        </template>
    </Dropdown>
</template>
