<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Podcasts', href: '/podcasts' }];

const page = usePage();
const podcasts = page.props.podcasts as any[];
</script>

<template>
    <Head title="Podcasts" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex max-w-6xl flex-col gap-6 p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">Podcasts</h1>
                    <p class="mt-1 text-sm text-muted-foreground">Manage your generated podcast episodes.</p>
                </div>
                <Button as-child>
                    <Link :href="route('podcasts.create')">New Podcast</Link>
                </Button>
            </div>

            <div v-if="podcasts.length" class="grid gap-4">
                <div
                    v-for="pod in podcasts"
                    :key="pod.id"
                    class="flex flex-col gap-1 rounded-lg border border-sidebar-border/70 p-4 transition-colors hover:bg-muted/40 dark:border-sidebar-border"
                >
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-medium">
                            <Link :href="route('podcasts.show', pod.slug)" class="hover:underline">{{ pod.title }}</Link>
                        </h2>
                        <span class="rounded-md bg-muted px-2 py-0.5 text-xs tracking-wide uppercase">{{ pod.status }}</span>
                    </div>
                    <p class="line-clamp-2 text-sm text-muted-foreground">{{ pod.description }}</p>
                    <div class="mt-1 flex gap-4 text-xs text-muted-foreground">
                        <span
                            >Topic: <span class="font-medium text-foreground">{{ pod.topic }}</span></span
                        >
                        <span
                            >Tone: <span class="font-medium text-foreground">{{ pod.tone }}</span></span
                        >
                        <span v-if="pod.duration_minutes">Dur: {{ pod.duration_minutes }}m</span>
                    </div>
                </div>
            </div>
            <div v-else class="rounded-lg border border-dashed p-8 text-center text-sm text-muted-foreground">
                No podcasts yet. Create your first one.
            </div>
        </div>
    </AppLayout>
</template>
