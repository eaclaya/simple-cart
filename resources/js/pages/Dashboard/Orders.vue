<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

interface OrderItemRow {
    id: number;
    quantity: number;
    price: number;
    line_total: number;
    product: {
        id: number | null;
        name: string | null;
        image_url?: string | null;
    };
}

interface OrderRow {
    id: number;
    items_count: number;
    total: number;
    created_at: string | null;
    items: OrderItemRow[];
    user: {
        id: number | null;
        name: string | null;
        email: string | null;
    };
}

const props = defineProps<{
    orders: OrderRow[];
    total: number;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Orders',
        href: '/admin/orders',
    },
];

const expandedOrderId = ref<number | null>(null);

const toggleOrder = (orderId: number) => {
    expandedOrderId.value = expandedOrderId.value === orderId ? null : orderId;
};

const formatPrice = (value: number | string) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(Number(value ?? 0));
</script>

<template>
    <Head title="Orders" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-foreground">
                        Orders
                    </h1>
                    <p class="text-sm text-muted-foreground">
                        Review recent orders and their line items.
                    </p>
                </div>
                <div class="text-sm font-medium text-foreground">
                    Total revenue: {{ formatPrice(props.total) }}
                </div>
            </div>

            <div
                class="overflow-hidden rounded-lg border border-border bg-background"
            >
                <div v-if="!props.orders.length" class="p-8 text-sm">
                    No orders yet.
                </div>
                <table v-else class="min-w-full divide-y divide-border text-sm">
                    <thead class="bg-muted/50 text-left text-xs uppercase">
                        <tr>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Order
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Customer
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Items
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Total
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Placed
                            </th>
                            <th
                                class="px-4 py-3 font-medium text-muted-foreground"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <template v-for="order in props.orders" :key="order.id">
                            <tr class="hover:bg-muted/30">
                                <td
                                    class="px-4 py-3 font-medium text-foreground"
                                >
                                    #{{ order.id }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-foreground">
                                        {{ order.user.name || 'Unknown' }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        {{ order.user.email || '—' }}
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-3 font-medium text-foreground"
                                >
                                    {{ order.items_count }}
                                </td>
                                <td
                                    class="px-4 py-3 font-medium text-foreground"
                                >
                                    {{ formatPrice(order.total) }}
                                </td>
                                <td class="px-4 py-3 text-muted-foreground">
                                    {{ order.created_at || '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <button
                                        type="button"
                                        class="text-primary hover:underline"
                                        @click="toggleOrder(order.id)"
                                    >
                                        {{
                                            expandedOrderId === order.id
                                                ? 'Hide items'
                                                : 'View items'
                                        }}
                                    </button>
                                </td>
                            </tr>
                            <tr
                                v-if="expandedOrderId === order.id"
                                class="bg-muted/20"
                            >
                                <td colspan="6" class="px-4 py-4">
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
                                                        Price
                                                    </th>
                                                    <th
                                                        class="px-3 py-2 font-medium text-muted-foreground"
                                                    >
                                                        Line total
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="divide-y divide-border"
                                            >
                                                <tr
                                                    v-for="item in order.items"
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
                                                            <div
                                                                class="font-medium text-foreground"
                                                            >
                                                                {{
                                                                    item.product
                                                                        .name ||
                                                                    'Unknown'
                                                                }}
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
                                                                item.price,
                                                            )
                                                        }}
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
