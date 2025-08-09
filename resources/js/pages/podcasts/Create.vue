<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Podcasts', href: '/podcasts' },
    { title: 'Create', href: '/podcasts/create' },
];

const form = useForm({
    title: '',
    description: '',
    topic: '',
    tone: '',
    duration_minutes: '',
    source_file: null as File | null,
    source_text: '',
});

function submit() {
    form.post(route('podcasts.store'), {
        forceFormData: true,
    });
}

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.source_file = target.files[0];
    }
}
</script>

<template>
    <Head title="Create Podcast" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex max-w-5xl flex-col gap-6 p-6">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">New Podcast</h1>
                <p class="mt-1 text-sm text-muted-foreground">Define the core attributes of your podcast episode.</p>
            </div>

            <form @submit.prevent="submit" class="grid gap-8">
                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-0">
                        <HeadingSmall title="Basic details" description="Title, topic & tone." />
                    </CardHeader>
                    <CardContent class="grid gap-6 pt-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="title">Title</Label>
                            <Input id="title" v-model="form.title" placeholder="Compelling title" />
                            <InputError :message="form.errors.title" />
                        </div>
                        <div class="space-y-2">
                            <Label for="topic">Topic</Label>
                            <Input id="topic" v-model="form.topic" placeholder="Main subject" />
                            <InputError :message="form.errors.topic" />
                        </div>
                        <div class="space-y-2">
                            <Label for="tone">Tone</Label>
                            <Input id="tone" v-model="form.tone" placeholder="e.g. Informative, Casual" />
                            <InputError :message="form.errors.tone" />
                        </div>
                        <div class="space-y-2">
                            <Label for="duration_minutes">Duration (minutes)</Label>
                            <Input id="duration_minutes" v-model="form.duration_minutes" placeholder="e.g. 30" />
                            <InputError :message="form.errors.duration_minutes" />
                        </div>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-0">
                        <HeadingSmall title="Description" description="Optional overview or notes." />
                    </CardHeader>
                    <CardContent class="pt-6">
                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="5"
                                placeholder="Describe the podcast focus, target audience, structure..."
                                class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-none"
                            />
                            <InputError :message="form.errors.description" />
                        </div>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-0">
                        <HeadingSmall title="Source material" description="Upload a file or paste raw text to kickstart the script." />
                    </CardHeader>
                    <CardContent class="grid gap-6 pt-6">
                        <div class="space-y-2">
                            <Label for="source_file">Upload file (PDF / TXT / DOCX)</Label>
                            <input
                                id="source_file"
                                type="file"
                                accept=".pdf,.txt,.doc,.docx"
                                @change="handleFileChange"
                                class="block w-full cursor-pointer text-sm file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-primary-foreground hover:file:bg-primary/90"
                            />
                            <InputError :message="form.errors.source_file" />
                            <p class="text-xs text-muted-foreground" v-if="form.source_file">Selected: {{ form.source_file.name }}</p>
                        </div>
                        <div class="space-y-2">
                            <Label for="source_text">Or paste text</Label>
                            <textarea
                                id="source_text"
                                v-model="form.source_text"
                                rows="8"
                                placeholder="Paste transcript, outline, or notes here..."
                                class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-none"
                            />
                            <InputError :message="form.errors.source_text" />
                            <p class="text-xs text-muted-foreground">You can provide both; text helps immediate script generation.</p>
                        </div>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-0">
                        <HeadingSmall title="Workflow status" description="Status flags are managed automatically." />
                    </CardHeader>
                    <CardContent class="grid gap-2 pt-6 text-sm text-muted-foreground">
                        <p>New podcasts start in <span class="font-medium text-foreground">draft</span> status.</p>
                        <p>File upload, script & audio readiness flags will update as you progress.</p>
                    </CardContent>
                    <CardFooter class="flex justify-end gap-3 pt-0">
                        <Button type="button" variant="secondary" @click="form.reset()" :disabled="form.processing">Reset</Button>
                        <Button :disabled="form.processing">Create podcast</Button>
                    </CardFooter>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
