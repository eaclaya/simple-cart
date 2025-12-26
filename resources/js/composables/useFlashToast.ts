import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { toast } from 'vue-sonner';

interface FlashMessages {
    success?: string;
    error?: string;
}

export function useFlashToast() {
    const page = usePage();

    watch(
        () => page.props.flash as FlashMessages,
        (flash) => {
            if (flash?.success) {
                toast.success(flash.success);
            }
            if (flash?.error) {
                toast.error(flash.error);
            }
        },
        { immediate: true },
    );
}
