<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { LoaderCircle } from 'lucide-vue-next';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Teams',
        href: '/teams',
    },
];

defineProps<{
    teams: any[];
}>();

const page = usePage();
const isSuperAdmin = computed(() => page.props.auth.user.is_super_admin);
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const createUser = () => {
    form.post(route('teams.createUser'), {
        onSuccess: () => form.reset(),
    });
};

const createTeam = () => {
    router.get(route('teams.create'));
};

const showTeam = (teamId: number) => {
    router.get(route('teams.show', teamId.toString()));
};

const editTeam = (teamId: number) => {
    router.get(route('teams.edit', teamId.toString()));
};

const deleteTeam = (teamId: number) => {
    if (confirm('Are you sure you want to delete this team?')) {
        router.delete(route('teams.destroy', teamId.toString()));
    }
};

</script>

<template>
    <Head title="Teams" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">Teams</h1>

            <Button v-if="isSuperAdmin" @click="createTeam">+ New Team</Button>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Card v-for="team in teams" :key="team.id">
                    <CardHeader>
                        <CardTitle>{{ team.name }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Button @click="showTeam(team.id)" class="mr-2">View</Button>
                        <Button v-if="isSuperAdmin" @click="editTeam(team.id)" variant="secondary" class="mr-2">Edit</Button>
                        <Button v-if="isSuperAdmin" variant="destructive" @click="deleteTeam(team.id)">Delete</Button>
                    </CardContent>
                </Card>
            </div>
        </div>

        <div v-if="isSuperAdmin" class="p-6 space-y-4">
            <h1 class="text-2xl font-bold">Create User</h1>
            <Card>
                <CardHeader>Create User</CardHeader>
                <CardContent>
                    <form @submit.prevent="createUser" class="flex flex-col gap-6">
                        <div class="grid gap-6">
                            <div class="grid gap-2">
                                <Label for="name">Name</Label>
                                <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Full name" />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="email">Email address</Label>
                                <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password">Password</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    required
                                    :tabindex="3"
                                    autocomplete="new-password"
                                    v-model="form.password"
                                    placeholder="Password"
                                />
                                <InputError :message="form.errors.password" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password_confirmation">Confirm password</Label>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    required
                                    :tabindex="4"
                                    autocomplete="new-password"
                                    v-model="form.password_confirmation"
                                    placeholder="Confirm password"
                                />
                                <InputError :message="form.errors.password_confirmation" />
                            </div>

                            <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                Create user
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>


    </AppLayout>
</template>

<style scoped>

</style>