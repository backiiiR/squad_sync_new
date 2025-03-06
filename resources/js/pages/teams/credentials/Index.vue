<script setup lang="ts">

import { Head, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { computed } from 'vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

const props = defineProps({ team: Object, credentials: Object });
const page = usePage();
const isAdmin = computed(() => page.props.auth.user.is_super_admin);
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
        title: 'Credentials',
        href: `/teams/${props.team.id}/credentials`,
    },
];

const createCredential = () => {
    router.get(route('teams.credentials.create', props.team.id));
};

const editCredential = (credentialId: number) => {
    router.get(route('teams.credentials.edit', [props.team.id, credentialId]));
}

const viewCredential = (credentialId: number) => {
    router.get(route('teams.credentials.show', [props.team.id, credentialId]));
};

const deleteCredential = (credentialId: number) => {
    if (confirm('Are you sure you want to delete this credential?')) {
        router.delete(route('teams.credentials.destroy', [props.team.id, credentialId]));
    }
};

</script>

<template>
    <Head title="Credentials" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">Credentials</h1>
            <Button v-if="isAdmin" @click="createCredential">+ New Credential</Button>
            <Table>
                <TableCaption>The list of the teams credentials</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>Title</TableHead>
                        <TableHead>URL</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="credential in props.credentials" :key="credential.id">
                        <TableCell>{{ credential.title }}</TableCell>
                        <TableCell>
                            <a :href="credential.url" target="_blank">{{ credential.url }}</a>
                        </TableCell>
                        <TableCell>
                            <Button class="mr-2" @click="viewCredential(credential.id)">View</Button>
                            <Button v-if="isAdmin" variant="secondary" class="mr-2" @click="editCredential(credential.id)">Edit</Button>
                            <Button v-if="isAdmin" variant="destructive" @click="deleteCredential(credential.id)">Delete</Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>