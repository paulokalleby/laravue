<script setup>
import { useAuth } from "@/stores/auth.js";
import { computed, onMounted, ref } from "vue";

onMounted(() => {
  document.documentElement.setAttribute("data-bs-theme", theme.value);
  if (theme.value === "light") {
    document.body.classList.add("bg-info-subtle");
  }
});

const auth = useAuth();

const isCollapsed = ref(false);

const isDropdownOpen = ref(false);

const theme = ref(localStorage.getItem("theme") || "light");

const themeIcon = computed(() =>
  theme.value === "dark" ? "far fa-sun" : "far fa-moon-stars"
);

const toggleCollapsed = () => {
  isCollapsed.value = !isCollapsed.value;
  isDropdownOpen.value = false;
};

const toggleTheme = () => {
  theme.value = theme.value === "dark" ? "light" : "dark";
  localStorage.setItem("theme", theme.value);
  document.documentElement.setAttribute("data-bs-theme", theme.value);

  if (theme.value === "light") {
    document.body.classList.add("bg-info-subtle");
  } else {
    document.body.classList.remove("bg-info-subtle");
  }
};
</script>

<template>
  <div class="wrapper">
    <aside id="sidebar" :class="['bg-primary', { expand: isCollapsed }]">
      <div class="d-flex">
        <button class="toggle-btn" type="button" @click.prevent="toggleCollapsed">
          <i :class="[isCollapsed ? 'fad' : 'far', 'fa-bars']"></i>
        </button>
        <div class="sidebar-logo text-white fw-bold">
          <a href="#">Laravue</a>
        </div>
      </div>
      <ul class="sidebar-nav">
        <li class="sidebar-item">
          <router-link :to="{ name: 'home.index' }" class="sidebar-link">
            <i class="far fa-home"></i>
            <span>Home</span>
          </router-link>
        </li>
        <li class="sidebar-item" v-can="'roles.index'">
          <router-link :to="{ name: 'roles.index' }" class="sidebar-link">
            <i class="far fa-user-lock"></i>
            <span>Papéis</span>
          </router-link>
        </li>
        <li class="sidebar-item" v-can="'users.index'">
          <router-link :to="{ name: 'users.index' }" class="sidebar-link">
            <i class="far fa-users"></i>
            <span>Usuários</span>
          </router-link>
        </li>
      </ul>
      <div class="sidebar-footer">
        <router-link :to="{ name: 'profile' }" class="sidebar-link">
          <i class="far fa-user"></i>
          <span>{{ auth.user.name }}</span>
        </router-link>
        <a @click.prevent="toggleTheme" href="#" class="sidebar-link">
          <i :class="themeIcon"></i>
          <span>Tema</span>
        </a>
        <a href="#" @click.prevent="auth.logout()" class="sidebar-link">
          <i class="far fa-power-off"></i>
          <span>Sair</span>
        </a>
      </div>
    </aside>
    <div class="main p-lg-5 p-3">
      <RouterView />
    </div>
  </div>
</template>

<style scoped>
::after,
::before {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

a {
  text-decoration: none;
}

li {
  list-style: none;
}

.wrapper {
  display: flex;
}

#sidebar {
  width: 70px;
  min-width: 70px;
  z-index: 1000;
  transition: width 0.25s ease-in-out; /* Transição suave de largura */
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  bottom: 0;
  overflow: hidden;
}
#sidebar.expand {
  width: 260px;
  min-width: 260px;
}
.main {
  min-height: 100vh;
  width: calc(100% - 70px);
  margin-left: 70px;
  padding: 2rem;
  overflow: auto;
  transition: all 0.25s ease-in-out;
}

/* Main content ajustado para sidebar expandida */
#sidebar.expand + .main {
  width: calc(100% - 260px);
  margin-left: 260px;
}

.toggle-btn {
  background-color: transparent;
  cursor: pointer;
  border: 0;
  padding: 1rem 1.5rem;
}

.toggle-btn i {
  font-size: 1.5rem;
  color: #fff;
}

.sidebar-logo {
  margin: auto 0;
}

.sidebar-logo a {
  color: #fff;
  font-size: 1.15rem;
  font-weight: 600;
}
#sidebar:not(.expand) .sidebar-logo,
#sidebar:not(.expand) a.sidebar-link span {
  display: none;
}

.sidebar-nav {
  padding: 2rem 0;
  flex: 1 1 auto;
}

a.sidebar-link {
  padding: 0.625rem 1.5rem;
  color: #fff;
  display: block;
  font-size: 0.9rem;
  white-space: nowrap;
  border-left: 2px solid transparent;
}

.sidebar-link i {
  font-size: 1.1rem;
  margin-right: 0.75rem;
}

a.sidebar-link:hover {
  background-color: rgba(255, 255, 255, 0.075);
  border-left: 2px solid #fff;
}

a.active {
  background-color: rgba(255, 255, 255, 0.075);
  border-left: 2px solid #fff;
}

a.sidebar-link {
  padding: 0.625rem 1.5rem;
  color: #fff;
  display: block;
  font-size: 0.9rem;
  white-space: nowrap;
  border-left: 2px solid transparent;
}

.sidebar-link i {
  font-size: 1.1rem;
  margin-right: 0.75rem;
}

a.sidebar-link:hover {
  background-color: rgba(255, 255, 255, 0.075);
  border-left: 2px solid #fff;
}

a.active {
  background-color: rgba(255, 255, 255, 0.075);
  border-left: 2px solid #fff;
}

.sidebar-item {
  position: relative;
}
</style>
