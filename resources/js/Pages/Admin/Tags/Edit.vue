<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {useForm} from '@inertiajs/vue3';
import TagForm from './TagForm.vue';

const props = defineProps({
    tag: Object,
});

const form = useForm({
    id: props.tag.id,
    name: props.tag.name,
});

const updateTag = () => {
    form.submit('put', `/admin/tags/${props.tag.id}`, {
        onSuccess: () => {
            console.log('Tag updated successfully!');
        },
        onError: (errors) => {
            console.error('Error updating tag:', errors);
        },
    });
};
</script>

<template>
    <AppLayout title="Edit Tag">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Tag
            </h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-semibold text-gray-700">Edit Tag</h1>
                        <a href="/admin/tags"
                           class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">
                            Back to index
                        </a>
                    </div>
                    <div class="max-w-7xl mx-auto my-5 sm:px-6 lg:px-8">
                        <TagForm :form="form" :isEdit="true"/>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
