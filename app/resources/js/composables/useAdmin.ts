import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useAdmin() {
    const page = usePage();
    const isAdmin = computed(() => page.props.auth.user?.is_admin === true);
    return { isAdmin };
}
