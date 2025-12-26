<template>
    <div class="bg-gray-50">
        <div>
            <!-- Mobile menu -->
            <TransitionRoot as="template" :show="mobileMenuOpen">
                <Dialog
                    class="relative z-40 lg:hidden"
                    @close="mobileMenuOpen = false"
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
                            enter-from="-translate-x-full"
                            enter-to="translate-x-0"
                            leave="transition ease-in-out duration-300 transform"
                            leave-from="translate-x-0"
                            leave-to="-translate-x-full"
                        >
                            <DialogPanel
                                class="relative flex w-full max-w-xs flex-col overflow-y-auto bg-white pb-12 shadow-xl"
                            >
                                <div class="flex px-4 pt-5 pb-2">
                                    <button
                                        type="button"
                                        class="relative -m-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400"
                                        @click="mobileMenuOpen = false"
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

                                <div
                                    class="space-y-6 border-t border-gray-200 px-4 py-6"
                                >
                                    <div
                                        v-for="page in navigation.pages"
                                        :key="page.name"
                                        class="flow-root"
                                    >
                                        <a
                                            :href="page.href"
                                            class="-m-2 block p-2 font-medium text-gray-900"
                                            >{{ page.name }}</a
                                        >prod
                                    </div>
                                </div>

                                <div
                                    class="space-y-6 border-t border-gray-200 px-4 py-6"
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
                                        <div class="flow-root">
                                            <Link
                                                :href="register()"
                                                class="-m-2 block p-2 font-medium text-gray-900"
                                                >Create an account</Link
                                            >
                                        </div>
                                        <div class="flow-root">
                                            <Link
                                                :href="login()"
                                                class="-m-2 block p-2 font-medium text-gray-900"
                                                >Sign in</Link
                                            >
                                        </div>
                                    </template>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </Dialog>
            </TransitionRoot>

            <header class="relative">
                <nav aria-label="Top">
                    <!-- Secondary navigation -->
                    <div class="bg-white shadow-sm">
                        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div class="flex h-16 items-center justify-between">
                                <!-- Logo (lg+) -->
                                <div
                                    class="hidden lg:flex lg:flex-1 lg:items-center"
                                >
                                    <a href="#">
                                        <span class="sr-only"
                                            >Your Company</span
                                        >
                                        <img
                                            class="h-8 w-auto"
                                            src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600"
                                            alt=""
                                        />
                                    </a>
                                </div>

                                <!-- Mobile menu and search (lg-) -->
                                <div class="flex flex-1 items-center lg:hidden">
                                    <button
                                        type="button"
                                        class="-ml-2 rounded-md bg-white p-2 text-gray-400"
                                        @click="mobileMenuOpen = true"
                                    >
                                        <span class="sr-only">Open menu</span>
                                        <Bars3Icon
                                            class="size-6"
                                            aria-hidden="true"
                                        />
                                    </button>

                                    <!-- Search -->
                                    <a
                                        href="#"
                                        class="ml-2 p-2 text-gray-400 hover:text-gray-500"
                                    >
                                        <span class="sr-only">Search</span>
                                        <MagnifyingGlassIcon
                                            class="size-6"
                                            aria-hidden="true"
                                        />
                                    </a>
                                </div>

                                <!-- Logo (lg-) -->
                                <a href="#" class="lg:hidden">
                                    <span class="sr-only">Your Company</span>
                                    <img
                                        src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600"
                                        alt=""
                                        class="h-8 w-auto"
                                    />
                                </a>

                                <div
                                    class="flex flex-1 items-center justify-end"
                                >
                                    <div class="flex items-center lg:ml-8">
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

                                <!-- Filters -->
                                <form class="mt-4">
                                    <Disclosure
                                        as="div"
                                        v-for="section in filters"
                                        :key="section.name"
                                        class="border-t border-gray-200 px-4 py-6"
                                        v-slot="{ open }"
                                    >
                                        <h3 class="-mx-2 -my-3 flow-root">
                                            <DisclosureButton
                                                class="flex w-full items-center justify-between bg-white px-2 py-3 text-sm text-gray-400"
                                            >
                                                <span
                                                    class="font-medium text-gray-900"
                                                    >{{ section.name }}</span
                                                >
                                                <span
                                                    class="ml-6 flex items-center"
                                                >
                                                    <ChevronDownIcon
                                                        :class="[
                                                            open
                                                                ? '-rotate-180'
                                                                : 'rotate-0',
                                                            'size-5 transform',
                                                        ]"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                            </DisclosureButton>
                                        </h3>
                                        <DisclosurePanel class="pt-6">
                                            <div class="space-y-6">
                                                <div
                                                    v-for="(
                                                        option, optionIdx
                                                    ) in section.options"
                                                    :key="option.value"
                                                    class="flex gap-3"
                                                >
                                                    <div
                                                        class="flex h-5 shrink-0 items-center"
                                                    >
                                                        <div
                                                            class="group grid size-4 grid-cols-1"
                                                        >
                                                            <input
                                                                :id="`filter-mobile-${section.id}-${optionIdx}`"
                                                                :name="`${section.id}[]`"
                                                                :value="
                                                                    option.value
                                                                "
                                                                type="checkbox"
                                                                :checked="
                                                                    option.checked
                                                                "
                                                                class="col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"
                                                            />
                                                            <svg
                                                                class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25"
                                                                viewBox="0 0 14 14"
                                                                fill="none"
                                                            >
                                                                <path
                                                                    class="opacity-0 group-has-[:checked]:opacity-100"
                                                                    d="M3 8L6 11L11 3.5"
                                                                    stroke-width="2"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                />
                                                                <path
                                                                    class="opacity-0 group-has-[:indeterminate]:opacity-100"
                                                                    d="M3 7H11"
                                                                    stroke-width="2"
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <label
                                                        :for="`filter-mobile-${section.id}-${optionIdx}`"
                                                        class="ml-3 text-sm text-gray-500"
                                                        >{{
                                                            option.label
                                                        }}</label
                                                    >
                                                </div>
                                            </div>
                                        </DisclosurePanel>
                                    </Disclosure>
                                </form>
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
                                v-for="(product, productIdx) in products"
                                :key="product.id"
                                class="group"
                            >
                                <div class="block">
                                    <img
                                        :src="
                                            product.image_url ||
                                            productImage(productIdx)
                                        "
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
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue';
import { ChevronDownIcon } from '@heroicons/vue/20/solid';
import {
    Bars3Icon,
    MagnifyingGlassIcon,
    ShoppingBagIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
import { toast } from 'vue-sonner';

const filters = [
    {
        id: 'category',
        name: 'Category',
        options: [
            { value: 'tees', label: 'Tees' },
            { value: 'crewnecks', label: 'Crewnecks' },
            { value: 'hats', label: 'Hats' },
            { value: 'bundles', label: 'Bundles' },
            { value: 'carry', label: 'Carry' },
            { value: 'objects', label: 'Objects' },
        ],
    },
    {
        id: 'brand',
        name: 'Brand',
        options: [
            { value: 'clothing-company', label: 'Clothing Company' },
            { value: 'fashion-inc', label: 'Fashion Inc.' },
            { value: 'shoes-n-more', label: "Shoes 'n More" },
            { value: 'supplies-n-stuff', label: "Supplies 'n Stuff" },
        ],
    },
    {
        id: 'color',
        name: 'Color',
        options: [
            { value: 'white', label: 'White' },
            { value: 'black', label: 'Black' },
            { value: 'grey', label: 'Grey' },
            { value: 'blue', label: 'Blue' },
            { value: 'olive', label: 'Olive' },
            { value: 'tan', label: 'Tan' },
        ],
    },
    {
        id: 'sizes',
        name: 'Sizes',
        options: [
            { value: 'xs', label: 'XS' },
            { value: 's', label: 'S' },
            { value: 'm', label: 'M' },
            { value: 'l', label: 'L' },
            { value: 'xl', label: 'XL' },
            { value: '2xl', label: '2XL' },
        ],
    },
];
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
const footerNavigation = {
    products: [
        { name: 'Bags', href: '#' },
        { name: 'Tees', href: '#' },
        { name: 'Objects', href: '#' },
        { name: 'Home Goods', href: '#' },
        { name: 'Accessories', href: '#' },
    ],
    company: [
        { name: 'Who we are', href: '#' },
        { name: 'Sustainability', href: '#' },
        { name: 'Press', href: '#' },
        { name: 'Careers', href: '#' },
        { name: 'Terms & Conditions', href: '#' },
        { name: 'Privacy', href: '#' },
    ],
    customerService: [
        { name: 'Contact', href: '#' },
        { name: 'Shipping', href: '#' },
        { name: 'Returns', href: '#' },
        { name: 'Warranty', href: '#' },
        { name: 'Secure Payments', href: '#' },
        { name: 'FAQ', href: '#' },
        { name: 'Find a store', href: '#' },
    ],
};

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
const products = ref([...props.products]);
const primaryProducts = computed(() => products.value.slice(0, 6));
const secondaryProducts = computed(() => products.value.slice(6));
const productImages = [
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-01.jpg',
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-02.jpg',
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-03.jpg',
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-04.jpg',
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-05.jpg',
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-06.jpg',
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-07.jpg',
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-08.jpg',
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-09.jpg',
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-10.jpg',
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-11.jpg',
    'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-12.jpg',
];
const productImage = (index) => productImages[index % productImages.length];
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
                    errors.quantity || errors.product_id || 'Failed to add item to cart';
                toast.error(errorMessage);
            },
        },
    );
};

const fetchProducts = async () => {
    try {
        const response = await fetch('/api/products', {
            headers: { Accept: 'application/json' },
        });

        if (!response.ok) {
            return;
        }

        products.value = await response.json();
    } catch {
        // Keep existing products if the API request fails.
    }
};

onMounted(fetchProducts);

const mobileMenuOpen = ref(false);
const mobileFiltersOpen = ref(false);
</script>
