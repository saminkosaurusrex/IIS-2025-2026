<template>
    <div class="app-layout flex h-screen flex-col overflow-hidden">
        <NavMenu />

        <!-- Banner - 50% výšky -->
        <section class="banner relative h-[50vh] overflow-hidden">
            <img
                v-if="currentShow.image"
                :src="currentShow.image"
                alt="Obrázok predstavenia"
                class="h-full w-full object-cover"
            />

            <div
                class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent"
            ></div>

            <div
                class="absolute inset-0 z-10 flex flex-col items-start justify-center p-4 sm:p-6 md:p-8 lg:p-12"
            >
                <h3
                    class="mb-4 max-w-[90%] leading-tight font-semibold text-white sm:mb-6 md:mb-8 lg:max-w-[70%]"
                    style="font-size: clamp(1.5rem, 4vw, 3.5rem)"
                >
                    {{ currentShow.name }}
                </h3>

                <div
                    class="flex max-w-[90%] flex-wrap items-center gap-2 sm:gap-3"
                >
                    <div
                        class="w-fit rounded-full bg-amber-50 px-3 py-1.5 whitespace-nowrap sm:rounded-3xl sm:px-4 sm:py-2"
                        style="font-size: clamp(0.75rem, 1.2vw, 1rem)"
                    >
                        {{ currentShow.show_type.name }}
                    </div>

                    <div class="flex flex-wrap gap-1.5 sm:gap-2">
                        <span
                            v-for="tag in currentShow.tags"
                            :key="tag.id"
                            class="rounded-full bg-white/80 px-2.5 py-1 whitespace-nowrap text-gray-800 sm:px-3"
                            style="font-size: clamp(0.7rem, 1vw, 0.875rem)"
                        >
                            {{ tag.name }}
                        </span>
                    </div>
                </div>
            </div>

            <div
                class="absolute bottom-3 left-3 z-20 w-fit cursor-pointer rounded-xl bg-amber-50 px-4 py-2 font-medium transition-colors hover:bg-amber-100 sm:bottom-4 sm:left-4 sm:rounded-2xl sm:px-6 sm:py-3"
                style="font-size: clamp(0.875rem, 1.2vw, 1.125rem)"
                @click.stop="onCardClick(currentShow)"
            >
                Rezervovať
            </div>

            <div
                class="absolute bottom-3 left-1/2 z-20 flex -translate-x-1/2 transform space-x-1.5 sm:bottom-4 sm:space-x-2"
            >
                <span
                    v-for="(show, index) in newestShows"
                    :key="show.id"
                    @click="goToSlide(index)"
                    :class="[
                        'h-2 w-2 cursor-pointer rounded-full transition-colors sm:h-3 sm:w-3',
                        currentIndex === index ? 'bg-white' : 'bg-gray-400',
                    ]"
                ></span>
            </div>
        </section>

        <!-- Sekcia Odporúčame -->
        <div class="flex flex-col p-3 sm:p-4 md:p-6">
            <h1
                class="mb-2 text-center font-semibold sm:mb-3 md:mb-4"
                style="font-size: clamp(1.5rem, 3vw, 3rem)"
            >
                Odporúčame
            </h1>

            <div class="relative flex w-full items-center gap-4">
                <!-- Šípka vľavo -->
                <button
                    @click="scrollLeft"
                    class="z-10 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-white/90 shadow-lg transition-all hover:scale-110 hover:bg-white disabled:cursor-not-allowed disabled:opacity-50 sm:h-12 sm:w-12"
                    :disabled="scrollPosition <= 0"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2.5"
                        stroke="currentColor"
                        class="h-5 w-5 sm:h-6 sm:w-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15.75 19.5L8.25 12l7.5-7.5"
                        />
                    </svg>
                </button>

                <!-- Scroll container -->
                <div
                    ref="scrollContainer"
                    @scroll="updateScrollPosition"
                    class="hide-scrollbar flex flex-1 gap-3 overflow-x-auto scroll-smooth p-5 sm:gap-4"
                >
                    <div
                        v-for="bestShow in bestShows"
                        :key="bestShow.id"
                        class="flex flex-shrink-0 px-1"
                    >
                        <!-- Wrapper pre hover efekt -->
                        <div
                            class="relative w-[250px] cursor-pointer transition-transform duration-300 hover:scale-105"
                            @click="onCardClick(bestShow)"
                        >
                            <!-- Karta -->
                            <div
                                class="bg-p-4 rounded-2xs flex flex-col justify-end"
                                style="height: 35vh"
                            >
                                <!-- Obrázok -->
                                <div
                                    class="relative flex-1 overflow-hidden rounded-2xl bg-amber-800"
                                >
                                    <img
                                        v-if="bestShow.image"
                                        :src="bestShow.image"
                                        alt="Obrázok predstavenia"
                                        class="h-full w-full object-cover transition-transform duration-300"
                                    />

                                    <!-- Overlay -->
                                    <div
                                        class="absolute inset-0 flex flex-col items-center justify-center bg-black/0 p-4 text-center text-white opacity-0 transition-all duration-300 hover:bg-black/60 hover:opacity-100"
                                    >
                                        <h2 class="mb-2 text-lg font-semibold">
                                            {{ bestShow.name }}
                                        </h2>
                                        <span
                                            class="rounded-full bg-yellow-400 px-3 py-1 text-sm font-semibold text-black"
                                        >
                                            {{
                                                roundRating(
                                                    bestShow.average_rating,
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Šípka vpravo -->
                <button
                    @click="scrollRight"
                    class="z-10 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-white/90 shadow-lg transition-all hover:scale-110 hover:bg-white disabled:cursor-not-allowed disabled:opacity-50 sm:h-12 sm:w-12"
                    :disabled="!canScrollRight()"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2.5"
                        stroke="currentColor"
                        class="h-5 w-5 sm:h-6 sm:w-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8.25 4.5l7.5 7.5-7.5 7.5"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, nextTick, onMounted, onUnmounted, ref } from 'vue';
import NavMenu from '../components/NavBar.vue';

const scrollContainer = ref<HTMLElement | null>(null);
const scrollPosition = ref(0);

const props = defineProps({
    newestShows: Array,
    bestShows: Array,
});

const currentIndex = ref(0);
const cardWidth = '250px'; // Pevná šírka kariet pre posúvanie o 1 kartu

let intervalId: number;

onMounted(() => {
    intervalId = setInterval(() => {
        currentIndex.value =
            (currentIndex.value + 1) % props.newestShows?.length;
    }, 5000);

    nextTick(() => updateScrollPosition());
    window.addEventListener('resize', updateScrollPosition);
});

onUnmounted(() => {
    clearInterval(intervalId);
    window.removeEventListener('resize', updateScrollPosition);
});

const currentShow = computed(() => props.newestShows[currentIndex.value]);

function goToSlide(index: number) {
    currentIndex.value = index;
    clearInterval(intervalId);
    intervalId = setInterval(() => {
        currentIndex.value =
            (currentIndex.value + 1) % props.newestShows?.length;
    }, 5000);
}

function updateScrollPosition() {
    if (scrollContainer.value) {
        scrollPosition.value = scrollContainer.value.scrollLeft;
    }
}

function scrollLeft() {
    if (scrollContainer.value) {
        scrollContainer.value.scrollBy({
            left: -parseInt(cardWidth),
            behavior: 'smooth',
        });
        nextTick(() => updateScrollPosition());
    }
}

function scrollRight() {
    if (scrollContainer.value) {
        scrollContainer.value.scrollBy({
            left: parseInt(cardWidth),
            behavior: 'smooth',
        });
        nextTick(() => updateScrollPosition());
    }
}

function canScrollRight() {
    if (!scrollContainer.value) return false;
    return (
        scrollContainer.value.scrollLeft + scrollContainer.value.clientWidth <
        scrollContainer.value.scrollWidth - 1
    );
}

function roundRating(rating?: number | null): string {
    return rating != null ? rating.toFixed(1) : 'N/A';
}

function onCardClick(show: any) {
    // Navigácia na detail filmu
    window.location.href = `/show/${show.id}`;
}
</script>

<style scoped>
/* Skrytie scrollbaru ale ponechanie funkčnosti */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.5);
}

/* Skrytie scrollbaru vo všetkých prehliadačoch */
.hide-scrollbar {
    -ms-overflow-style: none; /* IE a Edge */
    scrollbar-width: none; /* Firefox */
}
.hide-scrollbar::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}
</style>
