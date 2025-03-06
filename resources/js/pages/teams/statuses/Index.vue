<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { computed } from 'vue';
import { TableBody, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';

const props = defineProps({ team: Object, taskStatuses: Object });
const page = usePage();
const isAdmin = computed(() => page.props.auth.user.is_super_admin);
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Teams',
        href: '/teams',
    },
    {
        title: props.team.name,
        href: `/teams/${props.team.id}`,
    },
    {
        title: 'Statuses',
        href: `/teams/${props.team.id}/statuses`,
    },
];

const form = useForm({
    name: '',
});

const createStatus = () => {
    form.post(`/teams/${props.team.id}/statuses`, {
        onSuccess: () => {
            form.reset();
        },
    });
};

const deleteStatus = (statusId: number) => {
    if (confirm('Are you sure you want to delete this status?')) {
        router.delete(route('teams.statuses.destroy', [props.team.id, statusId]));
    }
};
</script>

<template>
    <Head title="Statuses" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">Statuses</h1>
            <Card>
                <CardHeader>
                    <CardTitle>New Status</CardTitle>
                </CardHeader>
                <CardContent>
                    <div>
                        <Input v-model="form.name" placeholder="Tag Name" required/>
                        <InputError :message="form.errors.name" />
                    </div>
                </CardContent>
                <CardFooter>
                    <Button @click="createStatus">Create</Button>
                </CardFooter>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Statuses</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <tr v-for="status in taskStatuses" :key="status.id">
                                    <td>{{ status.name }}</td>
                                    <td>
                                        <Button v-if="isAdmin" @click="deleteStatus(status.id)" variant="destructive">Delete</Button>
                                    </td>
                                </tr>
                            </TableBody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>