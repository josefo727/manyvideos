<script setup>
const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    isEdit: {
        type: Boolean,
        default: false,
    },
});

const handleSubmit = () => {
    if (props.isEdit) {
        props.form.submit('put', `/admin/tags/${props.form.id}`);
    } else {
        props.form.submit('post', '/admin/tags');
    }
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Tag Name</label>
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
        <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
        >
            {{ props.isEdit ? 'Update' : 'Create' }} Tag
        </button>
    </form>
</template>
