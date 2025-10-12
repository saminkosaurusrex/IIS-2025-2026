<template>
    <div class="app-layout flex min-h-screen flex-col bg-gray-50">
        <NavMenu />
        <div class="flex-1">
            <!-- Filter Section -->
            <div class="bg-gray-50 px-6 py-6">
                <div class="mx-auto max-w-7xl">
                    <div class="mb-4 flex flex-wrap gap-4">
                        <!-- Search -->
                        <div class="relative min-w-[300px] flex-1">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search..."
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 pl-10 text-gray-900 focus:border-blue-500 focus:outline-none"
                            />
                            <svg
                                class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </div>

                        <!-- Location Filter -->
                        <select
                            v-model="selectedLocation"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 focus:border-blue-500 focus:outline-none"
                        >
                            <option value="">All Locations</option>
                            <option
                                v-for="location in uniqueLocations"
                                :key="location"
                                :value="location"
                            >
                                {{ location }}
                            </option>
                        </select>
                    </div>

                    <!-- Tags -->
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-700"
                            >Tags:</span
                        >
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="tag in getTags"
                                :key="tag"
                                @click="toggleTag(tag)"
                                :class="[
                                    'rounded-full px-4 py-1.5 text-sm font-medium transition-colors',
                                    selectedTags.includes(tag)
                                        ? 'bg-gray-900 text-white'
                                        : 'bg-white text-gray-700 hover:bg-gray-100',
                                ]"
                            >
                                {{ tag }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shows Grid -->
            <div class="px-6 py-8">
                <div class="mx-auto max-w-7xl">
                    <div
                        class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                    >
                        <div
                            v-for="show in filteredShows"
                            :key="show.id"
                            class="group cursor-pointer overflow-hidden rounded-lg bg-white shadow transition-shadow hover:shadow-lg"
                            @click="goToShow(show.id)"
                        >
                            <!-- Image with Category Badge -->
                            <div class="relative aspect-[2/3] overflow-hidden">
                                <img
                                    v-if="show.image"
                                    :src="show.image"
                                    :alt="show.name"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                <div
                                    v-if="show.show_type"
                                    class="text-var[--color-primary] absolute top-3 right-3 rounded-full bg-amber-400 px-3 py-1 text-xs font-medium"
                                >
                                    {{ roundRating(show.average_rating) }}
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-4">
                                <h3
                                    class="mb-2 line-clamp-1 text-lg font-semibold text-gray-900"
                                >
                                    {{ show.name }}
                                </h3>

                                <!-- Actors/Cast -->
                                <p
                                    v-if="
                                        show.performers &&
                                        show.actors.length > 0
                                    "
                                    class="mb-3 line-clamp-2 text-sm text-gray-600"
                                >
                                    {{ show.performers.join(', ') }}
                                </p>
                                <p v-else class="mb-3 text-sm text-gray-500">
                                    Cast information not available
                                </p>

                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-lg font-bold text-gray-900"
                                    >
                                        {{ getMinPrice(show) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Results -->
                    <div
                        v-if="filteredShows.length === 0"
                        class="py-16 text-center"
                    >
                        <p class="text-lg text-gray-500">
                            Neboli nájdené žiedne eventy!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import NavMenu from '../components/NavBar.vue';

const props = defineProps({
    shows: Array,
});

const searchQuery = ref('');
const selectedLocation = ref('');
const selectedCategory = ref('');
const selectedTags = ref<string[]>([]);

// Get unique locations from events
const uniqueLocations = computed(() => {
    const locations = new Set<string>();
    props.shows?.forEach((show: any) => {
        show.events?.forEach((event: any) => {
            if (event.hall?.name) {
                locations.add(event.hall.name);
            }
        });
    });
    return Array.from(locations).sort();
});

// Get unique show types
const uniqueTypes = computed(() => {
    const types = new Set<string>();
    props.shows?.forEach((show: any) => {
        if (show.show_type?.name) {
            types.add(show.show_type.name);
        }
    });
    return Array.from(types).sort();
});

// Toggle tag selection
const toggleTag = (tag: string) => {
    const index = selectedTags.value.indexOf(tag);
    if (index > -1) {
        selectedTags.value.splice(index, 1);
    } else {
        selectedTags.value.push(tag);
    }
};

const getTags = computed(() => {
    const tags = new Set<string>();
    props.shows?.forEach((show: any) => {
        if (show.tags && Array.isArray(show.tags)) {
            show.tags.forEach((tag: any) => {
                tags.add(tag.name);
            });
        }
    });
    return Array.from(tags).sort();
});

// Filter shows based on all criteria
const filteredShows = computed(() => {
    if (!props.shows) return [];

    return props.shows.filter((show: any) => {
        // Search filter
        if (
            searchQuery.value &&
            !show.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        ) {
            return false;
        }

        // Location filter
        if (selectedLocation.value) {
            const hasLocation = show.events?.some(
                (event: any) => event.hall?.name === selectedLocation.value,
            );
            if (!hasLocation) return false;
        }
        // tags filter
        if (selectedTags.value.length > 0) {
            const showTagNames = show.tags?.map((t: any) => t.name) || [];
            const hasMatchingTag = selectedTags.value.some((selectedTag) =>
                showTagNames.includes(selectedTag),
            );
            if (!hasMatchingTag) return false;
        }

        return true;
    });
});

// Get minimum price from events
const getMinPrice = (show: any) => {
    if (!show.events || show.events.length === 0) return 'N/A';

    const prices = show.events.map((event: any) => Number(event.price));
    const minPrice = Math.min(...prices);

    return `$${minPrice.toFixed(2)}`;
};

// Navigate to show detail
const goToShow = (showId: number) => {
    window.location.href = `/show/${showId}`;
};

function roundRating(rating?: number | null): string {
    return rating != null ? rating.toFixed(1) : 'N/A';
}
</script>

<style src="../../css/app.css"></style>
