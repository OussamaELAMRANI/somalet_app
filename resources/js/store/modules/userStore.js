import {getToken, setToken, removeToken, expireToken, getExpireTime} from '@/utils/auth';


const state = {
    token: getToken(),
    expired_at: getExpireTime(),
    name: '',
    role: '',
    user: {}
};

// Setter of this state
const mutations = {
    SET_TOKEN: (state, token) => {
        state.token = token;
    },
    SET_EXPIRE_TIME: (state, exp) => {
        state.expired_at = exp;
    },

    SET_NAME: (state, name) => {
        state.name = name;
    },
    SET_ROLES: (state, roles) => {
        state.role = roles;
    },
    SET_USER: (state, user) => {
        state.user = user;
    },
};

const actions = {

    sign_up(context, registers) {
        return axios.post('/api/auth/sign-up', registers)
            .then((response) => Promise.resolve(response))
            .catch((error) => Promise.reject(error.response));
    },

    // user login
    login({commit}, userData) {
        return axios.post('/api/auth/login', userData)
            .then(res => {
                const {data} = res
                // console.log(data)
                commit('SET_TOKEN', data.access_token);
                setToken(data.access_token);

                commit('SET_EXPIRE_TIME', data.expires_at);
                expireToken(data.expires_at);

                commit('SET_ROLES', data.user.type_user);

                return Promise.resolve(res)
            })
            .catch((error) => Promise.reject(error.response));
    },

    logout({commit, state}) {
        const token = `Bearer ${state.token}`;

        return axios.get('api/auth/logout', {
            headers: {
                'Authorization': token,
            }
        })
            .then(res => {
                // Clear storage
                commit('SET_TOKEN', '');
                commit('SET_ROLES', []);
                commit('SET_USER', {});
                removeToken();

                return Promise.resolve(res);
            })
            .catch((error) => Promise.reject(error.response));
    },

    userInfos({commit, state}) {
        const token = `Bearer ${state.token}`;

        axios.defaults.headers.common['Authorization'] = token;

        return axios.get('/api/auth/user', {
            headers: {
                // 'Authorization': token,
            }
        })
            .then(res => {
                const {data} = res;
                const type = data.type_user;

                // TODO Verify the Role of this User !
                // roles must be a non-empty array
                // user.type === 'guest'
                // if (!type) {
                //     reject('getInfo: roles must be a non-null array!');
                // }

                commit('SET_ROLES', type);
                commit('SET_NAME', data.username);
                commit('SET_USER', data);

                return Promise.resolve(data);
            })
            .catch((error) => Promise.reject(error));
    },

    // remove token
    resetToken({commit}) {
        return new Promise(resolve => {
            commit('SET_TOKEN', '');
            commit('SET_ROLES', []);
            commit('SET_USER', {});

            removeToken();
            resolve();
        });
    },


};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
};
