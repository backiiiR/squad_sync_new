<script setup lang="ts">

import { Head, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({ team: Object, task: Object });
const breadcrumbs = [
    {
        title: 'Teams',
        href: '/teams',
    },
    {
        title: props.team.name,
        href: `/teams/${props.team.id}`,
    },
    {
        title: 'Tasks',
        href: `/teams/${props.team.id}/tasks`,
    },
    {
        title: props.task.title,
        href: `/teams/${props.team.id}/tasks/${props.task.id}`,
    },
];

const status = props.team.task_statuses.find((status) => status.id === props.task.task_status_id);
</script>

<template>
    <Head title="Task Detail" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">Task</h1>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h2 class="text-lg font-bold">Title</h2>
                    <p>{{ props.task.title }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-bold">Status</h2>
                    <p>{{ status.name }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-bold">Assigned To</h2>
                    <p>{{ props.task.assignees.map((user) => user.name).join(", ") }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-bold">Tags</h2>
                    <p>{{ props.task.tags.map((tag) => tag.name).join(", ") }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-bold">Due Date</h2>
                    <p>{{ props.task.due_date }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-bold">Time Estimation</h2>
                    <p>{{ props.task.time_estimation ?? "Not available" }}</p>
                </div>
                <div class="col-span-2">
                    <h2 class="text-lg font-bold">Description</h2>
                    <p>{{ props.task.description ?? "Not available" }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>