<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import UserService from "@/services/user.service";
import RoleService from "@/services/role.service";

import useVuelidate from "@vuelidate/core";
import usersStoreRules from "@/validations/users/users-store.rules";

onBeforeMount(() => {
  document.title = "Cadastrar Usuário";
});

onMounted(async () => {
  await fetchRoles();
});

const toast = useToast();

const router = useRouter();

const state = reactive({
  data: {
    name: "",
    email: "",
    password: "",
    roles: [],
    active: true,
  },
  roles: [],
  creating: false,
});

const v$ = useVuelidate(usersStoreRules, state);

const fetchRoles = async () => {
  try {
    const response = await RoleService.getActiveRoles();
    state.roles = response.data.data;
    console.log(response.data.data);
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar papéis");
  }
};

const handlerStore = async () => {
  if (!(await v$.value.$validate())) return;
  state.creating = true;
  try {
    const response = await UserService.create(state.data);
    toast.success("Usuário cadastrada com sucesso!");
    router.push({ name: "users.index" });
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao criar usuário");
  } finally {
    state.creating = false;
  }
};

const toggleRole = (roleId) => {
  const index = state.data.roles.indexOf(roleId);
  if (index === -1) {
    state.data.roles.push(roleId);
  } else {
    state.data.roles.splice(index, 1);
  }
};

const roleSelected = (roleId) => {
  return state.data.roles.includes(roleId);
};
</script>

<template>
  <div class="row mb-2">
    <div class="col-12 mb-3">
      <h4 class="fw-semibold mb-8">Cadastrar Usuário</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link
              class="text-muted text-decoration-none"
              :to="{ name: 'home.index' }"
              >Home</router-link
            >
          </li>
          <li class="breadcrumb-item">
            <router-link
              class="text-muted text-decoration-none"
              :to="{ name: 'users.index' }"
              >Usuários</router-link
            >
          </li>
          <li class="breadcrumb-item" aria-current="page">Cadastrar Usuário</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card border shadow-none py-2">
        <div class="card-body">
          <form @submit.prevent="handlerStore">
            <div class="row mb-3">
              <label for="name" class="col-sm-2 col-form-label text-md-end">Nome</label>
              <div class="col-md-4">
                <input
                  v-model="state.data.name"
                  type="text"
                  class="form-control"
                  id="name"
                  placeholder="Nome do usuário"
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
                  placeholder="Email"
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
                  placeholder="Senha"
                />
                <small class="text-danger" v-if="v$.data.password.$error">
                  {{ v$.data.password.$errors[0].$message }}
                </small>
              </div>
            </div>
            <div class="row mb-3">
              <label for="name" class="col-sm-2 col-form-label text-md-end">Papéis</label>
              <div class="col-md-10">
                <div class="row mt-2">
                  <div class="col-md-3 mb-3" v-for="role in state.roles" :key="role.id">
                    <div class="form-check form-switch_">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        :checked="roleSelected(role.id)"
                        @change="toggleRole(role.id)"
                        :id="'role-' + role.id"
                      />
                      <label class="custom-control-label" :for="'role-' + role.id">
                        {{ role.name }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-sm-md offset-sm-2">
                <div class="form-check">
                  <input
                    v-model="state.data.active"
                    class="form-check-input"
                    type="checkbox"
                    id="active"
                  />
                  <label class="form-check-label" for="active"> Ativo </label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-10 offset-sm-2">
                <button
                  type="submit"
                  class="btn btn-primary me-2"
                  :disabled="state.creating"
                >
                  <span v-if="state.creating">Enviando...</span>
                  <span v-else>Cadastrar</span>
                </button>
                <router-link :to="{ name: 'users.index' }" class="btn">
                  Cancelar
                </router-link>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
