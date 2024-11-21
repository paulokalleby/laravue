export default {
  path: "/",
  component: () => import("@/layouts/DefaultLayout.vue"),
  children: [
    {
      path: "/",
      name: "home.index",
      component: () => import("@/pages/home/HomeIndex.vue"),
    },

    {
      path: "/roles",
      name: "roles.index",
      component: () => import("@/pages/roles/RolesIndex.vue"),
      meta: { permission: "roles.index" },
    },
    {
      path: "/roles/create",
      name: "roles.create",
      component: () => import("@/pages/roles/RolesCreate.vue"),
      meta: { permission: "roles.store" },
    },
    {
      path: "/roles/:id/edit",
      name: "roles.edit",
      component: () => import("@/pages/roles/RolesEdit.vue"),
      meta: { permission: "roles.update" },
      props: true,
    },
    {
      path: "/roles/:id/show",
      name: "roles.show",
      component: () => import("@/pages/roles/RolesShow.vue"),
      meta: { permission: "roles.show" },
      props: true,
    },

    {
      path: "/users",
      name: "users.index",
      component: () => import("@/pages/users/UsersIndex.vue"),
      meta: { permission: "users.index" },
    },
    {
      path: "/users/create",
      name: "users.create",
      component: () => import("@/pages/users/UsersCreate.vue"),
      meta: { permission: "users.store" },
    },
    {
      path: "/users/:id/edit",
      name: "users.edit",
      component: () => import("@/pages/users/UsersEdit.vue"),
      meta: { permission: "users.update" },
      props: true,
    },
    {
      path: "/users/:id/show",
      name: "users.show",
      component: () => import("@/pages/users/UsersShow.vue"),
      meta: { permission: "users.show" },
      props: true,
    },

    {
      path: "/profile",
      name: "profile",
      component: () => import("@/pages/profile/ProfileIndex.vue"),
    },
  ],
  meta: { auth: true },
};
