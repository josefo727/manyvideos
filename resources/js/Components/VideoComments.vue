<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const isAuthenticated = page.props.auth.user !== null;
const userId = page.props.auth?.user?.id;

const props = defineProps({
    videoId: {
        type: Number,
        required: true,
    },
});

const commentContent = ref('');
const comments = ref([]);
const isLoading = ref(true);
const error = ref(null);

const fetchComments = async () => {
    try {
        isLoading.value = true;
        const { data } = await axios.get(`/api/videos/${props.videoId}/comments`);
        comments.value = data;
    } catch (err) {
        console.error(err);
        error.value = 'Failed to load comments.';
    } finally {
        isLoading.value = false;
    }
};

const submitComment = async () => {
    if (!commentContent.value.trim()) {
        alert('The comment cannot be empty.');
        return;
    }

    try {
        const videoId = props.videoId;
        const response = await axios.post(`/api/videos/${videoId}/comments/${userId}`,
            {
                    content: commentContent.value,
                },
            {
                    headers: {
                        'Content-Type': 'application/json',
                    },
            });

        if (response.status === 201) {
            await fetchComments();
            commentContent.value = '';
        }
    } catch (error) {
        console.error('Error while sending the comment:', error);
    }
};

onMounted(() => {
    fetchComments();
    console.log(page.props);
});
</script>

<template>
    <div class="bg-white shadow-md rounded-lg p-4 mt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Comments</h2>

        <div v-if="isLoading" class="text-gray-500">Loading comments...</div>

        <div v-if="error" class="text-red-500">{{ error }}</div>

        <ul v-if="comments.length > 0">
            <li
                v-for="comment in comments"
                :key="comment.id"
                class="border-b border-gray-200 pb-4 mb-4 last:border-none last:pb-0 last:mb-0"
            >
                <div class="flex items-center space-x-3">
                    <div class="text-sm font-bold text-gray-700">{{ comment.user.name }}</div>
                    <div class="text-sm text-gray-500">{{ new Date(comment.created_at).toLocaleString() }}</div>
                </div>
                <p class="text-gray-800 mt-2">{{ comment.content }}</p>
            </li>
        </ul>

        <div v-else-if="!isLoading && comments.length === 0" class="text-gray-500">
            No comments yet. Be the first to comment!
        </div>

        <div class="mt-4" v-if="isAuthenticated">
            <form @submit.prevent="submitComment">
                <textarea v-model="commentContent" placeholder="Write your comment..." class="w-full border rounded"></textarea>
                <button type="submit" class="mt-2 bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600">
                    Comment
                </button>
            </form>
        </div>

        <p v-else>
            You must <a href="/login" class="text-blue-500 hover:underline">login</a> to comment.
        </p>
    </div>
</template>

<style scoped>
</style>
