<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Wand2 } from 'lucide-vue-next';

const props = defineProps<{
    podcast: any;
    script: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Podcasts', href: '/podcasts' },
    { title: props.podcast.title, href: `/podcasts/${props.podcast.slug}` },
];
</script>

<template>
    <Head :title="podcast.title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex max-w-5xl flex-col gap-6 p-6">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">{{ podcast.title }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Topic: {{ podcast.topic }} • Tone: {{ podcast.tone
                    }}<span v-if="podcast.duration_minutes"> • {{ podcast.duration_minutes }}m</span>
                </p>
            </div>

            <div class="grid gap-6">
                <div class="rounded-lg border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <h2 class="mb-2 text-base font-medium">Description</h2>
                    <p class="text-sm leading-relaxed" v-if="podcast.description">{{ podcast.description }}</p>
                    <p v-else class="text-sm text-muted-foreground italic">No description provided.</p>
                </div>

                <div class="grid gap-4 rounded-lg border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <h2 class="text-base font-medium">Production Status</h2>
                    <div class="grid gap-4 text-sm md:grid-cols-3">
                        <div
                            class="flex flex-col gap-1 rounded-md border p-4"
                            :class="podcast.file_uploaded ? 'border-emerald-500/40 bg-emerald-500/5' : ''"
                        >
                            <span class="font-medium">Source File</span>
                            <span
                                class="text-xs"
                                :class="podcast.file_uploaded ? 'text-emerald-600 dark:text-emerald-500' : 'text-muted-foreground'"
                                >{{ podcast.file_uploaded ? 'Uploaded' : 'Pending' }}</span
                            >
                        </div>
                        <div
                            class="flex flex-col gap-1 rounded-md border p-4"
                            :class="podcast.script_ready ? 'border-emerald-500/40 bg-emerald-500/5' : ''"
                        >
                            <span class="font-medium">Final Script</span>
                            <span
                                class="text-xs"
                                :class="podcast.script_ready ? 'text-emerald-600 dark:text-emerald-500' : 'text-muted-foreground'"
                                >{{ podcast.script_ready ? 'Ready' : 'Pending' }}</span
                            >
                        </div>
                        <div
                            class="flex flex-col gap-1 rounded-md border p-4"
                            :class="podcast.audio_ready ? 'border-emerald-500/40 bg-emerald-500/5' : ''"
                        >
                            <span class="font-medium">Audio</span>
                            <span class="text-xs" :class="podcast.audio_ready ? 'text-emerald-600 dark:text-emerald-500' : 'text-muted-foreground'">{{
                                podcast.audio_ready ? 'Ready' : 'Pending'
                            }}</span>
                        </div>
                    </div>
                </div>
                <div class="grid gap-4 text-sm md:grid-cols-2">
                    <div class="flex flex-col gap-1 rounded-md border p-4" :class="script.raw_text ? 'border-emerald-500/40 bg-emerald-500/5' : ''">
                        <span class="font-medium">Raw text</span>
                        <span class="text-xs" :class="script.raw_text ? 'text-emerald-600 dark:text-emerald-500' : 'text-muted-foreground'">{{
                            script.raw_text
                        }}</span>
                    </div>
                    <div class="flex flex-col gap-1 rounded-md border p-4" :class="script.final_text ? 'border-emerald-500/40 bg-emerald-500/5' : ''">
                        <span class="font-medium">AI final text</span>
                        <div v-if="script.final_text" class="text-xs text-emerald-600 dark:text-emerald-500">
                            {{ script.final_text }}
                        </div>
                        <div v-else class="flex items-center justify-center pt-1">
                            <Button size="sm" variant="secondary" class="w-fit">
                                <Wand2 />
                                Generate
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
