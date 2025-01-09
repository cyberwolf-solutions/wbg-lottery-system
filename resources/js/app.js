// alert('app.js is loaded!');
import './bootstrap';
import { createApp } from 'vue';
import 'bootstrap/dist/css/bootstrap.min.css';



import LandingPage from './components/Landing/LandingPage.vue';
import Nav from './components/Landing/nav.vue';
import Footer from './components/Landing/footer.vue';


const app = createApp({});


app.component('landing-page', LandingPage); 
app.component('custom-nav', Nav);
app.component('custom-footer', Footer);

app.mount('#app');
