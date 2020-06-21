const getters = {
   // sidebar: state => state.app.sidebar,
   // language: state => state.dao.language,
   // size: state => state.app.size,
   // device: state => state.app.device,
   // visitedViews: state => state.tagsView.visitedViews,
   // cachedViews: state => state.tagsView.cachedViews,
   token: state => state.userStore.token,
   // avatar: state => state.user.avatar,
   name: state => state.userStore.name,
   user: state => state.userStore.user,
   roles: state => state.userStore.role,
   permission_routes: state => state.permisionsStore.routes,
   addRoutes: state => state.permisionsStore.addRoutes,

   // Update Data Getter
   updateUrl: state => state.updateStore.url,
   updateData: state => state.updateStore.data,
   updateRedirect: state => state.updateStore.redirect,

};
export default getters;
