<script setup>
import { computed, onBeforeMount, reactive } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
import useVuelidate from "@vuelidate/core";
import registerRules from "@/validations/auth/register.rules";
import http from "@/http";

onBeforeMount(() => (document.title = "Registre-se"));

const toast = useToast();
const router = useRouter();
const state = reactive({
  data: {
    business: "",
    name: "",
    email: "",
    password: "",
  },
  loading: false,
});

const rules = computed(() => registerRules(state.data.password));

const v$ = useVuelidate(rules, state);

const handlerRegister = async () => {
  if (!(await v$.value.$validate())) return;
  state.loading = true;
  try {
    const response = await http.post("/auth/register", state.data);
    toast.success("Registro realizada com sucesso!");
    router.push({ name: "auth.login" });
  } catch (error) {
    toast.error(error?.response?.data?.message || "Falha ao realizar registro");
  } finally {
    state.loading = false;
  }
};
</script>

<template>
  <div class="auth-max-width col-sm-8 col-md-6 col-xl-7 px-4">
    <h2 class="mb-1 fs-7 fw-bolder">Registre-se</h2>
    <p class="mb-7">Se inscreva para ter acesso</p>
    <form @submit.prevent="handlerRegister">
      <div class="mb-3">
        <label for="business" class="form-label">Negócio</label>
        <input
          v-model="state.data.business"
          type="text"
          class="form-control"
          id="business"
          placeholder="Nome do seu negócio"
        />
        <small class="text-danger" v-if="v$.data.business.$error">
          {{ v$.data.business.$errors[0].$message }}
        </small>
      </div>
      <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input
          v-model="state.data.name"
          type="text"
          class="form-control"
          id="name"
          placeholder="Seu nome"
        />
        <small class="text-danger" v-if="v$.data.name.$error">
          {{ v$.data.name.$errors[0].$message }}
        </small>
      </div>
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
        <span v-else>Registrar</span>
      </button>
      <div class="d-flex align-items-center justify-content-center">
        <p class="fs-3 mb-0 fw-medium">Já tenho uma conta?</p>
        <router-link :to="{ name: 'auth.login' }" class="text-primary fw-medium ms-2"
          >Entrar
        </router-link>
      </div>
    </form>
  </div>
</template>
