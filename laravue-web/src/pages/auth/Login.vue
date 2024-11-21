<script setup>
import { useAuth } from "@/stores/auth.js";
import { useRouter } from "vue-router";
import { reactive, onBeforeMount } from "vue";
import { useToast } from "vue-toastification";
import useVuelidate from "@vuelidate/core";
import loginRules from "@/validations/auth/login.rules";
import http from "@/http";

onBeforeMount(() => (document.title = "Entrar"));

const toast = useToast();
const auth = useAuth();
const router = useRouter();
const state = reactive({
  data: {
    email: "",
    password: "",
    device: "webapp",
  },
  loading: false,
});

const v$ = useVuelidate(loginRules, state);

const handlerLogin = async () => {
  if (!(await v$.value.$validate())) return;
  state.loading = true;
  try {
    const response = await http.post("/auth/login", state.data);
    auth.setToken(response.data.token);
    auth.setUser(response.data.data);
    toast.success(`Olá ${response.data.data.name}, bem vindo!`);
    router.push({ name: "home.index" });
  } catch (error) {
    toast.error(error?.response?.data?.message || "Falha no login");
  } finally {
    state.loading = false;
  }
};
</script>

<template>
  <div class="auth-max-width col-sm-8 col-md-6 col-xl-7 px-4">
    <h2 class="mb-1 fs-7 fw-bolder">Login</h2>
    <p class="mb-7">Informe suas credencias para acessar</p>
    <form @submit.prevent="handlerLogin">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          v-model="state.data.email"
          type="text"
          class="form-control"
          id="email"
          placeholder="E-mail"
        />
        <small class="text-danger" v-if="v$.data.email.$error">
          {{ v$.data.email.$errors[0].$message }}
        </small>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input
          v-model="state.data.password"
          type="password"
          class="form-control"
          id="password"
          placeholder="Senha"
        />
        <small class="text-danger" v-if="v$.data.password.$error">
          {{ v$.data.password.$errors[0].$message }}
        </small>
      </div>
      <div class="d-flex align-items-center justify-content-between mb-3">
        <router-link
          :to="{ name: 'auth.recover-password' }"
          class="text-primary fw-medium fs-3"
        >
          Esqueceu sua senha?
        </router-link>
      </div>
      <button
        type="submit"
        class="btn btn-primary w-100 py-8 mb-4 rounded-2"
        :disabled="state.loading"
      >
        <span
          v-if="state.loading"
          class="spinner-border spinner-border-sm"
          role="status"
        ></span>
        <span v-else>Entrar</span>
      </button>
      <div class="d-flex align-items-center justify-content-center">
        <p class="fs-3 mb-0 fw-medium">Não tenho uma conta?</p>
        <router-link :to="{ name: 'auth.register' }" class="text-primary fw-medium ms-2"
          >Registre-se
        </router-link>
      </div>
    </form>
  </div>
</template>
