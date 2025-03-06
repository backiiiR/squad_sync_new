<script setup lang="ts">

import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { Select, SelectItem } from '@/components/ui/select';

const props = defineProps({ team: Object, task: Object });
const breadcrumbsCreate = [
    {
        title: "Teams",
        href: "/teams",
    },
    {
        title: props.team.name,
        href: `/teams/${props.team.id}`,
    },
    {
        title: "Tasks",
        href: `/teams/${props.team.id}/tasks`,
    },
    {
        title: "Create",
        href: `/teams/${props.team.id}/tasks/create`,
    },
];

const breadcrumbsEdit = [
    {
        title: "Teams",
        href: "/teams",
    },
    {
        title: "Tasks",
        href: `/teams/${props.team.id}/tasks`,
    },
    {
        title: "Edit",
        href: `/teams/${props.team.id}/tasks/${props.task?.id}/edit`,
    },
];

let statuses = props.team.task_statuses;
// only store the id and name of the status
statuses = statuses.map((status) => {
    return {
        id: status.id,
        name: status.name,
    };
});

let tags = props.team.tags;
// only store the id and name of the tag
tags = tags.map((tag) => {
    return {
        id: tag.id,
        name: tag.name,
    };
});

let assignees = props.team.users;
// only store the id and name of the
assignees = assignees.map((assignee) => {
    return {
        id: assignee.id,
        name: assignee.name,
    };
});

const form = useForm({
    title: props.task ? props.task.title : "",
    description: props.task ? props.task.description : "",
    status: props.task ? props.task.task_status_id : "",
    due_date: props.task ? props.task.due_date : "",
    time_estimation: props.task ? props.task.time_estimation : "",
    tags: props?.task?.tags ? props.task.tags.map((tag) => {
        return tag.id;
    }) : "",
    assignees: props?.task?.assignees ? props.task.assignees.map((assignee) => {
        return assignee.id;
    }) : "",

});

const submit = () => {
    if (props.task) {
        form.put(`/teams/${props.team.id}/tasks/${props.task.id}`);
    } else {
        form.post(`/teams/${props.team.id}/tasks`);
    }
};
</script>

<template>
    <Head title="Tasks Form" />

    <AppLayout :breadcrumbs="props.task ? breadcrumbsEdit : breadcrumbsCreate">
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">{{ props.task ? "Edit Task" : "New Task" }}</h1>

            <Label>Title</Label>
            <Input v-model="form.title" placeholder="Task Title" required />
            <InputError :message="form.errors.title" />

            <Label>Description</Label>
            <Textarea v-model="form.description" placeholder="Task Description" required />
            <InputError :message="form.errors.description" />

            <Label>Status</Label>
            <br/>
            <select v-model="form.status" required>
                <option value="">Select Status</option>
                <option v-for="status in statuses" :key="status.id" :value="status.id">{{ status.name }}</option>
            </select><br/>
            <InputError :message="form.errors.status" />
            <Label>Due Date</Label>
            <Input v-model="form.due_date" type="date" placeholder="Due Date" required />
            <InputError :message="form.errors.due_date" />
            <Label>Time Estimation</Label>
            <Input v-model="form.time_estimation" type="number" placeholder="Time Estimation (in minutes)" required />
            <InputError :message="form.errors.time_estimation" />

            <Label>Tags</Label>
            <br/>
            <select v-model="form.tags" multiple>
                <option value="">Select Tags</option>
                <option v-for="tag in tags" :key="tag.id" :value="tag.id">{{ tag.name }}</option>
            </select><br/>
            <InputError :message="form.errors.tags" />

            <Label>Assignees</Label>
            <br/>
            <select v-model="form.assignees" multiple>
                <option value="">Select Assignees</option>
                <option v-for="assignee in assignees" :key="assignee.id" :value="assignee.id">{{ assignee.name }}</option>
            </select><br/>
            <InputError :message="form.errors.assignees" />

            <Button @click="submit">{{ props.task ? "Update" : "Create" }}</Button>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>