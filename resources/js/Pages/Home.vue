<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PropertyCard from "@/Components/PropertyCard.vue";
import {
    MagnifyingGlassIcon,
    HomeIcon,
    MapPinIcon,
    ArrowRightIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    BuildingOfficeIcon,
    CheckCircleIcon,
    UserGroupIcon,
    FunnelIcon,
    AdjustmentsHorizontalIcon,
    SparklesIcon,
    ShieldCheckIcon,
    CurrencyDollarIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    featuredProperties: { type: Array, default: () => [] },
    recentProperties: { type: Array, default: () => [] },
    propertyTypes: { type: Array, default: () => [] },
    locations: { type: Array, default: () => [] },
    stats: { type: Object, default: () => ({}) },
});

const searchForm = ref({
    location: "",
    type: "",
    min_price: "",
    max_price: "",
});

const showAdvancedFilters = ref(false);

// Featured slider
const currentSlide = ref(0);
const slideInterval = ref(null);

// Recent properties slider
const recentSlidePosition = ref(0);
const recentCardsToShow = ref(4);

const nextSlide = () => {
    if (props.featuredProperties.length > 0) {
        currentSlide.value =
            (currentSlide.value + 1) % props.featuredProperties.length;
    }
};

const prevSlide = () => {
    if (props.featuredProperties.length > 0) {
        currentSlide.value =
            currentSlide.value === 0
                ? props.featuredProperties.length - 1
                : currentSlide.value - 1;
    }
};

const slideRecentNext = () => {
    const maxSlide = props.recentProperties.length - recentCardsToShow.value;
    if (recentSlidePosition.value < maxSlide) {
        recentSlidePosition.value++;
    }
};

const slideRecentPrev = () => {
    if (recentSlidePosition.value > 0) {
        recentSlidePosition.value--;
    }
};

const updateCardsToShow = () => {
    if (window.innerWidth >= 1024) {
        recentCardsToShow.value = 4;
    } else if (window.innerWidth >= 768) {
        recentCardsToShow.value = 2;
    } else {
        recentCardsToShow.value = 1;
    }
};

onMounted(() => {
    if (props.featuredProperties.length > 1) {
        slideInterval.value = setInterval(nextSlide, 6000);
    }
    updateCardsToShow();
    window.addEventListener("resize", updateCardsToShow);
});

onUnmounted(() => {
    if (slideInterval.value) clearInterval(slideInterval.value);
    window.removeEventListener("resize", updateCardsToShow);
});

const handleSearch = () => {
    router.get("/properties", searchForm.value);
};

const formatCurrency = (amount) => {
    return "KES " + Number(amount).toLocaleString();
};

const getPropertyImage = (property) => {
    if (
        property.images &&
        Array.isArray(property.images) &&
        property.images.length > 0
    ) {
        return property.images[0];
    }
    return null;
};
</script>

