<script setup lang="ts">

import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({ team: Object, credential: Object });
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
    {
        title: props.credential.title,
        href: `/teams/${props.team.id}/credentials/${props.credential.id}`,
    },
];
</script>

<template>
    <Head title="Credential Detail" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">Credential</h1>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h2 class="text-lg font-bold">Title</h2>
                    <p>{{ props.credential.title }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-bold">URL</h2>
                    <p>{{ props.credential.url }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-bold">Username</h2>
                    <p>{{ props.credential.username }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-bold">Password</h2>
                    <p>{{ props.credential.password }}</p>
                </div>
                <div class="col-span-2">
                    <h2 class="text-lg font-bold">Description</h2>
                    <p>{{ props.credential.description }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>