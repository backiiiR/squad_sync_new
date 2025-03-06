<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import InputError from '@/components/InputError.vue';

const breadcrumbsCreate: BreadcrumbItem[] = [
    {
        title: 'Teams',
        href: '/teams',
    },
    {
        title: 'Create',
        href: '/teams/create',
    },
];

const breadcrumbsEdit: BreadcrumbItem[] = [
    {
        title: 'Teams',
        href: '/teams',
    },
    {
        title: 'Edit',
        href: '/teams/edit',
    },
];

const props = defineProps({ team: Object });
const form = useForm({
    name: props.team ? props.team.name : '',
});

const submit = () => {
    if (props.team) {
        form.put(`/teams/${props.team.id}`);
    } else {
        form.post('/teams');
    }
};

</script>

<template>
    <Head title="Teams Form" />

    <AppLayout :breadcrumbs="props.team ? breadcrumbsEdit : breadcrumbsCreate">
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">{{ props.team ? 'Edit Team' : 'New Team' }}</h1>

            <Input v-model="form.name" placeholder="Team Name" required/>
            <InputError :message="form.errors.name" />
            <Button @click="submit">{{ props.team ? 'Update' : 'Create' }}</Button>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>