<template>
    <Head title="Find Your Perfect Rental Home in Kenya" />

    <AppLayout>
        <!-- Hero Section - Fresh Gradient -->
        <section
            class="relative bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 overflow-hidden"
        >
            <!-- Decorative Elements -->
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-emerald-200 rounded-full blur-3xl opacity-20"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-96 h-96 bg-teal-200 rounded-full blur-3xl opacity-20"
            ></div>

            <div class="relative max-w-7xl mx-auto px-4 py-20">
                <div class="text-center max-w-4xl mx-auto mb-12">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm mb-6"
                    >
                        <SparklesIcon class="w-4 h-4 text-emerald-600" />
                        <span class="text-sm font-medium text-gray-700"
                            >Trusted by 10,000+ renters</span
                        >
                    </div>
                    <h1
                        class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight"
                    >
                        Discover Your
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600"
                            >Dream Home</span
                        >
                    </h1>
                    <p class="text-xl text-gray-600 leading-relaxed">
                        Explore verified properties across Kenya. Connect
                        directly with owners. Zero broker fees.
                    </p>
                </div>

                <!-- Enhanced Filter Section -->
                <div
                    class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 md:p-8 max-w-6xl mx-auto"
                >
                    <form @submit.prevent="handleSearch" class="space-y-6">
                        <!-- Main Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <!-- Location -->
                            <div class="md:col-span-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    <MapPinIcon
                                        class="w-4 h-4 inline mr-1 text-emerald-600"
                                    />
                                    Location
                                </label>
                                <input
                                    v-model="searchForm.location"
                                    type="text"
                                    placeholder="City or neighborhood"
                                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                />
                            </div>

                            <!-- Property Type -->
                            <div class="md:col-span-3">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    <HomeIcon
                                        class="w-4 h-4 inline mr-1 text-emerald-600"
                                    />
                                    Property Type
                                </label>
                                <select
                                    v-model="searchForm.type"
                                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition appearance-none"
                                >
                                    <option value="">All Types</option>
                                    <option
                                        v-for="type in propertyTypes"
                                        :key="type.type"
                                        :value="type.type"
                                    >
                                        {{ type.type }}
                                    </option>
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="md:col-span-3">
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    <CurrencyDollarIcon
                                        class="w-4 h-4 inline mr-1 text-emerald-600"
                                    />
                                    Max Price
                                </label>
                                <input
                                    v-model="searchForm.max_price"
                                    type="number"
                                    placeholder="Enter amount"
                                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                />
                            </div>

                            <!-- Search Button -->
                            <div class="md:col-span-2 flex items-end">
                                <button
                                    type="submit"
                                    class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold py-3.5 px-6 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/30"
                                >
                                    <MagnifyingGlassIcon class="w-5 h-5" />
                                    <span class="hidden md:inline">Search</span>
                                </button>
                            </div>
                        </div>

                        <!-- Advanced Filters Toggle -->
                        <div class="flex items-center justify-between pt-2">
                            <button
                                type="button"
                                @click="
                                    showAdvancedFilters = !showAdvancedFilters
                                "
                                class="text-sm font-medium text-emerald-600 hover:text-emerald-700 flex items-center gap-2"
                            >
                                <AdjustmentsHorizontalIcon class="w-4 h-4" />
                                {{ showAdvancedFilters ? "Hide" : "Show" }}
                                Advanced Filters
                            </button>
                            <div class="text-sm text-gray-500">
                                {{ stats.total_properties || 0 }} properties
                                available
                            </div>
                        </div>

                        <!-- Advanced Filters Panel -->
                        <div
                            v-if="showAdvancedFilters"
                            class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-100"
                        >
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Min Price</label
                                >
                                <input
                                    v-model="searchForm.min_price"
                                    type="number"
                                    placeholder="From"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Bedrooms</label
                                >
                                <select
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                >
                                    <option value="">Any</option>
                                    <option>1+</option>
                                    <option>2+</option>
                                    <option>3+</option>
                                    <option>4+</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                    >Bathrooms</label
                                >
                                <select
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                                >
                                    <option value="">Any</option>
                                    <option>1+</option>
                                    <option>2+</option>
                                    <option>3+</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Quick Stats -->
                <div
                    class="grid grid-cols-3 gap-6 mt-12 max-w-3xl mx-auto text-center"
                >
                    <div class="bg-white/70 backdrop-blur rounded-2xl p-6">
                        <div
                            class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600"
                        >
                            {{ stats.total_properties || 0 }}+
                        </div>
                        <div class="text-sm text-gray-600 mt-2">Properties</div>
                    </div>
                    <div class="bg-white/70 backdrop-blur rounded-2xl p-6">
                        <div
                            class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600"
                        >
                            {{ stats.total_locations || 0 }}+
                        </div>
                        <div class="text-sm text-gray-600 mt-2">Locations</div>
                    </div>
                    <div class="bg-white/70 backdrop-blur rounded-2xl p-6">
                        <div
                            class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600"
                        >
                            24/7
                        </div>
                        <div class="text-sm text-gray-600 mt-2">Support</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Properties -->
        <section v-if="featuredProperties.length > 0" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h2
                            class="text-3xl md:text-4xl font-bold text-gray-900 mb-2"
                        >
                            Featured Properties
                        </h2>
                        <p class="text-gray-600">
                            Hand-picked premium listings
                        </p>
                    </div>
                    <Link
                        href="/properties"
                        class="hidden md:flex items-center gap-2 px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-xl transition"
                    >
                        View All
                        <ArrowRightIcon class="w-4 h-4" />
                    </Link>
                </div>

                <div class="relative">
                    <div class="overflow-hidden rounded-3xl shadow-2xl">
                        <div
                            v-for="(property, index) in featuredProperties"
                            :key="property.id"
                            v-show="currentSlide === index"
                            class="grid md:grid-cols-5"
                        >
                            <!-- Image - Takes 3 columns -->
                            <div
                                class="md:col-span-3 relative aspect-[16/10] md:aspect-auto md:min-h-[450px]"
                            >
                                <img
                                    v-if="getPropertyImage(property)"
                                    :src="getPropertyImage(property)"
                                    :alt="property.title"
                                    class="w-full h-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-full bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center"
                                >
                                    <HomeIcon
                                        class="w-24 h-24 text-emerald-300"
                                    />
                                </div>
                                <div
                                    class="absolute top-6 left-6 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-full shadow-lg"
                                >
                                    Featured
                                </div>
                            </div>

                            <!-- Content - Takes 2 columns -->
                            <div
                                class="md:col-span-2 bg-gradient-to-br from-gray-50 to-white p-8 md:p-10 flex flex-col justify-center"
                            >
                                <div
                                    class="inline-block px-4 py-1.5 bg-teal-50 text-teal-700 text-sm font-semibold rounded-full mb-4 w-fit"
                                >
                                    {{ property.type }}
                                </div>
                                <h3
                                    class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 leading-tight"
                                >
                                    {{ property.title }}
                                </h3>
                                <div
                                    class="flex items-center text-gray-600 mb-6"
                                >
                                    <MapPinIcon
                                        class="w-5 h-5 mr-2 text-emerald-600"
                                    />
                                    {{ property.city }}, {{ property.county }}
                                </div>
                                <p
                                    class="text-gray-600 mb-8 line-clamp-3 leading-relaxed"
                                >
                                    {{ property.description }}
                                </p>
                                <div class="flex items-end justify-between">
                                    <div>
                                        <div class="text-sm text-gray-500 mb-1">
                                            Starting from
                                        </div>
                                        <div
                                            class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600"
                                        >
                                            {{ formatCurrency(property.price) }}
                                        </div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            per month
                                        </div>
                                    </div>
                                    <Link
                                        :href="`/properties/${property.slug}`"
                                        class="px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-xl transition shadow-lg"
                                    >
                                        View Details
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <button
                        v-if="featuredProperties.length > 1"
                        @click="prevSlide"
                        class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white border border-gray-200 rounded-full flex items-center justify-center shadow-lg transition backdrop-blur"
                    >
                        <ChevronLeftIcon class="w-6 h-6 text-gray-900" />
                    </button>
                    <button
                        v-if="featuredProperties.length > 1"
                        @click="nextSlide"
                        class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white border border-gray-200 rounded-full flex items-center justify-center shadow-lg transition backdrop-blur"
                    >
                        <ChevronRightIcon class="w-6 h-6 text-gray-900" />
                    </button>

                    <!-- Indicators -->
                    <div
                        v-if="featuredProperties.length > 1"
                        class="flex justify-center gap-2 mt-8"
                    >
                        <button
                            v-for="(property, index) in featuredProperties"
                            :key="index"
                            @click="currentSlide = index"
                            :class="[
                                'h-2 rounded-full transition-all',
                                currentSlide === index
                                    ? 'w-10 bg-emerald-600'
                                    : 'w-2 bg-gray-300 hover:bg-gray-400',
                            ]"
                        ></button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Property Types -->
        <section
            v-if="propertyTypes.length > 0"
            class="py-20 bg-gradient-to-b from-gray-50 to-white"
        >
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-12">
                    <h2
                        class="text-3xl md:text-4xl font-bold text-gray-900 mb-3"
                    >
                        Browse by Type
                    </h2>
                    <p class="text-gray-600 text-lg">
                        Find exactly what you're looking for
                    </p>
                </div>

                <div
                    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4"
                >
                    <Link
                        v-for="type in propertyTypes"
                        :key="type.type"
                        :href="`/properties?type=${type.type}`"
                        class="group bg-white hover:bg-gradient-to-br hover:from-emerald-50 hover:to-teal-50 border border-gray-200 hover:border-emerald-300 rounded-2xl p-6 text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
                    >
                        <div
                            class="text-4xl mb-3 group-hover:scale-110 transition-transform"
                        >
                            {{
                                type.type === "Apartment"
                                    ? "🏢"
                                    : type.type === "Villa"
                                      ? "🏡"
                                      : type.type === "Office"
                                        ? "💼"
                                        : type.type === "Commercial"
                                          ? "🏪"
                                          : type.type === "Warehouse"
                                            ? "🏭"
                                            : "🏘️"
                            }}
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">
                            {{ type.type }}
                        </h3>
                        <div class="text-sm text-gray-500">
                            {{ type.count }} listings
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Recent Properties - Sliding Cards -->
        <section v-if="recentProperties.length > 0" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h2
                            class="text-3xl md:text-4xl font-bold text-gray-900 mb-2"
                        >
                            Latest Listings
                        </h2>
                        <p class="text-gray-600">
                            Fresh properties added daily
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            @click="slideRecentPrev"
                            :disabled="recentSlidePosition === 0"
                            :class="[
                                'w-10 h-10 rounded-full border-2 flex items-center justify-center transition',
                                recentSlidePosition === 0
                                    ? 'border-gray-200 text-gray-300 cursor-not-allowed'
                                    : 'border-emerald-600 text-emerald-600 hover:bg-emerald-50',
                            ]"
                        >
                            <ChevronLeftIcon class="w-5 h-5" />
                        </button>
                        <button
                            @click="slideRecentNext"
                            :disabled="
                                recentSlidePosition >=
                                recentProperties.length - recentCardsToShow
                            "
                            :class="[
                                'w-10 h-10 rounded-full border-2 flex items-center justify-center transition',
                                recentSlidePosition >=
                                recentProperties.length - recentCardsToShow
                                    ? 'border-gray-200 text-gray-300 cursor-not-allowed'
                                    : 'border-emerald-600 text-emerald-600 hover:bg-emerald-50',
                            ]"
                        >
                            <ChevronRightIcon class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <div class="overflow-hidden">
                    <div
                        class="flex transition-transform duration-500 ease-out gap-6"
                        :style="{
                            transform: `translateX(-${recentSlidePosition * (100 / recentCardsToShow + 1.5)}%)`,
                        }"
                    >
                        <div
                            v-for="property in recentProperties"
                            :key="property.id"
                            :class="[
                                'flex-shrink-0',
                                recentCardsToShow === 4
                                    ? 'w-[calc(25%-18px)]'
                                    : recentCardsToShow === 2
                                      ? 'w-[calc(50%-12px)]'
                                      : 'w-full',
                            ]"
                        >
                            <PropertyCard :property="property" />
                        </div>
                    </div>
                </div>

                <div class="text-center mt-10">
                    <Link
                        href="/properties"
                        class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-xl transition shadow-lg shadow-emerald-500/30"
                    >
                        View All Properties
                        <ArrowRightIcon class="w-5 h-5" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- Popular Locations -->
        <section
            v-if="locations.length > 0"
            class="py-20 bg-gradient-to-b from-gray-50 to-white"
        >
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-12">
                    <h2
                        class="text-3xl md:text-4xl font-bold text-gray-900 mb-3"
                    >
                        Popular Locations
                    </h2>
                    <p class="text-gray-600 text-lg">
                        Discover properties in trending areas
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <Link
                        v-for="location in locations.slice(0, 8)"
                        :key="location.id"
                        :href="`/properties?location=${location.name}`"
                        class="group bg-white hover:bg-gradient-to-br hover:from-emerald-50 hover:to-teal-50 border border-gray-200 hover:border-emerald-300 rounded-2xl p-6 text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
                    >
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-teal-100 group-hover:from-emerald-200 group-hover:to-teal-200 rounded-full flex items-center justify-center mx-auto mb-4 transition-all group-hover:scale-110"
                        >
                            <MapPinIcon class="w-7 h-7 text-emerald-600" />
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">
                            {{ location.name }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ location.property_count }} properties
                        </p>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-12">
                    <h2
                        class="text-3xl md:text-4xl font-bold text-gray-900 mb-3"
                    >
                        Why Thousands Choose Us
                    </h2>
                    <p class="text-gray-600 text-lg">
                        Simplifying property rental across Kenya
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 rounded-2xl p-8 text-center hover:shadow-lg transition-all duration-300"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-emerald-500/30"
                        >
                            <ShieldCheckIcon class="w-8 h-8 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Verified Listings
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Every property verified for authenticity and
                            accuracy
                        </p>
                    </div>

                    <div
                        class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 rounded-2xl p-8 text-center hover:shadow-lg transition-all duration-300"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-emerald-500/30"
                        >
                            <UserGroupIcon class="w-8 h-8 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Direct Contact
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Connect directly with property owners, no middlemen
                        </p>
                    </div>

                    <div
                        class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 rounded-2xl p-8 text-center hover:shadow-lg transition-all duration-300"
                    >
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-emerald-500/30"
                        >
                            <CheckCircleIcon class="w-8 h-8 text-white" />
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Zero Hidden Fees
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            Transparent pricing with no broker or hidden charges
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section
            class="py-20 bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 relative overflow-hidden"
        >
            <!-- Decorative elements -->
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"
            ></div>

            <div class="relative max-w-5xl mx-auto px-4 text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Ready to Find Your Next Home?
                </h2>
                <p class="text-xl text-emerald-50 mb-10 leading-relaxed">
                    Join thousands of happy tenants and landlords using our
                    platform
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link
                        href="/properties"
                        class="px-8 py-4 bg-white hover:bg-gray-50 text-emerald-600 font-bold rounded-xl transition shadow-xl hover:shadow-2xl hover:-translate-y-1 duration-300"
                    >
                        Explore Properties
                    </Link>
                    <Link
                        href="/register"
                        class="px-8 py-4 bg-transparent hover:bg-white/10 text-white font-bold border-2 border-white rounded-xl transition hover:shadow-xl duration-300"
                    >
                        List Your Property
                    </Link>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
