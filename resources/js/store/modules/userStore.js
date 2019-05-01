const state = {
    accessToken: null,
    TokenType: null,
    ExpireAt: null,
    name: null,
    email: null,
    role: null,
};

// Setter of this state
const mutations = {
    setAccessToken: (state, value) => {
        state.accessToken = value;
    },
    setTokenType: (state, value) => {
        state.TokenType = value;
    },
    setExpireDate: (state, value) => {
        state.ExpireAt = value;
    },
    setUsername: (state, value) => {
        state.name = value;
    },
    setRole: (state, value) => {
        state.role = value;
    },
    setEmail: (state, value) => {
        state.email = value;
    },
};

// Getters of this state
const getters = {

    // isAvailableToken(state) {
    //     let exp = null;
    //     const today = new Date().toDateString();
    //     if (state.ExpireAt)
    //         exp = state.ExpireAt;

    //     else if (localStorage.getItem('expires_at'))
    //         exp = localStorage.getItem('expires_at');
    //     return today >= exp;
    // },


    getToken: (state) => {
        return state.accessToken;

    },
    getTokenType: (state) => {
        return state.TokenType;
    },
    getExpireDate: (state) => {
        return state.ExpireAt;
    },
    getName(state) {
        return state.name
    },
    getEmail(state) {
        return state.email
    },
    getRole(state) {
        return state.role
    },
};

const actions = {
    /**
     * Login a user
     *
     * @param context {Object}
     * @param credentials {Object} User credentials
     * @param credentials.email {string} User email
     * @param credentials.password {string} User password
     */
    login(context, credentials) {

        return axios.post('api/auth/login', credentials)
            .then((response) => {
                // retrieve access token
                const {
                    access_token: AccessToken,
                    token_type: TokenType,
                    expires_at: ExpireAt,
                    user: user
                } = response.data;

                // commit it
                context.commit('setAccessToken', AccessToken);
                context.commit('setTokenType', TokenType);
                context.commit('setExpireDate', ExpireAt);

                context.commit('setUsername', user.name);
                context.commit('setEmail', user.email);
                context.commit('setRole', user.type_user);

                // Storage in locale

                localStorage.setItem('access_token', AccessToken)
                localStorage.setItem('token_type', TokenType)
                localStorage.setItem('expires_at', ExpireAt)

                return Promise.resolve();
            })
            .catch((error) => Promise.reject(error.response));
    },
    /**
     *
     * @param context
     * @param registers
     */
    signUp(context, registers) {
        return axios.post('api/auth/sign-up', registers)
            .then((response) => {
                return Promise.resolve(response);
            })
            .catch((error) => Promise.reject(error.response));
    },

    loadStats({commit}) {
        commit('setAccessToken', localStorage.getItem('access_token'))
        commit('setTokenType', localStorage.getItem('token_type'))
        commit('setExpireDate', localStorage.getItem('expires_at'))
    },

    logout(context) {
        const token = `Bearer ${context.getters.getToken}`
        // const auth = {'Authorization': token};
        // console.log(token)

        return axios.get('api/auth/logout', {
            headers: {
                'Authorization': token,
            }
        })
            .then(res => {
                // Clear storage
                localStorage.clear();
                // Clear the states
                context.commit('setAccessToken', null);
                context.commit('setTokenType', null);
                context.commit('setExpireDate', null);
                context.commit('setUsername', null);
                context.commit('setEmail', null);
                context.commit('setRole', null);

                return Promise.resolve(res);
            })
            .catch((error) => Promise.reject(error.response));
    },
    userInfo(context) {
        const token = `Bearer ${context.getters.getToken}`
        const _this = this;
        return axios.get('api/auth/user', {headers: {'Authorization': token}})
            .then(res => {
                const {name, email, role,} = res.data;
                context.commit('setUsername', name);
                context.commit('setEmail', email);
                context.commit('setRole', role);

                return Promise.resolve(res);
            })
            .catch((error) => Promise.reject(error.response));
    },

    isAuthenticated: function ({commit, state}) {
        const token = window.localStorage.getItem('access_token')

        return Promise.resolve(token !== null );
    },
    // isAdmin: ({})
};

export default {
    state,
    getters,
    actions,
    mutations
}
