<template>
    <div class="bg-gray-50">
        <div>
            <header class="relative">
                <nav aria-label="Top">
                    <div class="bg-white shadow-sm">
                        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div class="flex h-16 items-center justify-between">
                                <div
                                    class="flex flex-1 items-center justify-end"
                                >
                                    <div class="flex items-center lg:ml-8">
                                        <div
                                            class="lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6"
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
                                        <div class="ml-4 flow-root lg:ml-8">
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
                                                    >items in cart, view
                                                    bag</span
                                                >
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
        </div>

        <div>
            <!-- Mobile filter dialog -->
            <TransitionRoot as="template" :show="mobileFiltersOpen">
                <Dialog
                    class="relative z-40 sm:hidden"
                    @close="mobileFiltersOpen = false"
                >
                    <TransitionChild
                        as="template"
                        enter="transition-opacity ease-linear duration-300"
                        enter-from="opacity-0"
                        enter-to=""
                        leave="transition-opacity ease-linear duration-300"
                        leave-from=""
                        leave-to="opacity-0"
                    >
                        <div class="fixed inset-0 bg-black/25"></div>
                    </TransitionChild>

                    <div class="fixed inset-0 z-40 flex">
                        <TransitionChild
                            as="template"
                            enter="transition ease-in-out duration-300 transform"
                            enter-from="translate-x-full"
                            enter-to="translate-x-0"
                            leave="transition ease-in-out duration-300 transform"
                            leave-from="translate-x-0"
                            leave-to="translate-x-full"
                        >
                            <DialogPanel
                                class="relative ml-auto flex size-full max-w-xs flex-col overflow-y-auto bg-white pt-4 pb-6 shadow-xl"
                            >
                                <div
                                    class="flex items-center justify-between px-4"
                                >
                                    <h2
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Filters
                                    </h2>
                                    <button
                                        type="button"
                                        class="relative -mr-2 flex size-10 items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                        @click="mobileFiltersOpen = false"
                                    >
                                        <span
                                            class="absolute -inset-0.5"
                                        ></span>
                                        <span class="sr-only">Close menu</span>
                                        <XMarkIcon
                                            class="size-6"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </Dialog>
            </TransitionRoot>

            <main>
                <div
                    class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8"
                >
                    <!-- Product grid -->
                    <section aria-labelledby="products-heading" class="py-8">
                        <h2 id="products-heading" class="sr-only">Products</h2>

                        <div
                            class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8"
                        >
                            <div
                                v-for="product in props.products"
                                :key="product.id"
                                class="group"
                            >
                                <div class="block">
                                    <img
                                        :src="product.image_url"
                                        :alt="product.name"
                                        class="aspect-square w-full rounded-lg object-cover group-hover:opacity-75 sm:aspect-[2/3]"
                                    />
                                    <div
                                        class="mt-4 flex items-center justify-between text-base font-medium text-gray-900"
                                    >
                                        <h3>{{ product.name }}</h3>
                                        <p>{{ formatPrice(product.price) }}</p>
                                    </div>
                                </div>
                                <div
                                    class="mt-2 flex items-center justify-between text-sm text-gray-500"
                                >
                                    <span>{{
                                        product.stock_quantity > 0
                                            ? 'In stock'
                                            : 'Out of stock'
                                    }}</span>
                                    <button
                                        type="button"
                                        class="cursor-pointer font-medium text-indigo-600 hover:text-indigo-500 disabled:cursor-not-allowed disabled:text-gray-400"
                                        :disabled="product.stock_quantity <= 0"
                                        @click="addToCart(product.id)"
                                    >
                                        <ShoppingCart />
                                    </button>
                                </div>
                                <p
                                    v-if="inCartQuantity(product.id)"
                                    class="mt-1 text-xs text-gray-500"
                                >
                                    In cart: {{ inCartQuantity(product.id) }}
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>

        <AuthPromptModal
            :open="authModalOpen"
            :login-url="loginUrl"
            :register-url="registerUrl"
            @update:open="authModalOpen = $event"
        />
    </div>
</template>

<script setup>
import AuthPromptModal from '@/components/AuthPromptModal.vue';
import { login, logout, register } from '@/routes';
import cart from '@/routes/cart';
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue';
import { ShoppingBagIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps({
    products: {
        type: Array,
        default: () => [],
    },
    cartItemQuantities: {
        type: Object,
        default: () => ({}),
    },
});

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);
const authModalOpen = ref(false);
const pendingProductId = ref(null);
const cartCount = computed(() =>
    Object.values(props.cartItemQuantities).reduce(
        (sum, quantity) => sum + Number(quantity || 0),
        0,
    ),
);

const formatPrice = (value) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(Number(value ?? 0));
const inCartQuantity = (productId) =>
    Number(props.cartItemQuantities[productId] || 0);
const buildAuthUrl = (baseUrl, productId) => {
    const redirectTarget =
        page.url || window.location.pathname + window.location.search;
    const params = new URLSearchParams({
        redirect: redirectTarget,
        product_id: productId?.toString() || '',
        quantity: '1',
    });

    return `${baseUrl}?${params.toString()}`;
};

const loginUrl = computed(() =>
    buildAuthUrl(login().url, pendingProductId.value),
);
const registerUrl = computed(() =>
    buildAuthUrl(register().url, pendingProductId.value),
);

const addToCart = (productId) => {
    if (!isAuthenticated.value) {
        pendingProductId.value = productId;
        authModalOpen.value = true;
        return;
    }

    router.post(
        cart.store().url,
        {
            product_id: productId,
            quantity: 1,
        },
        {
            preserveScroll: true,
            onError: (errors) => {
                const errorMessage =
                    errors.quantity ||
                    errors.product_id ||
                    'Failed to add item to cart';
                toast.error(errorMessage);
            },
        },
    );
};

const mobileFiltersOpen = ref(false);
</script>
