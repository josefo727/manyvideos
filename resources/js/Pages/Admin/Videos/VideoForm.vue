<script setup>
const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    tags: {
        type: Object,
        required: true,
    },
    isEdit: {
        type: Boolean,
        default: false,
    },
});

const handleFileChange = (event) => {
    props.form.video = event.target.files[0];
};

const handleSubmit = () => {
    if (props.isEdit) {
        props.form.submit('put', `/admin/videos/${props.form.id}`);
    } else {
        props.form.submit('post', '/admin/videos');
    }
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Title</label>
            <input
                id="name"
                type="text"
                v-model="props.form.name"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                required
            />
            <div v-if="props.form.errors.name" class="mt-2 text-red-600 text-sm">
                {{ props.form.errors.name }}
            </div>
        </div>
        <div>
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
            <div class="grid grid-cols-2 gap-2 mt-1">
                <label v-for="tag in props.tags" :key="tag.id" class="flex items-center">
                    <input
                        type="checkbox"
                        :value="tag.id"
                        v-model="props.form.tags"
                        class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                    />
                    <span class="ml-2 text-sm text-gray-700">{{ tag.name }}</span>
                </label>
            </div>
            <div v-if="props.form.errors.tags" class="mt-2 text-red-600 text-sm">
                {{ props.form.errors.tags }}
            </div>
        </div>
        <div>
            <label for="video" class="block text-sm font-medium text-gray-700">Video File</label>
            <input
                id="video"
                type="file"
                @change="handleFileChange"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :required="!props.isEdit"
            />
            <div v-if="props.form.errors.video" class="mt-2 text-red-600 text-sm">
                {{ props.form.errors.video }}
            </div>
        </div>
        <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
        >
            {{ props.isEdit ? 'Update' : 'Create' }} Video
        </button>
    </form>
</template>
