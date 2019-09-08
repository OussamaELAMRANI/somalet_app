import router from '@/routes';
import store from '@/store';

import NProgress from 'nprogress'; // progress bar
import 'nprogress/nprogress.css'; // progress bar style

import {getToken} from '@/utils/auth'; // get token from cookie

NProgress.configure({showSpinner: false}); // NProgress Configuration

const whiteList = ['/login']; // no redirect whitelist

router.beforeEach(async (to, from, next) => {
    // start progress bar
    NProgress.start();

    // set page title
    document.title = (to.meta.title);

    // determine whether the user has logged in
    const hasToken = getToken();

    // console.log("Permission Cookies")
    // console.log(getToken())

    if (hasToken) {
        if (to.path === '/login') {
            // if is logged in, redirect to the home page
            next({path: '/dashboard'});
            NProgress.done();
        } else {
            // determine whether the user has obtained his permission roles through getInfo
            // && store.getters.roles.length > 0;
            const hasRoles = store.getters.roles;
            // console.log(hasRoles)
            if (hasRoles) {
                next();
            } else {
                // console.log("Has not roles")

                // get user info
                // note: roles must be a object array! such as: ['admin'] or ,['developer','editor']
                store.dispatch('userStore/userInfos')
                    .then((data) => {

                            // console.log("User Info Dispach")
                            // console.log(data.type_user)

                            store.dispatch('permisionsStore/generateRoutes', data.type_user)

                                .then(accessRoutes => {

                                    // dynamically add accessible routes
                                    router.addRoutes(accessRoutes);

                                    next({...to, replace: true});
                                    // console.log("NEXT", accessRoutes)
                                    NProgress.done();

                                }).catch(err => console.log(err))

                        }
                    )
                    .catch(err => {
                        console.log("in catch")
                        console.log(err)
                        // remove token and go to login page to re-login
                        store.dispatch('userStore/resetToken')
                            .then(res => {
                                next(`/login?redirect=${to.path}`);
                                NProgress.done();
                            });
                        // Message.error(error || 'Has Error');
                    })

            }
        }
    } else {
        /* has no token*/
        if (whiteList.indexOf(to.path) !== -1) {
            // in the free login whitelist, go directly
            next();
        } else {
            // other pages that do not have permission to access are redirected to the login page.
            next(`/login?redirect=${to.path}`);
            NProgress.done();
        }
    }
});

router.afterEach(() => {
    // finish progress bar
    NProgress.done();
});
