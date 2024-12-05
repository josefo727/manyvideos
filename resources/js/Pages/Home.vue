<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import VideoCard from '@/Components/VideoCard.vue';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';
import Notifications from "@/Components/Notifications.vue";

const props = defineProps({
    filters: {
        type: Object,
        default: () => ({
            search: '',
            tags: [],
            min_duration: 0,
            max_duration: 10,
            min_size: 0,
            max_size: 500,
        })
    },
    tags: Array
});

const form = useForm({
    search: props.filters.search,
    tags: props.filters.tags,
    min_duration: props.filters.min_duration,
    max_duration: props.filters.max_duration,
    min_size: props.filters.min_size,
    max_size: props.filters.max_size,
});

function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

watch(form, debounce(() => {
    form.get(route('home'));
}, 300));

const tagsSelected = ref(
    (props.filters.tags || []).map(tagId => {
        return props.tags.find(tag => tag.id === parseInt(tagId));
    }).filter(tag => tag !== undefined)
);

watch(tagsSelected, (newValue) => {
    form.tags = newValue.map(tag => tag.id);
});

const handleTagClick = (tagId) => {
    const tag = props.tags.find(t => t.id === tagId);

    if (tag && !tagsSelected.value.some(t => t.id === tagId)) {
        tagsSelected.value.push(tag);
        form.tags = tagsSelected.value.map(tag => tagId);
    }
};

</script>

<template>
    <div class="container mx-auto px-4">
        <!-- Header -->
        <header class="flex justify-between items-center py-4">
            <h1 class="text-3xl font-bold">ManyVideos</h1>
            <div class="flex space-x-2 justify-between items-center w-1/3">
                <input
                    type="text"
                    v-model="form.search"
                    placeholder="Search videos..."
                    class="border rounded p-2 w-4/5"
                />
                <Notifications />
            </div>
        </header>

        <!-- Filters -->
        <section class="my-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label for="tags" class="block text-sm font-medium">Tags</label>
                    <Multiselect
                        v-model="tagsSelected"
                        :options="props.tags"
                        :taggable="true"
                        multiple
                        tag-placeholder="Press enter to add"
                        placeholder="Select or add tags"
                        label="name"
                        track-by="id"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium">Duration (Minutes)</label>
                    <div class="flex items-center space-x-2">
                        <input type="number" v-model="form.min_duration" class="border rounded p-2 w-1/3" />
                        <span>to</span>
                        <input type="number" v-model="form.max_duration" class="border rounded p-2 w-1/3" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium">Size (MB)</label>
                    <div class="flex items-center space-x-2">
                        <input type="number" v-model="form.min_size" class="border rounded p-2 w-1/3" />
                        <span>to</span>
                        <input type="number" v-model="form.max_size" class="border rounded p-2 w-1/3" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Video List -->
        <section class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <VideoCard
                v-for="video in $page.props.videos.data"
                :key="video.id"
                :video="video"
                @tag-click="handleTagClick"
            />
        </section>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <button
                v-for="(link, index) in $page.props.videos.links"
                :key="index"
                :disabled="!link.url"
                @click="form.get(link.url)"
                class="px-4 py-2 mx-1 border rounded text-sm font-medium transition"
                :class="{
                    'bg-gray-200 text-gray-800 cursor-default': link.active,
                    'hover:bg-gray-100 text-gray-600': !link.active && link.url,
                    'text-gray-400 cursor-not-allowed': !link.url,
                }"
            >
                <span v-html="link.label"></span>
            </button>
        </div>

    </div>
</template>
