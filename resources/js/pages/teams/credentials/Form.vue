<script setup lang="ts">

import { Head, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';

const props = defineProps({ team: Object, credential: Object });
const breadcrumbsCreate = [
    {
        title: 'Teams',
        href: '/teams',
    },
    {
        title: props.team.name,
        href: `/teams/${props.team.id}`,
    },
    {
        title: 'Credentials',
        href: `/teams/${props.team.id}/credentials`,
    },
    {
        title: 'Create',
        href: `/teams/${props.team.id}/credentials/create`,
    },
];

const breadcrumbsEdit = [
    {
        title: 'Teams',
        href: '/teams',
    },
    {
        title: props.team.name,
        href: `/teams/${props.team.id}`,
    },
    {
        title: 'Credentials',
        href: `/teams/${props.team.id}/credentials`,
    },
    {
        title: 'Edit',
        href: `/teams/${props.team.id}/credentials/edit`,
    },
];

const form = useForm({
    title: props.credential ? props.credential.title : '',
    url: props.credential ? props.credential.url : '',
    username: props.credential ? props.credential.username : '',
    password: props.credential ? props.credential.password : '',
    description: props.credential ? props.credential.description : '',
});

const users = props.team.users;

const submit = () => {
    if (props.credential) {
        form.put(`/teams/${props.team.id}/credentials/${props.credential.id}`);
    } else {
        form.post(`/teams/${props.team.id}/credentials`);
    }
};
</script>

<template>
    <Head title="Credentials Form" />

    <AppLayout :breadcrumbs="props.credential ? breadcrumbsEdit : breadcrumbsCreate">
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">{{ props.credential ? 'Edit Credential' : 'New Credential' }}</h1>

            <Label>Title</Label>
            <Input v-model="form.title" placeholder="Title" required/>
            <InputError :message="form.errors.title" />

            <Label>URL</Label>
            <Input v-model="form.url" placeholder="URL" required/>
            <InputError :message="form.errors.url" />

            <Label>Username</Label>
            <Input v-model="form.username" placeholder="Username" required/>
            <InputError :message="form.errors.username" />

            <Label>Password</Label>
            <Input v-model="form.password" placeholder="Password" required/>
            <InputError :message="form.errors.password" />

            <Label>Description</Label>
            <Textarea v-model="form.description" placeholder="Description"></Textarea>
            <InputError :message="form.errors.description" />

            <Button @click="submit">{{ props.credential ? 'Update' : 'Create' }}</Button>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>