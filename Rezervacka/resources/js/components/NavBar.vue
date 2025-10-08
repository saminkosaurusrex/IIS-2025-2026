<!-- NavMenu.vue -->
<template>
    <header class="nav-header">
        <nav class="mx-auto px-6 lg:px-8">
            <div class="flex h-[6vh] items-center justify-between">
                <!-- Logo a Menu vľavo -->
                <div class="flex items-center gap-8">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <div class="flex h-10 w-10 items-center justify-center">
                            <Link :href="'/'">
                                <img src="/images/logo.svg" alt="Logo" />
                            </Link>
                        </div>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex md:items-center md:gap-6">
                        <Link
                            v-for="type in types"
                            :key="type.id"
                            :href="'/' + type.name"
                            class="nav-link"
                        >
                            {{ type.name }}
                        </Link>
                    </div>
                </div>

                <!-- Desktop Auth Buttons vpravo -->
                <div class="hidden md:flex md:items-center md:gap-3">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="'/dashboard'"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="login()" class="nav-btn-secondary">
                            Prihlásiť sa
                        </Link>
                        <Link :href="register()" class="nav-btn-primary">
                            Registrovať sa
                        </Link>
                    </template>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button
                        @click="toggleMenu"
                        class="nav-mobile-btn"
                        aria-label="Toggle menu"
                    >
                        <svg
                            v-if="!isMenuOpen"
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                        <svg
                            v-else
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-if="isMenuOpen" class="nav-mobile-menu">
                <Link
                    v-for="type in types"
                    :key="type.id"
                    :href="'/' + type.name"
                    class="nav-mobile-link"
                >
                    {{ type.name }}
                </Link>
                <div class="nav-mobile-buttons">

                    <Link
                        v-if="$page.props.auth.user"
                        :href="'/dashboard'"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link :href="login()" class="nav-mobile-btn-secondary">
                            Prihlásiť sa
                        </Link>
                        <Link :href="register()" class="nav-mobile-btn-primary">
                            Registrovať sa
                        </Link>
                    </template>

                </div>
            </div>
        </nav>
    </header>
</template>

<script setup lang="ts">
import { login, register } from '@/routes';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Type {
    id: number;
    name: string;
}

const page = usePage();
const types = page.props.types as Type[];

const isMenuOpen = ref<boolean>(false);

const toggleMenu = (): void => {
    isMenuOpen.value = !isMenuOpen.value;
};
</script>

<style scoped>
/* Header */
.nav-header {
    background-color: white;
    border-bottom: 1px solid var(--color-secondary);
    font-family: var(--font-sans);
}

/* Desktop Links */
.nav-link {
    color: var(--color-primary);
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.2s;
}

.nav-link:hover {
    color: var(--color-myred);
}

/* Desktop Buttons */
.nav-btn-secondary {
    color: var(--color-primary);
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.2s;
}

.nav-btn-secondary:hover {
    color: var(--color-myred);
}

.nav-btn-primary {
    background-color: var(--color-primary);
    color: var(--color-mywhite);
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: background-color 0.2s;
}

.nav-btn-primary:hover {
    background-color: var(--color-myred);
}

/* Mobile Menu Button */
.nav-mobile-btn {
    color: var(--color-primary);
    padding: 0.5rem;
    transition: color 0.2s;
}

.nav-mobile-btn:hover {
    color: var(--color-myred);
}

/* Mobile Menu */
.nav-mobile-menu {
    padding: 1rem 0;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    border-top: 1px solid var(--color-secondary);
    margin-top: 0.5rem;
}

.nav-mobile-link {
    display: block;
    color: var(--color-primary);
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.2s;
}

.nav-mobile-link:hover {
    color: var(--color-myred);
    background-color: rgba(141, 153, 174, 0.1);
}

/* Mobile Buttons Container */
.nav-mobile-buttons {
    padding-top: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    border-top: 1px solid var(--color-secondary);
    margin-top: 0.5rem;
}

.nav-mobile-btn-secondary {
    width: 100%;
    text-align: left;
    color: var(--color-primary);
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.2s;
}

.nav-mobile-btn-secondary:hover {
    color: var(--color-myred);
    background-color: rgba(141, 153, 174, 0.1);
}

.nav-mobile-btn-primary {
    width: 100%;
    background-color: var(--color-primary);
    color: var(--color-mywhite);
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 1rem;
    font-weight: 500;
    transition: background-color 0.2s;
}

.nav-mobile-btn-primary:hover {
    background-color: var(--color-myred);
}

@media (min-width: 768px) {
    .nav-mobile-menu {
        display: none;
    }
}
</style>
