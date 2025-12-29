<template>
    <div class="min-h-screen bg-white">
        <header class="relative bg-white">
            <nav
                aria-label="Top"
                class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"
            >
                <div class="border-b border-gray-200">
                    <div class="flex h-16 items-center">
                        <button
                            type="button"
                            class="relative rounded-md bg-white p-2 text-gray-400 lg:hidden"
                            @click="open = true"
                        >
                            <span class="absolute -inset-0.5"></span>
                            <span class="sr-only">Open menu</span>
                            <Bars3Icon class="size-6" aria-hidden="true" />
                        </button>

                        <!-- Logo -->
                        <div class="ml-4 flex lg:ml-0">
                            <a href="/">
                                <span class="sr-only">Your Company</span>
                                <img
                                    class="h-8 w-auto"
                                    src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600"
                                    alt=""
                                />
                            </a>
                        </div>

                        <div class="ml-auto flex items-center">
                            <div
                                class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6"
                            >
                                <template v-if="isAuthenticated">
                                    <div class="flow-root">
                                        <Link
                                            :href="logout()"
                                            class="-m-2 block p-2 font-medium text-gray-900"
                                            >Logout</Link
                                        >
                                    </div>
                                </template>
                                <template v-else>
                                    <Link
                                        :href="login()"
                                        class="text-sm font-medium text-gray-700 hover:text-gray-800"
                                        >Sign in</Link
                                    >
                                </template>
                            </div>

                            <!-- Cart -->
                            <div class="ml-4 flow-root lg:ml-6">
                                <Link
                                    :href="cart.index()"
                                    class="group -m-2 flex items-center p-2"
                                >
                                    <ShoppingBagIcon
                                        class="size-6 shrink-0 text-gray-400 group-hover:text-gray-500"
                                        aria-hidden="true"
                                    />
                                    <span
                                        class="ml-2 text-sm font-medium text-gray-700 group-hover:text-gray-800"
                                        >{{ cartCount }}</span
                                    >
                                    <span class="sr-only"
                                        >items in cart, view bag</span
                                    >
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <main
            class="mx-auto max-w-2xl px-4 pt-16 pb-24 sm:px-6 lg:max-w-7xl lg:px-8"
        >
            <h1
                class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
            >
                Shopping Cart
            </h1>

            <form
                class="mt-12 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16"
                @submit.prevent="checkoutCart"
            >
                <section aria-labelledby="cart-heading" class="lg:col-span-7">
                    <h2 id="cart-heading" class="sr-only">
                        Items in your shopping cart
                    </h2>

                    <div
                        v-if="!props.items.length"
                        class="rounded-lg border border-dashed border-gray-200 p-8 text-center text-sm text-gray-500"
                    >
                        Your cart is empty. Browse products to add items to your
                        cart.
                    </div>

                    <ul
                        v-else
                        role="list"
                        class="divide-y divide-gray-200 border-t border-b border-gray-200"
                    >
                        <li
                            v-for="(item, itemIdx) in props.items"
                            :key="item.id"
                            class="flex py-6 sm:py-10"
                        >
                            <div class="shrink-0">
                                <img
                                    :src="item.product.image_url"
                                    :alt="item.product.name"
                                    class="size-24 rounded-md object-cover sm:size-48"
                                />
                            </div>

                            <div
                                class="ml-4 flex flex-1 flex-col justify-between sm:ml-6"
                            >
                                <div
                                    class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0"
                                >
                                    <div>
                                        <div class="flex justify-between">
                                            <h3 class="text-sm">
                                                <Link
                                                    :href="`/products/${item.product.id}`"
                                                    class="font-medium text-gray-700 hover:text-gray-800"
                                                    >{{
                                                        item.product.name
                                                    }}</Link
                                                >
                                            </h3>
                                        </div>
                                        <div class="mt-1 flex text-sm">
                                            <p class="text-gray-500">
                                                Qty {{ item.quantity }}
                                            </p>
                                            <p
                                                class="ml-4 border-l border-gray-200 pl-4 text-gray-500"
                                            >
                                                Stock
                                                {{
                                                    item.product.stock_quantity
                                                }}
                                            </p>
                                        </div>
                                        <p
                                            class="mt-1 text-sm font-medium text-gray-900"
                                        >
                                            {{
                                                formatPrice(item.product.price)
                                            }}
                                        </p>
                                    </div>

                                    <div class="mt-4 sm:mt-0 sm:pr-9">
                                        <div
                                            class="inline-grid w-full max-w-16 grid-cols-1"
                                        >
                                            <select
                                                :id="`quantity-${itemIdx}`"
                                                :name="`quantity-${itemIdx}`"
                                                :value="item.quantity"
                                                :aria-label="`Quantity, ${item.product.name}`"
                                                class="col-start-1 row-start-1 appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                                @change="
                                                    updateQuantity(
                                                        item.id,
                                                        Number(
                                                            $event.target.value,
                                                        ),
                                                    )
                                                "
                                            >
                                                <option
                                                    v-for="option in quantityOptions(
                                                        item,
                                                    )"
                                                    :key="option"
                                                    :value="option"
                                                >
                                                    {{ option }}
                                                </option>
                                            </select>
                                            <ChevronDownIcon
                                                class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                                                aria-hidden="true"
                                            />
                                        </div>

                                        <div class="absolute top-0 right-0">
                                            <button
                                                type="button"
                                                class="-m-2 inline-flex p-2 text-gray-400 hover:text-gray-500"
                                                @click="removeItem(item.id)"
                                            >
                                                <span class="sr-only"
                                                    >Remove</span
                                                >
                                                <XMarkIconMini
                                                    class="size-5"
                                                    aria-hidden="true"
                                                />
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <p
                                    class="mt-4 flex space-x-2 text-sm text-gray-700"
                                >
                                    <CheckIcon
                                        v-if="item.product.stock_quantity > 0"
                                        class="size-5 shrink-0 text-green-500"
                                        aria-hidden="true"
                                    />
                                    <ClockIcon
                                        v-else
                                        class="size-5 shrink-0 text-gray-300"
                                        aria-hidden="true"
                                    />
                                    <span>{{
                                        item.product.stock_quantity > 0
                                            ? 'In stock'
                                            : 'Out of stock'
                                    }}</span>
                                </p>
                            </div>
                        </li>
                    </ul>
                </section>

                <!-- Order summary -->
                <section
                    aria-labelledby="summary-heading"
                    class="mt-16 rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:col-span-5 lg:mt-0 lg:p-8"
                >
                    <h2
                        id="summary-heading"
                        class="text-lg font-medium text-gray-900"
                    >
                        Order summary
                    </h2>

                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ formatPrice(props.subtotal) }}
                            </dd>
                        </div>
                        <div
                            class="flex items-center justify-between border-t border-gray-200 pt-4"
                        >
                            <dt class="flex items-center text-sm text-gray-600">
                                <span>Shipping estimate</span>
                                <a
                                    href="#"
                                    class="ml-2 shrink-0 text-gray-400 hover:text-gray-500"
                                >
                                    <span class="sr-only"
                                        >Learn more about how shipping is
                                        calculated</span
                                    >
                                    <QuestionMarkCircleIcon
                                        class="size-5"
                                        aria-hidden="true"
                                    />
                                </a>
                            </dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ formatPrice(0) }}
                            </dd>
                        </div>
                        <div
                            class="flex items-center justify-between border-t border-gray-200 pt-4"
                        >
                            <dt class="flex text-sm text-gray-600">
                                <span>Tax estimate</span>
                                <a
                                    href="#"
                                    class="ml-2 shrink-0 text-gray-400 hover:text-gray-500"
                                >
                                    <span class="sr-only"
                                        >Learn more about how tax is
                                        calculated</span
                                    >
                                    <QuestionMarkCircleIcon
                                        class="size-5"
                                        aria-hidden="true"
                                    />
                                </a>
                            </dt>
                            <dd class="text-sm font-medium text-gray-900">
                                {{ formatPrice(0) }}
                            </dd>
                        </div>
                        <div
                            class="flex items-center justify-between border-t border-gray-200 pt-4"
                        >
                            <dt class="text-base font-medium text-gray-900">
                                Order total
                            </dt>
                            <dd class="text-base font-medium text-gray-900">
                                {{ formatPrice(props.subtotal) }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-6">
                        <button
                            type="submit"
                            :disabled="!props.items.length"
                            class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-400"
                        >
                            Checkout
                        </button>
                    </div>
                </section>
            </form>
        </main>
    </div>
</template>

<script setup>
import { login, logout } from '@/routes';
import cart from '@/routes/cart';
import checkout from '@/routes/checkout';
import { ChevronDownIcon } from '@heroicons/vue/16/solid';
import {
    CheckIcon,
    ClockIcon,
    QuestionMarkCircleIcon,
    XMarkIcon as XMarkIconMini,
} from '@heroicons/vue/20/solid';
import { Bars3Icon, ShoppingBagIcon } from '@heroicons/vue/24/outline';
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    subtotal: {
        type: Number,
        default: 0,
    },
});

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);
const cartCount = computed(() =>
    props.items.reduce((sum, item) => sum + Number(item.quantity || 0), 0),
);

const formatPrice = (value) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(Number(value ?? 0));
const quantityOptions = (item) => {
    const max = Math.max(
        1,
        Math.min(10, Number(item.product.stock_quantity || 1)),
    );
    return Array.from({ length: max }, (_, i) => i + 1);
};
const updateQuantity = (itemId, quantity) => {
    router.put(
        cart.update({ cartItem: itemId }).url,
        {
            quantity,
        },
        {
            preserveScroll: true,
            onError: (errors) => {
                const errorMessage =
                    errors.quantity || 'Failed to update quantity';
                toast.error(errorMessage);
            },
        },
    );
};
const removeItem = (itemId) => {
    router.delete(cart.destroy({ cartItem: itemId }).url, {
        preserveScroll: true,
        onError: () => {
            toast.error('Failed to remove item');
        },
    });
};
const checkoutCart = () => {
    if (!isAuthenticated.value) {
        router.visit(login().url);
        return;
    }

    router.post(checkout.store().url, {}, { preserveScroll: true });
};

const open = ref(false);
</script>
