<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

interface CartItemRow {
    id: number;
    quantity: number;
    line_total: number;
    added_at: string | null;
    product: {
        id: number | null;
        name: string | null;
        price: number | null;
        image_url?: string | null;
    };
}

interface CartRow {
    id: number;
    items_count: number;
    total: number;
    items: CartItemRow[];
    updated_at: string | null;
    user: {
        id: number | null;
        name: string | null;
        email: string | null;
    };
}

const props = defineProps<{
    carts: CartRow[];
    total: number;
}>();

const expandedCartId = ref<number | null>(null);

const toggleCart = (cartId: number) => {
    expandedCartId.value = expandedCartId.value === cartId ? null : cartId;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Carts',
        href: '/admin/carts',
    },
];

const formatPrice = (value: number | string) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(Number(value ?? 0));
</script>

<template>
    <Head title="Manage Carts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-foreground">
                        Cart activity
                    </h1>
                    <p class="text-sm text-muted-foreground">
                        Track active cart items across customers.
                    </p>
                </div>
                <div class="text-sm font-medium text-foreground">
                    Total value: {{ formatPrice(props.total) }}
                </div>
            </div>

            <div
                class="overflow-hidden rounded-lg border border-border bg-background"
            >
                <div v-if="!props.carts.length" class="p-8 text-sm">
                    No cart activity yet.
                </div>
                <table v-else class="min-w-full divide-y divide-border text-sm">
                    <thead class="bg-muted/50 text-left text-xs uppercase">
                        <tr>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Cart
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Items
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Cart total
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Updated
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <template v-for="cart in props.carts" :key="cart.id">
                            <tr class="hover:bg-muted/30">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-foreground">
                                        {{ cart.user.name || 'Unknown' }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        {{ cart.user.email || '—' }}
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-3 font-medium text-foreground"
                                >
                                    {{ cart.items_count }}
                                </td>
                                <td
                                    class="px-4 py-3 font-medium text-foreground"
                                >
                                    {{ formatPrice(cart.total) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-muted-foreground">
                                        {{ cart.updated_at || '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <button
                                        type="button"
                                        class="text-primary hover:underline"
                                        @click="toggleCart(cart.id)"
                                    >
                                        {{
                                            expandedCartId === cart.id
                                                ? 'Hide items'
                                                : 'View items'
                                        }}
                                    </button>
                                </td>
                            </tr>
                            <tr
                                v-if="expandedCartId === cart.id"
                                class="bg-muted/20"
                            >
                                <td colspan="5" class="px-4 py-4">
                                    <div
                                        class="rounded-md border border-border bg-background"
                                    >
                                        <table
                                            class="min-w-full divide-y divide-border text-sm"
                                        >
                                            <thead
                                                class="bg-muted/40 text-left text-xs uppercase"
                                            >
                                                <tr>
                                                    <th
                                                        class="px-3 py-2 font-medium text-muted-foreground"
                                                    >
                                                        Product
                                                    </th>
                                                    <th
                                                        class="px-3 py-2 font-medium text-muted-foreground"
                                                    >
                                                        Quantity
                                                    </th>
                                                    <th
                                                        class="px-3 py-2 font-medium text-muted-foreground"
                                                    >
                                                        Line total
                                                    </th>
                                                    <th
                                                        class="px-3 py-2 font-medium text-muted-foreground"
                                                    >
                                                        Added
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="divide-y divide-border"
                                            >
                                                <tr
                                                    v-for="item in cart.items"
                                                    :key="item.id"
                                                >
                                                    <td class="px-3 py-2">
                                                        <div
                                                            class="flex items-center gap-3"
                                                        >
                                                            <img
                                                                :src="
                                                                    item.product
                                                                        .image_url ||
                                                                    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-01.jpg'
                                                                "
                                                                :alt="
                                                                    item.product
                                                                        .name ||
                                                                    'Product'
                                                                "
                                                                class="size-9 rounded-md object-cover"
                                                            />
                                                            <div>
                                                                <div
                                                                    class="font-medium text-foreground"
                                                                >
                                                                    {{
                                                                        item
                                                                            .product
                                                                            .name ||
                                                                        'Unknown'
                                                                    }}
                                                                </div>
                                                                <div
                                                                    class="text-xs text-muted-foreground"
                                                                >
                                                                    <Link
                                                                        v-if="
                                                                            item
                                                                                .product
                                                                                .id
                                                                        "
                                                                        class="hover:underline"
                                                                        :href="`/products/${item.product.id}`"
                                                                    >
                                                                        View
                                                                        product
                                                                    </Link>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="px-3 py-2 font-medium text-foreground"
                                                    >
                                                        {{ item.quantity }}
                                                    </td>
                                                    <td
                                                        class="px-3 py-2 font-medium text-foreground"
                                                    >
                                                        {{
                                                            formatPrice(
                                                                item.line_total,
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="px-3 py-2 text-muted-foreground"
                                                    >
                                                        {{
                                                            item.added_at || '—'
                                                        }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
