<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import PreLoader from "@/components/PreLoader.vue";
import useVuelidate from "@vuelidate/core";
import { userUpdateRules } from "@/validations/user.rules";
import http from "@/http";

onBeforeMount(() => (document.title = "Editar Usuário"));

onMounted(async () => {
  await fetchRoles();
  await fetchUser();
});

const toast = useToast();
const router = useRouter();
const props = defineProps({ id: String });

const state = reactive({
  data: {
    name: "",
    email: "",
    password: "",
    roles: [],
    active: false,
  },
  roles: [],
  loading: false,
  updating: false,
});

const v$ = useVuelidate(userUpdateRules, state);

const fetchRoles = async () => {
  try {
    const response = await http.get("/roles?active=1");
    state.roles = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar papéis");
  }
};

const fetchUser = async () => {
  state.loading = true;
  try {
    const response = await http.get("/users/" + props.id);
    state.data = response.data.data;
    state.data.roles = response.data.data.roles.map((role) => role.id);
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar usuário");
  } finally {
    state.loading = false;
  }
};

const handlerUpdate = async () => {
  console.log(state.data);
  if (!(await v$.value.$validate())) return;
  state.updating = true;
  try {
    await http.put("/users/" + props.id, state.data);
    toast.success("Usuário atualizado com sucesso!");
    router.push({ name: "users.index" });
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao atualizar usuário");
  } finally {
    state.updating = false;
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

const clean = async () => {
  await fetchUser();
  v$.value.$reset();
  state.data.password = "";
};
</script>

<template>
  <PreLoader :show="state.loading" />

  <div class="row mb-2">
    <div class="col-12">
      <div class="d-flex flex-row justify-content-between align-items-center">
        <h4 class="fw-semibold">Editar Usuário</h4>
        <router-link
          :to="{ name: 'users.index' }"
          v-can="'users.index'"
          class="btn btn-primary float-end"
        >
          <i class="fal fa-long-arrow-left"></i> Voltar
        </router-link>
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
          <li class="breadcrumb-item">
            <router-link
              class="text-muted text-decoration-none"
              :to="{ name: 'users.index' }"
              >usuarios</router-link
            >
          </li>
          <li class="breadcrumb-item" aria-current="page">editar usuario</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card border shadow-none py-2">
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
            <div v-if="state.roles.length > 0" class="row mb-3">
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
              <div class="col-sm-md offset-md-2">
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
              <div class="col-sm-10 offset-md-2">
                <button
                  type="submit"
                  class="btn btn-primary me-2"
                  :disabled="state.updating"
                >
                  <span v-if="state.updating">Enviando...</span>
                  <span v-else>Atualizar</span>
                </button>
                <button
                  type="button"
                  @click.prevent="clean"
                  class="btn bg-primary-subtle text-primary"
                >
                  Cancelar
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
