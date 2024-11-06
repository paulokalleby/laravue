<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import RoleService from "@/services/role.service";
import ResourceService from "@/services/resource.service";

import useVuelidate from "@vuelidate/core";
import rolesStoreRules from "@/validations/roles/roles-store.rules";

onBeforeMount(() => {
  document.title = "Cadastrar Papel";
});

onMounted(async () => {
  await fetchResources();
});

const toast = useToast();

const router = useRouter();

const state = reactive({
  data: {
    name: "",
    permissions: [],
    active: true,
  },
  resources: [],
  creating: false,
});

const v$ = useVuelidate(rolesStoreRules, state);

const fetchResources = async () => {
  try {
    const response = await ResourceService.getAll();
    state.resources = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar recursos");
  }
};

const handlerStore = async () => {
  if (!(await v$.value.$validate())) return;
  state.creating = true;
  try {
    const response = await RoleService.create(state.data);
    toast.success("Papel cadastrada com sucesso!");
    router.push({ name: "roles.index" });
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao criar papel");
  } finally {
    state.creating = false;
  }
};

const togglePermission = (permissionId) => {
  const index = state.data.permissions.indexOf(permissionId);
  if (index === -1) {
    state.data.permissions.push(permissionId);
  } else {
    state.data.permissions.splice(index, 1);
  }
};

const permissionSelected = (permissionId) => {
  return state.data.permissions.includes(permissionId);
};
</script>

<template>
  <div class="row mb-2">
    <div class="col-12 mb-3">
      <h4 class="fw-semibold mb-8">Cadastrar Papel</h4>
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
              :to="{ name: 'roles.index' }"
              >Papéis</router-link
            >
          </li>
          <li class="breadcrumb-item" aria-current="page">Cadastrar Papel</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card border shadow-none py-3">
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
                  placeholder="Nome da papel"
                />
                <small class="text-danger" v-if="v$.data.name.$error">
                  {{ v$.data.name.$errors[0].$message }}
                </small>
              </div>
            </div>
            <div class="row mb-3">
              <label for="name" class="col-sm-2 col-form-label text-md-end"
                >Permissões</label
              >
              <div class="col-md-10">
                <div class="row mt-2">
                  <div
                    class="col-md-2 mb-3"
                    v-for="resource in state.resources"
                    :key="resource.id"
                  >
                    <h6 class="mb-2">{{ resource.name }}</h6>

                    <div
                      class="form-check form-switch_"
                      v-for="permission in resource.permissions"
                      :key="permission.id"
                    >
                      <input
                        class="form-check-input"
                        type="checkbox"
                        :checked="permissionSelected(permission.id)"
                        @change="togglePermission(permission.id)"
                        :id="'permission-' + permission.id"
                      />
                      <label
                        class="custom-control-label"
                        :for="'permission-' + permission.id"
                      >
                        {{ permission.name }}
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
                <router-link :to="{ name: 'roles.index' }" class="btn">
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
