import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// jQuery
import $ from 'jquery';
window.$ = window.jQuery = $;

// Bootstrap
import * as bootstrap from 'bootstrap';