<script setup lang="ts">
import { Head, router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';


const page = usePage();
const isAdmin = computed(() => page.props.auth.user.is_super_admin);
const props = defineProps({
    team: Object,
    tasks: Object,
});

const breadcrumbs = [
    {
        title: "Teams",
        href: "/teams",
    },
    {
        title: page.props.team.name,
        href: `/teams/${page.props.team.id}`,
    },
    {
        title: "Tasks",
        href: `/teams/${page.props.team.id}/tasks`,
    },
];

const createTask = () => {
    router.get(route("teams.tasks.create", props.team.id));
};

const showTask = (id: number) => {
    router.get(route("teams.tasks.show", [props.team.id, id]));
};

const editTask = (id: number) => {
    router.get(route("teams.tasks.edit", [props.team.id, id]));
};

const deleteTask = (id: number) => {
    if (confirm("Are you sure you want to delete this task?")) {
        router.delete(route("teams.tasks.destroy", [props.team.id, id]));
    }
};
</script>

<template>
    <Head title="Tasks" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">Tasks</h1>

            <Button @click="createTask">+ New Task</Button>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Card v-for="task in props.tasks" :key="task.id">
                    <CardHeader>
                        <CardTitle>{{ task.title }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-gray-500">Status: {{ props.team.task_statuses.find((status) => status.id === task.task_status_id).name }}</p>
                        <p class="text-sm text-gray-500">Due: {{ task.due_date || "No deadline" }}</p>
                        <p class="text-sm text-gray-500">Estimated time: {{ task.time_estimation || "" }}</p>
                        <p class="text-sm text-gray-500">Tags</p>
                        <ul class="text-sm text-gray-500">
                            <li v-for="tag in task.tags" :key="tag.id" class="text-sm text-gray-500">
                                {{ tag.name }}
                            </li>
                        </ul>
                        <p class="text-sm text-gray-500">Assignees</p>
                        <ul class="text-sm text-gray-500 mb-8">
                            <li v-for="assignee in task.assignees" :key="assignee.id" class="text-sm text-gray-500">
                                {{ assignee.name }}
                            </li>
                        </ul>


                        <Button @click="showTask(task.id)" class="mr-2">View</Button>
                        <Button @click="editTask(task.id)" variant="secondary" class="mr-2">Edit</Button>
                        <Button v-if="isAdmin" variant="destructive" @click="deleteTask(task.id)">Delete</Button>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>