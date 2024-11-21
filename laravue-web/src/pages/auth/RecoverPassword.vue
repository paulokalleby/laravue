<script setup>
import { useRouter } from "vue-router";
import { reactive, onBeforeMount, computed } from "vue";
import { useToast } from "vue-toastification";
import useVuelidate from "@vuelidate/core";
import {
  forgotRules,
  verifyRules,
  resetRules,
} from "@/validations/auth/recover-password.rules";
import http from "@/http";

onBeforeMount(() => (document.title = "Recuperar Senha"));

const toast = useToast();
const router = useRouter();

const state = reactive({
  data: {
    email: "",
    code: "",
    password: "",
    confirmPassword: "",
  },
  description: "Informe os dados solicitados",
  step: 1,
  loading: false,
});

const f$ = useVuelidate(forgotRules, state);
const v$ = useVuelidate(verifyRules, state);
const rules = computed(() => resetRules(state.data.password));
const r$ = useVuelidate(rules, state);

const handlerForgotPassword = async () => {
  if (!(await f$.value.$validate())) return;
  state.loading = true;
  try {
    const response = await http.post("/auth/password/code", { email: state.data.email });
    state.step = 2;
    state.description = "Informe o código recebido por email";
    toast.success(response.data.message);
  } catch (error) {
    toast.error(
      error?.response?.data?.message || "Falha ao enviar email para redefinição!"
    );
  } finally {
    state.loading = false;
  }
};

const handlerVerifyCode = async () => {
  if (!(await v$.value.$validate())) return;
  state.loading = true;
  try {
    const response = await http.post("/auth/password/verify", {
      email: state.data.email,
      code: state.data.code,
    });
    state.step = 3;
    state.description = "Informe a senha desejada";
    toast.success(response.data.message);
  } catch (error) {
    toast.error(error?.response?.data?.message || "Falha verificação do código!");
  } finally {
    state.loading = false;
  }
};

const handlerResetPassword = async () => {
  if (!(await r$.value.$validate())) return;
  state.loading = true;
  try {
    const response = await http.post("/auth/password/reset", {
      email: state.data.email,
      code: state.data.code,
      password: state.data.password,
    });
    state.step = 1;
    toast.success(response.data.message);
    router.push({ name: "auth.login" });
  } catch (error) {
    toast.error(error?.response?.data?.message || "Falha na alteração da senha!");
  } finally {
    state.loading = false;
  }
};
</script>

<template>
  <div class="auth-max-width col-sm-8 col-md-6 col-xl-7 px-4">
    <h2 class="mb-1 fs-7 fw-bolder">Recuperar Senha</h2>
    <p class="mb-7">{{ state.description }}</p>
    <form v-if="state.step == 1" @submit.prevent="handlerForgotPassword">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          v-model="state.data.email"
          type="text"
          class="form-control"
          id="email"
          placeholder="Seu email"
        />
        <small class="text-danger" v-if="f$.data.email.$error">
          {{ f$.email.data.$errors[0].$message }}
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
        <span v-else>Enviar</span>
      </button>
      <div class="d-flex align-items-center justify-content-center">
        <p class="fs-3 mb-0 fw-medium">Lembrei da senha?</p>
        <router-link :to="{ name: 'auth.login' }" class="text-primary fw-medium ms-2"
          >Entrar
        </router-link>
      </div>
    </form>

    <form v-if="state.step == 2" @submit.prevent="handlerVerifyCode">
      <div class="mb-3">
        <label for="code" class="form-label">Código</label>
        <input
          v-model="state.data.code"
          type="text"
          class="form-control"
          id="code"
          placeholder="Código de verificação"
        />
        <small class="text-danger" v-if="v$.data.code.$error">
          {{ v$.data.code.$errors[0].$message }}
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
        <span v-else>Verificar</span>
      </button>
      <div class="d-flex align-items-center justify-content-center">
        <p class="fs-3 mb-0 fw-medium">Lembrei da senha?</p>
        <router-link :to="{ name: 'auth.login' }" class="text-primary fw-medium ms-2"
          >Entrar
        </router-link>
      </div>
    </form>

    <form v-if="state.step == 3" @submit.prevent="handlerResetPassword">
      <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input
          v-model="state.data.password"
          type="password"
          class="form-control"
          id="password"
          placeholder="Senha"
        />
        <small class="text-danger" v-if="r$.data.password.$error">
          {{ r$.data.password.$errors[0].$message }}
        </small>
      </div>
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirmar senha</label>
        <input
          v-model="state.data.confirmPassword"
          type="password"
          class="form-control"
          id="confirmPassword"
          placeholder="Confirmação de Senha"
        />
        <small class="text-danger" v-if="r$.data.confirmPassword.$error">
          {{ r$.data.confirmPassword.$errors[0].$message }}
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
        <span v-else>Salvar</span>
      </button>
      <div class="d-flex align-items-center justify-content-center">
        <p class="fs-3 mb-0 fw-medium">Lembrei da senha?</p>
        <router-link :to="{ name: 'auth.login' }" class="text-primary fw-medium ms-2"
          >Entrar
        </router-link>
      </div>
    </form>
  </div>
</template>
