<script setup>
import { useAuth } from "@/stores/auth.js";
import { computed, onMounted, ref } from "vue";

onMounted(() => {
  document.documentElement.setAttribute("data-bs-theme", theme.value);
});

const auth = useAuth();

const isCollapsed = ref(false);

const isDropdownOpen = ref(false);

const visible = ref(false);

const theme = ref(localStorage.getItem("theme") || "light");

const themeIcon = computed(() =>
  theme.value === "dark" ? "far fa-sun" : "far fa-moon-stars"
);

const toggleCollapsed = () => {
  isCollapsed.value = !isCollapsed.value;
  isDropdownOpen.value = false;
};

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const toggleTheme = () => {
  theme.value = theme.value === "dark" ? "light" : "dark";
  localStorage.setItem("theme", theme.value);
  document.documentElement.setAttribute("data-bs-theme", theme.value);
};
</script>

<template>
  <nav
    class="navbar navbar-expand-xxl navbar-dark bg-primary fixed-top border-bottom py-3"
    aria-label="Seventh navbar example"
    :class="{ collapsed: isCollapsed }"
  >
    <div class="container">
      <router-link :to="{ name: 'users.index' }" class="navbar-brand">
        <b>{{ auth.user.tenant.name }}</b>
      </router-link>
      <button
        class="navbar-toggler"
        type="button"
        aria-controls="navbarCollapse"
        :aria-expanded="isCollapsed"
        aria-label="Toggle navigation"
        @click="toggleCollapsed"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div
        :class="['collapse navbar-collapse', { show: isCollapsed }]"
        id="navbarCollapse"
      >
        <ul class="navbar-nav me-auto mb-2 mb-xl-0">
          <li class="nav-item">
            <router-link
              :to="{ name: 'home.index' }"
              class="nav-link"
              @click="toggleCollapsed"
            >
              Home
            </router-link>
          </li>
          <li class="nav-item" v-can="'roles.index'">
            <router-link
              :to="{ name: 'roles.index' }"
              class="nav-link"
              @click="toggleCollapsed"
            >
              Papéis
            </router-link>
          </li>
          <li class="nav-item" v-can="'users.index'">
            <router-link
              :to="{ name: 'users.index' }"
              class="nav-link"
              @click="toggleCollapsed"
            >
              Usuários
            </router-link>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto mb-2 mb-xl-0">
          <li class="nav-item">
            <a href="#" class="nav-link" @click.prevent="toggleTheme">
              <i :class="themeIcon"></i>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a
              @click.prevent="toggleDropdown"
              class="nav-link dropdown-toggle"
              href="#"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <i class="far fa-user ms-lg-2"></i> {{ auth.user.name }}
            </a>
            <ul :class="['dropdown-menu dropdown-menu-end', { show: isDropdownOpen }]">
              <li>
                <router-link
                  :to="{ name: 'profile' }"
                  class="dropdown-item"
                  @click="toggleCollapsed"
                >
                  Perfil
                </router-link>
              </li>
              <li>
                <a class="dropdown-item" href="#" @click.prevent="auth.logout()">
                  Sair
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="body-wrapper">
    <div class="container py-3">
      <router-view></router-view>
    </div>
  </div>
</template>

<style>
/* body {
  padding-top: 6em !important;
}
.navbar-nav > .nav-item:not(.dropdown) > .nav-link {
  position: relative;
  padding: 10px 15px;
}

.navbar-nav > .nav-item:not(.dropdown) > .nav-link.active::after {
  content: "";
  position: absolute;
  bottom: -16px;
  left: 0;
  width: 100%;
  height: 2px;
  background-color: white;
  transition: width 0.3s ease;
}
.navbar-nav > .nav-item:not(.dropdown) > .nav-link:hover::after,
.navbar-nav > .nav-item:not(.dropdown) > .nav-link.active:hover::after {
  width: 100%;
}
.dropdown-menu .dropdown-item::after {
  display: none !important;
  content: none !important;
}

.dropdown-menu .dropdown-item {
  position: static !important;
} */
</style>
