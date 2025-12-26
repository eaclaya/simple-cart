<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface ProductRow {
    id: number;
    name: string;
    price: number;
    stock_quantity: number;
    image_url?: string | null;
}

const props = defineProps<{
    products: ProductRow[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Products',
        href: '/dashboard/products',
    },
];

const formatPrice = (value: number | string) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(Number(value ?? 0));
</script>

<template>
    <Head title="Manage Products" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <div>
                <h1 class="text-2xl font-semibold text-foreground">
                    Product catalog
                </h1>
                <p class="text-sm text-muted-foreground">
                    Review pricing and inventory levels for your store.
                </p>
            </div>

            <div
                class="overflow-hidden rounded-lg border border-border bg-background"
            >
                <div v-if="!props.products.length" class="p-8 text-sm">
                    No products found. Seed the database to get started.
                </div>
                <table v-else class="min-w-full divide-y divide-border text-sm">
                    <thead class="bg-muted/50 text-left text-xs uppercase">
                        <tr>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Product
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Price
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Stock
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                View
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <tr
                            v-for="product in props.products"
                            :key="product.id"
                            class="hover:bg-muted/30"
                        >
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img
                                        :src="
                                            product.image_url ||
                                            'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-01.jpg'
                                        "
                                        :alt="product.name"
                                        class="size-10 rounded-md object-cover"
                                    />
                                    <div>
                                        <div
                                            class="font-medium text-foreground"
                                        >
                                            {{ product.name }}
                                        </div>
                                        <div
                                            class="text-xs text-muted-foreground"
                                        >
                                            ID {{ product.id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-foreground">
                                {{ formatPrice(product.price) }}
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    :class="
                                        product.stock_quantity > 0
                                            ? 'text-emerald-600'
                                            : 'text-rose-600'
                                    "
                                    class="font-medium"
                                >
                                    {{ product.stock_quantity }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <Link
                                    class="text-primary hover:underline"
                                    :href="`/products/${product.id}`"
                                >
                                    View
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
