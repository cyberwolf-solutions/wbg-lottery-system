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
                siteKey: '6LdM2wkrAAAAAGxBDb3_rnTHEdSA1ZPzJsjviKRA',
                // siteKey: import.meta.env.RECAPTCHA_SITE_KEY
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});



const selectedNumbers = ref(Array.from({ length: 5 }, () => []));


function toggleNumber(ticketIndex, number) {
    const ticketNumbers = [...selectedNumbers.value[ticketIndex]]; 
    if (ticketNumbers.includes(number)) {
     
        selectedNumbers.value[ticketIndex] = ticketNumbers.filter(n => n !== number);
    } else {
        
        selectedNumbers.value[ticketIndex].push(number);
    }
}


function formatNumber(number) {
    return number < 10 ? `0${number}` : `${number}`;
}
