import '../css/app.css';
import './bootstrap';
import { useReCaptcha } from 'vue-recaptcha-v3';
import { ref } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { VueReCaptcha } from 'vue-recaptcha-v3';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';




axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Fetch and set CSRF token from Laravel
axios.get('/sanctum/csrf-cookie').then(() => {
    console.log("CSRF token set.");
});


createInertiaApp({
    title: (title) => title ? `${title} - Winboard Game` : 'Winboard Game',
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueReCaptcha, { 
                siteKey: '6LdM2wkrAAAAAGxBDb3_rnTHEdSA1ZPzJsjviKRA' 
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});



const selectedNumbers = ref(Array.from({ length: 5 }, () => []));

// Toggles the selection of a number for a specific ticket
function toggleNumber(ticketIndex, number) {
    const ticketNumbers = [...selectedNumbers.value[ticketIndex]]; // Create a shallow copy to maintain reactivity
    if (ticketNumbers.includes(number)) {
        // Remove the number if already selected
        selectedNumbers.value[ticketIndex] = ticketNumbers.filter(n => n !== number);
    } else {
        // Add the number if not already selected
        selectedNumbers.value[ticketIndex].push(number);
    }
}

// Formats numbers to start with 00, 01, etc.
function formatNumber(number) {
    return number < 10 ? `0${number}` : `${number}`;
}
