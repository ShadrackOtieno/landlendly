<script setup>
import { Link } from "@inertiajs/vue3";
import { MapPinIcon, HomeIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    property: Object,
});

const getPrimaryImage = () => {
    const primary = props.property.images?.find((img) => img.is_primary);
    return primary?.image_path || props.property.images?.[0]?.image_path;
};

const formatCurrency = (amount) => {
    return "KES " + Number(amount).toLocaleString();
};
</script>

<template>
    <Link
        :href="`/properties/${property.slug}`"
        class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300"
    >
        <div class="relative aspect-video bg-gray-200">
            <img
                v-if="getPrimaryImage()"
                :src="`/storage/${getPrimaryImage()}`"
                :alt="property.title"
                class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
            />
            <div v-else class="w-full h-full flex items-center justify-center">
                <HomeIcon class="w-16 h-16 text-gray-400" />
            </div>

            <div class="absolute top-3 left-3">
                <span
                    class="px-3 py-1 bg-blue-600 text-white text-sm font-semibold rounded-full"
                >
                    {{ property.type }}
                </span>
            </div>

            <div v-if="property.featured" class="absolute top-3 right-3">
                <span
                    class="px-3 py-1 bg-yellow-500 text-white text-sm font-semibold rounded-full"
                >
                    Featured
                </span>
            </div>

            <div v-if="property.verified" class="absolute bottom-3 right-3">
                <span
                    class="px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-full"
                >
                    ✓ Verified
                </span>
            </div>
        </div>

        <div class="p-5">
            <h3
                class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-blue-600 transition line-clamp-1"
            >
                {{ property.title }}
            </h3>

            <div class="flex items-center text-gray-600 mb-3">
                <MapPinIcon class="w-4 h-4 mr-1" />
                <span class="text-sm"
                    >{{ property.city }}, {{ property.county }}</span
                >
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-blue-600">
                        {{ formatCurrency(property.price) }}
                    </div>
                    <div class="text-xs text-gray-500">
                        per {{ property.price_period }}
                    </div>
                </div>

                <div class="flex gap-3 text-sm text-gray-600">
                    <span v-if="property.bedrooms"
                        >{{ property.bedrooms }} beds</span
                    >
                    <span v-if="property.bathrooms"
                        >{{ property.bathrooms }} baths</span
                    >
                </div>
            </div>
        </div>
    </Link>
</template>
