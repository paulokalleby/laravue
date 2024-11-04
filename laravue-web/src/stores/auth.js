import { computed, ref } from "vue";

import AuthService from "@/services/auth.service";
import { defineStore } from "pinia";

export const useAuth = defineStore("auth", () => {
  const token = ref(localStorage.getItem("token"));

  const user = ref(JSON.parse(localStorage.getItem("user")));

  function setToken(tokenValue) {
    localStorage.setItem("token", tokenValue);
    token.value = tokenValue;
  }

  function setUser(userValue) {
    localStorage.setItem("user", JSON.stringify(userValue));
    user.value = userValue;
  }

  async function checkToken() {
    try {
      const { data } = await AuthService.me();
      return data;
    } catch (error) {
      return false;
    }
  }

  async function logout() {
    try {
      const { response } = await AuthService.logout();
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      token.value = "";
      user.value = "";
      window.location = "/auth/login";
    } catch (error) {
      return false;
    }
  }

  const isAuthenticated = computed(() => {
    return token.value && user.value;
  });

  return {
    token,
    user,
    setToken,
    setUser,
    checkToken,
    isAuthenticated,
    logout,
  };
});
