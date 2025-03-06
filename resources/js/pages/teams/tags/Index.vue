<script setup lang="ts">

import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';

const props = defineProps({ team: Object, tags: Object });
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
        title: 'Tags',
        href: `/teams/${props.team.id}/tags`,
    },

];

const form = useForm({
    name: '',
    color: '',
});

const createTag = () => {
    if (form.color === '#000000') {
        form.color = '';
    }
    form.post(`/teams/${props.team.id}/tags`, {
        onSuccess: () => {
            form.reset();
        },
    });
};

const deleteTag = (tagId: number) => {
    if (confirm('Are you sure you want to delete this tag?')) {
        router.delete(route('teams.tags.destroy', [props.team.id, tagId]));
    }
};
</script>

<template>
    <Head title="Tags" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">Tags</h1>
            <Card>
                <CardHeader>
                    <CardTitle>New Tag</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="mb-4">
                        <Input v-model="form.name" placeholder="Tag Name" required/>
                        <InputError :message="form.errors.name" />
                    </div>
                    <div>
                        <Input v-model="form.color" type="color"  class="w-20" />
                        <InputError :message="form.errors.color" />
                    </div>

                </CardContent>
                <CardFooter>
                    <Button @click="createTag">Create</Button>
                </CardFooter>
            </Card>
            <Table class="w-full">
                <TableCaption>The list of the teams tags</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Color</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="tag in props.tags" :key="tag.id">
                        <TableCell>{{ tag.name }}</TableCell>
                        <TableCell><Input :value="tag.color" disabled class="w-20" type="color"/></TableCell>
                        <TableCell>
                            <Button v-if="isAdmin" @click="deleteTag(tag.id)" variant="destructive">Delete</Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>