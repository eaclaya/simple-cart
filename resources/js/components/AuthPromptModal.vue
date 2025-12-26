<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

defineProps<{
    open: boolean;
    loginUrl: string;
    registerUrl: string;
}>();

const emit = defineEmits<{
    (event: 'update:open', value: boolean): void;
}>();
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-md">
            <DialogHeader class="space-y-3">
                <DialogTitle>Sign in to continue</DialogTitle>
                <DialogDescription>
                    Create an account or sign in to add items to your cart.
                </DialogDescription>
            </DialogHeader>
            <DialogFooter class="gap-3 sm:justify-start">
                <Button as-child>
                    <Link :href="loginUrl">Sign in</Link>
                </Button>
                <Button variant="outline" as-child>
                    <Link :href="registerUrl">Create account</Link>
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
