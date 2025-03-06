<script setup lang="ts">

import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';

const props = defineProps({ team: Object });
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
];

const form = useForm({
    email: '',
});

const inviteUser = () => {
    form.post(`/teams/${props.team.id}/invite`);
};

const removeUser = (userId: number) => {
    if (confirm('Are you sure you want to remove this user from the team?')) {
        router.delete(route('teams.removeUser', [props.team.id, userId]));
    }
};

const viewCredentials = () => {
    router.get(route('teams.credentials.index', props.team.id));
};

const viewTasks = () => {
    router.get(route('teams.tasks.index', props.team.id));
};

const viewTags = () => {
    router.get(route('teams.tags.index', props.team.id));
};

const viewStatuses = () => {
    router.get(route('teams.statuses.index', props.team.id));
};
</script>

<template>
    <Head title="Team" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">{{ team.name }}</h1>

            <div class="grid md:grid-cols-2 gap-4">
                <Card>
                    <CardHeader>
                        <CardTitle>Team Members</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <ul>
                            <li v-for="user in team.users" :key="user.id" class="flex justify-between">
                                {{ user.name }} <span class="text-gray-500">({{ user.email }})</span>
                                <Button v-if="isAdmin && user.id !== page.props.auth.user.id" variant="destructive" @click="removeUser(user.id)">Remove</Button>
                            </li>
                        </ul>
                    </CardContent>
                    <CardFooter v-if="isAdmin" class="block space-y-2">
                        <Input v-model="form.email" placeholder="User email" />
                        <InputError :message="form.errors.email" />
                        <Button @click="inviteUser">Invite</Button>
                    </CardFooter>
                </Card>

                <Card>
                    <CardHeader><CardTitle>Credentials</CardTitle></CardHeader>
                    <CardContent>
                        <p class="mb-2">Here you can find the teams credentials.</p>
                        <Button @click="viewCredentials">View Credentials</Button>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader><CardTitle>Tasks</CardTitle></CardHeader>
                    <CardContent>
                        <p class="mb-2">Here you can find the teams tasks.</p>
                        <Button @click="viewTasks">View Tasks</Button>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader><CardTitle>Tags</CardTitle></CardHeader>
                    <CardContent>
                        <p class="mb-2">Here you can find the teams tags.</p>
                        <Button @click="viewTags">View Tags</Button>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader><CardTitle>Statuses</CardTitle></CardHeader>
                    <CardContent>
                        <p class="mb-2">Here you can find the teams statuses.</p>
                        <Button @click="viewStatuses">View Statuses</Button>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>