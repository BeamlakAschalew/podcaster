<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, ChevronDown, Menu, Play, Podcast, Radio, Target, X, Zap } from 'lucide-vue-next';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

// Auth presence (reuse prop from inertia)
const props = defineProps<{ auth?: { user?: any } }>();
const isAuthenticated = computed(() => Boolean(props.auth?.user));

// Mobile menu & scroll state
const mobileMenuOpen = ref(false);
const scrollY = ref(0);

const scrolled = computed(() => scrollY.value > 8);

function toggleMobile() {
    mobileMenuOpen.value = !mobileMenuOpen.value;
}

function closeMobile() {
    mobileMenuOpen.value = false;
}

function onScroll() {
    scrollY.value = window.scrollY;
}

onMounted(() => {
    scrollY.value = window.scrollY;
    window.addEventListener('scroll', onScroll, { passive: true });
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <Head title="PodcastAI – Create AI Podcasts">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="min-h-screen overflow-x-hidden bg-background font-sans text-foreground antialiased">
        <!-- Navigation -->
        <nav
            :class="[
                'fixed inset-x-0 top-0 z-50 border-b backdrop-blur-xl transition-all',
                scrolled ? 'bg-background/90 shadow-sm' : 'bg-background/70',
            ]"
        >
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6">
                <div class="flex items-center space-x-2">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-primary/90 to-primary text-sm font-bold text-primary-foreground shadow"
                    >
                        AI
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm font-semibold tracking-tight">PodcastAI</span>
                        <span class="text-[10px] tracking-wide text-muted-foreground uppercase">Auto Podcast</span>
                    </div>
                </div>
                <!-- Desktop Links -->
                <div class="hidden items-center space-x-8 text-sm font-medium lg:flex">
                    <a href="#features" class="text-muted-foreground transition-colors hover:text-foreground">Features</a>
                    <a href="#how-it-works" class="text-muted-foreground transition-colors hover:text-foreground">How it Works</a>
                    <a href="#pricing" class="text-muted-foreground transition-colors hover:text-foreground">Pricing</a>
                    <Link v-if="isAuthenticated" :href="route('dashboard')">
                        <Button variant="ghost" size="sm">Dashboard</Button>
                    </Link>
                    <template v-else>
                        <Link :href="route('login')"><Button variant="ghost" size="sm">Sign In</Button></Link>
                        <Link :href="route('register')"
                            ><Button size="sm" class="bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary/80"
                                >Get Started</Button
                            ></Link
                        >
                    </template>
                </div>
                <!-- Mobile -->
                <Button
                    variant="ghost"
                    size="icon"
                    class="lg:hidden"
                    @click="toggleMobile"
                    aria-label="Toggle navigation"
                    :aria-expanded="mobileMenuOpen"
                >
                    <component :is="mobileMenuOpen ? X : Menu" class="size-5" />
                </Button>
            </div>
            <!-- Mobile Menu -->
            <transition name="fade">
                <div v-if="mobileMenuOpen" class="border-t bg-background/95 backdrop-blur-xl lg:hidden">
                    <div class="space-y-4 px-4 py-4 sm:px-6">
                        <a href="#features" @click="closeMobile" class="block text-sm font-medium text-muted-foreground hover:text-foreground"
                            >Features</a
                        >
                        <a href="#how-it-works" @click="closeMobile" class="block text-sm font-medium text-muted-foreground hover:text-foreground"
                            >How it Works</a
                        >
                        <a href="#pricing" @click="closeMobile" class="block text-sm font-medium text-muted-foreground hover:text-foreground"
                            >Pricing</a
                        >
                        <div class="space-y-3 pt-2">
                            <template v-if="isAuthenticated">
                                <Link :href="route('dashboard')" @click="closeMobile"
                                    ><Button variant="outline" class="w-full">Dashboard</Button></Link
                                >
                            </template>
                            <template v-else>
                                <Link :href="route('login')" @click="closeMobile"><Button variant="ghost" class="w-full">Sign In</Button></Link>
                                <Link :href="route('register')" @click="closeMobile"
                                    ><Button class="w-full bg-gradient-to-r from-primary to-primary/90">Get Started</Button></Link
                                >
                            </template>
                        </div>
                    </div>
                </div>
            </transition>
        </nav>

        <!-- Hero -->
        <section class="relative flex min-h-screen items-center justify-center px-4 pt-24 sm:px-6">
            <!-- Background blobs -->
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-background to-secondary/5"></div>
                <div
                    class="absolute top-32 left-4 h-48 w-48 animate-pulse rounded-full bg-gradient-to-br from-primary/20 to-primary/5 blur-3xl sm:left-16 sm:h-64 sm:w-64"
                ></div>
                <div
                    class="absolute top-48 right-4 h-64 w-64 animate-pulse rounded-full bg-gradient-to-br from-secondary/20 to-accent/10 blur-3xl delay-1000 sm:right-20 sm:h-80 sm:w-80"
                ></div>
                <div
                    class="absolute bottom-32 left-1/4 h-56 w-56 animate-pulse rounded-full bg-gradient-to-br from-accent/20 to-primary/10 blur-3xl delay-2000 sm:h-72 sm:w-72"
                ></div>
                <div
                    class="absolute inset-0 bg-[linear-gradient(rgba(0,0,0,.04)_1px,transparent_1px),linear-gradient(90deg,rgba(0,0,0,.04)_1px,transparent_1px)] [mask-image:radial-gradient(ellipse_70%_40%_at_50%_0%,#000_55%,transparent_100%)] bg-[size:48px_48px] sm:bg-[size:64px_64px] dark:opacity-40"
                ></div>
            </div>
            <div class="relative z-10 mx-auto max-w-5xl space-y-6 text-center sm:space-y-8">
                <div class="animate-fade-in-up flex justify-center" style="animation-delay: 0.1s">
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-primary/30 bg-background/70 px-4 py-2 text-xs font-medium backdrop-blur-sm sm:text-sm"
                    >
                        <Podcast class="size-4 text-primary" /> <span class="tracking-wide">AI Powered Conversations</span>
                    </div>
                </div>
                <div class="animate-fade-in-up space-y-4" style="animation-delay: 0.2s">
                    <h1 class="text-3xl leading-tight font-bold tracking-tight sm:text-5xl md:text-6xl">
                        Generate Multi‑Host Podcasts
                        <span class="bg-gradient-to-r from-primary to-primary/60 bg-clip-text text-transparent">In Minutes</span>
                    </h1>
                    <div class="mx-auto h-1 w-24 rounded-full bg-gradient-to-r from-primary to-primary/50"></div>
                </div>
                <div class="animate-fade-in-up mx-auto max-w-3xl" style="animation-delay: 0.3s">
                    <p class="text-base leading-relaxed text-muted-foreground sm:text-lg md:text-xl">
                        Provide a topic or notes and our AI writes natural dialogue between hosts, voices it, mixes it, and gives you a publish‑ready
                        episode.
                    </p>
                </div>
                <div class="animate-fade-in-up flex flex-col items-center justify-center gap-4 sm:flex-row" style="animation-delay: 0.4s">
                    <Link :href="isAuthenticated ? route('dashboard') : route('register')">
                        <Button
                            size="lg"
                            class="w-full bg-gradient-to-r from-primary to-primary/90 px-8 py-6 text-base font-semibold shadow-lg hover:from-primary/90 hover:to-primary/80 hover:shadow-xl sm:w-auto"
                            >Get Started</Button
                        >
                    </Link>
                    <Button
                        size="lg"
                        variant="outline"
                        class="w-full border-2 px-8 py-6 text-base font-semibold hover:border-primary/50 hover:bg-primary/5 sm:w-auto"
                    >
                        <Play class="mr-2 size-5" /> Listen to Demo
                    </Button>
                </div>
                <!-- trust indicators placeholder -->
                <div
                    class="animate-fade-in-up flex flex-wrap justify-center gap-6 text-xs text-muted-foreground sm:text-sm"
                    style="animation-delay: 0.5s"
                >
                    <div class="h-6 w-24 rounded bg-muted/40" />
                    <div class="h-6 w-24 rounded bg-muted/40" />
                    <div class="h-6 w-24 rounded bg-muted/40" />
                </div>
            </div>
            <!-- Scroll cue -->
            <div
                class="absolute bottom-6 left-1/2 flex -translate-x-1/2 animate-bounce flex-col items-center text-muted-foreground"
                aria-hidden="true"
            >
                <span class="mb-1 text-xs font-medium">Explore Features</span>
                <ChevronDown class="size-4" />
            </div>
        </section>

        <!-- Stats (placeholders) -->
        <section class="animate-fade-in-up bg-gradient-to-r from-muted/30 via-background to-muted/30 px-4 py-16 sm:px-6">
            <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 md:grid-cols-4">
                <div v-for="i in 4" :key="'stat-' + i" class="space-y-2 text-center">
                    <div class="mx-auto h-8 w-8 rounded-full bg-primary/10" />
                    <div class="mx-auto h-4 w-24 rounded bg-muted/50" />
                    <div class="mx-auto h-3 w-20 rounded bg-muted/30" />
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="animate-fade-in-up relative px-4 py-20 sm:px-6">
            <div class="absolute inset-0 bg-gradient-to-b from-background via-muted/10 to-background" />
            <div class="relative mx-auto max-w-7xl">
                <div class="mb-16 space-y-4 text-center">
                    <div class="inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-sm"><Zap class="size-3" /> Powerful Features</div>
                    <h2 class="text-3xl font-bold tracking-tight md:text-4xl">Everything You Need to Create</h2>
                    <p class="mx-auto max-w-2xl text-muted-foreground">
                        Our AI platform handles the heavy lifting so you can focus on ideas & distribution.
                    </p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <Card
                        v-for="n in 6"
                        :key="'feature-' + n"
                        class="group min-h-52 border bg-gradient-to-br from-background to-muted/20 transition-all duration-300 hover:-translate-y-1 hover:border-primary/30 hover:shadow-lg"
                    />
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="how-it-works" class="animate-fade-in-up bg-gradient-to-br from-primary/5 via-background to-secondary/5 px-4 py-20 sm:px-6">
            <div class="mx-auto max-w-7xl">
                <div class="mb-16 space-y-4 text-center">
                    <div class="inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-sm"><Target class="size-3" /> Simple Process</div>
                    <h2 class="text-3xl font-bold tracking-tight md:text-4xl">From Idea to Podcast</h2>
                    <p class="mx-auto max-w-2xl text-muted-foreground">Three streamlined steps turn your concept into a polished episode.</p>
                </div>
                <div class="mb-16 grid gap-12 sm:grid-cols-3">
                    <div v-for="s in 3" :key="'step-' + s" class="space-y-4 text-center">
                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-primary/70 font-bold text-primary-foreground"
                        >
                            {{ s }}
                        </div>
                        <div class="mx-auto h-4 w-32 rounded bg-muted/50" />
                        <div class="mx-auto h-3 w-40 rounded bg-muted/30" />
                    </div>
                </div>
                <Card class="mx-auto max-w-4xl border bg-gradient-to-br from-background to-muted/20 shadow-xl">
                    <div class="space-y-6 px-6 py-10 text-center md:px-10">
                        <div class="mx-auto h-6 w-40 rounded bg-muted/40" />
                        <div class="mx-auto h-10 w-full max-w-xl rounded-lg bg-muted/20" />
                        <div class="grid gap-4 sm:grid-cols-3">
                            <div class="h-12 rounded bg-muted/20" />
                            <div class="h-12 rounded bg-muted/20" />
                            <div class="h-12 rounded bg-muted/20" />
                        </div>
                        <div class="flex justify-center"><Button disabled size="lg" class="opacity-60">Generate Sample</Button></div>
                    </div>
                </Card>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="animate-fade-in-up relative overflow-hidden px-4 py-20 sm:px-6">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-background to-secondary/5" />
            <div class="absolute top-0 left-1/4 h-64 w-64 rounded-full bg-primary/10 blur-3xl" />
            <div class="absolute right-1/4 bottom-0 h-64 w-64 rounded-full bg-secondary/10 blur-3xl" />
            <div class="relative mx-auto max-w-7xl">
                <div class="mb-16 space-y-4 text-center">
                    <div class="inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-sm"><Radio class="size-3" /> Success Stories</div>
                    <h2 class="text-3xl font-bold tracking-tight md:text-4xl">Loved by Creators</h2>
                    <p class="mx-auto max-w-2xl text-muted-foreground">Creators of all sizes accelerate content pipelines with PodcastAI.</p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <Card
                        v-for="t in 3"
                        :key="'test-' + t"
                        class="group min-h-60 border bg-gradient-to-br from-background to-muted/20 transition-all duration-300 hover:-translate-y-1 hover:border-primary/30 hover:shadow-lg"
                    />
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="animate-fade-in-up relative overflow-hidden px-4 py-20 sm:px-6">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-background to-secondary/10" />
            <div class="absolute top-1/2 left-1/2 h-[500px] w-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/10 blur-3xl" />
            <div class="relative mx-auto max-w-4xl text-center">
                <div
                    class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-primary/80 text-primary-foreground shadow-lg"
                >
                    <Podcast class="size-8" />
                </div>
                <h2 class="mb-4 text-3xl font-bold md:text-4xl">Ready to Create Your First <span class="text-primary">AI Podcast?</span></h2>
                <p class="mx-auto mb-8 max-w-2xl leading-relaxed text-muted-foreground">
                    Join creators using PodcastAI to bring ideas to life and reach new audiences.
                </p>
                <div class="mb-8 flex flex-col justify-center gap-4 sm:flex-row">
                    <Link :href="isAuthenticated ? route('dashboard') : route('register')"
                        ><Button size="lg" class="bg-gradient-to-r from-primary to-primary/90 px-8 hover:from-primary/90 hover:to-primary/80"
                            >Start Free Trial <ArrowRight class="ml-2 size-4" /></Button
                    ></Link>
                    <a href="#pricing"
                        ><Button size="lg" variant="outline" class="border-2 px-8 hover:bg-primary hover:text-primary-foreground"
                            >View Pricing Plans</Button
                        ></a
                    >
                </div>
                <div class="flex flex-wrap justify-center gap-6 text-xs text-muted-foreground">
                    <div class="h-4 w-24 rounded bg-muted/40" />
                    <div class="h-4 w-24 rounded bg-muted/40" />
                    <div class="h-4 w-24 rounded bg-muted/40" />
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t bg-gradient-to-br from-muted/20 to-background px-4 py-14 text-sm sm:px-6">
            <div class="mx-auto max-w-7xl space-y-10">
                <div class="grid grid-cols-2 gap-8 md:grid-cols-5">
                    <div class="col-span-2 space-y-4">
                        <div class="flex items-center space-x-2">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-primary/90 to-primary text-sm font-bold text-primary-foreground shadow"
                            >
                                AI
                            </div>
                            <span class="font-semibold tracking-tight">PodcastAI</span>
                        </div>
                        <p class="max-w-sm text-sm text-muted-foreground">
                            AI generated multi‑host podcast episodes. Script, voices, music & mix in one platform.
                        </p>
                    </div>
                    <div class="space-y-3">
                        <h3 class="text-sm font-semibold">Product</h3>
                        <ul class="space-y-2 text-muted-foreground">
                            <li><a href="#features" class="hover:text-foreground">Features</a></li>
                            <li><a href="#how-it-works" class="hover:text-foreground">How it works</a></li>
                            <li><a href="#pricing" class="hover:text-foreground">Pricing</a></li>
                        </ul>
                    </div>
                    <div class="space-y-3">
                        <h3 class="text-sm font-semibold">Company</h3>
                        <ul class="space-y-2 text-muted-foreground">
                            <li><a href="#" class="hover:text-foreground">About</a></li>
                            <li><a href="#" class="hover:text-foreground">Blog</a></li>
                            <li><a href="#" class="hover:text-foreground">Careers</a></li>
                        </ul>
                    </div>
                    <div class="space-y-3">
                        <h3 class="text-sm font-semibold">Legal</h3>
                        <ul class="space-y-2 text-muted-foreground">
                            <li><a href="#" class="hover:text-foreground">Terms</a></li>
                            <li><a href="#" class="hover:text-foreground">Privacy</a></li>
                            <li><a href="#" class="hover:text-foreground">Cookies</a></li>
                        </ul>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-between border-t pt-6 text-xs text-muted-foreground sm:flex-row">
                    <p>&copy; 2025 PodcastAI. All rights reserved.</p>
                    <div class="mt-4 flex gap-4 sm:mt-0">
                        <a href="#" class="hover:text-foreground">Docs</a>
                        <a href="#" class="hover:text-foreground">Status</a>
                        <a href="#" class="hover:text-foreground">Contact</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
    opacity: 0;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
