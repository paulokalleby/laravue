<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import PreLoader from "@/components/PreLoader.vue";
import useVuelidate from "@vuelidate/core";
import { userUpdateRules } from "@/validations/user.rules";
import { useAuth } from "@/stores/auth";
import http from "@/http";

onBeforeMount(() => (document.title = "Perfil"));

onMounted(async () => {
  await fecthMe();
});

const auth = useAuth();

const toast = useToast();

const state = reactive({
  data: {
    name: "",
    email: "",
    password: "",
    active: true,
  },
  loading: false,
  updating: false,
});

const v$ = useVuelidate(userUpdateRules, state);

const fecthMe = async () => {
  state.loading = true;
  try {
    const response = await http.get("/auth/me");
    state.data = response.data.data;
  } catch (error) {
  } finally {
    state.loading = false;
  }
};

const handlerUpdate = async () => {
  if (!(await v$.value.$validate())) return;
  state.updating = true;
  try {
    const response = await http.put("/auth/profile", state.data);
    toast.success("Conta atualizada com sucesso!");
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao atualizar usuário");
  } finally {
    state.updating = false;
  }
};
</script>

<template>
  <PreLoader :show="state.loading" />

  <div class="row mb-2">
    <div class="col-12">
      <div class="d-flex flex-row justify-content-between align-items-center">
        <h4 class="fw-semibold">Minha Conta</h4>
      </div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3">
          <li class="breadcrumb-item">
            <router-link
              class="text-muted text-decoration-none"
              :to="{ name: 'home.index' }"
              >home</router-link
            >
          </li>
          <li class="breadcrumb-item" aria-current="page">minha conta</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card border shadow-none py-3">
        <div class="card-body">
          <form @submit.prevent="handlerUpdate">
            <div class="row mb-3">
              <label for="name" class="col-sm-2 col-form-label text-md-end">Nome</label>
              <div class="col-md-4">
                <input
                  v-model="state.data.name"
                  type="text"
                  class="form-control"
                  id="name"
                />
                <small class="text-danger" v-if="v$.data.name.$error">
                  {{ v$.data.name.$errors[0].$message }}
                </small>
              </div>
            </div>
            <div class="row mb-3">
              <label for="email" class="col-sm-2 col-form-label text-md-end">Email</label>
              <div class="col-md-4">
                <input
                  v-model="state.data.email"
                  type="text"
                  class="form-control"
                  id="email"
                />
                <small class="text-danger" v-if="v$.data.email.$error">
                  {{ v$.data.email.$errors[0].$message }}
                </small>
              </div>
            </div>
            <div class="row mb-3">
              <label for="password" class="col-sm-2 col-form-label text-md-end"
                >Senha</label
              >
              <div class="col-md-4">
                <input
                  v-model="state.data.password"
                  type="password"
                  class="form-control"
                  id="password"
                />
                <small class="text-danger" v-if="v$.data.password.$error">
                  {{ v$.data.password.$errors[0].$message }}
                </small>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-10 offset-md-2">
                <button
                  type="submit"
                  class="btn btn-primary me-2"
                  :disabled="state.updating"
                >
                  <span v-if="state.updating">Enviando...</span>
                  <span v-else>Atualizar</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
