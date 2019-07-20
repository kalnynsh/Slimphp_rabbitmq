import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import App from './App.vue';
import router from './router';
import store from './store';
import axios from 'axios';

Vue.config.productionTip = false;

axios.defaults.baseURL = process.env.VUE_APP_API_URL;

Vue.use(BootstrapVue);

const user = JSON.parse(localStorage.getItem('user'));

if (user) {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + user.access_token;
}

axios.interceptors.response.use(null, error => {
    if (!error.response || error.response.status !== 401) {
        return Promise.reject(error);
    }

    const request = error.config;

    if (request.data) {
        let data = JSON.parse(request.data);

        if (data && data.grant_type) {
            return Promise.reject(error);
        }
    }

  return store.dispatch('refresh')
    .then(() => {
        return new Promise((resolve) => {
            request.headers['Authorization'] = 'Bearer ' + store.state.user.access_token;
            resolve(axios(request));
        });
    })
    .catch(() => {
        router.push({name: 'login'});
        return Promise.reject(error);
    });
});

const socket = new WebSocket(process.env.VUE_APP_WS_URL);

socket.open = function () {
  if (user) {
        socket.send(JSON.stringify({
            type: 'auth',
            token: user.access_token,
        }));
  }

  if (!user) {
    // eslint-disable-next-line no-console
    console.log('No user');
  }
};

socket.onmessage = function (event) {
    let data = JSON.parse(event.data);

    // eslint-disable-next-line no-console
    console.log(data);

    if (data.type === 'notification') {
        alert(data.message);
    }
};

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');
