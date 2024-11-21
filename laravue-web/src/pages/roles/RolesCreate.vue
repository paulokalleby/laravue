<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import useVuelidate from "@vuelidate/core";
import { roleRules } from "@/validations/role.rules";
import http from "@/http";

onBeforeMount(() => (document.title = "Cadastrar Papel"));

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

const v$ = useVuelidate(roleRules, state);

const fetchResources = async () => {
  try {
    const response = await http.get("/resources");
    state.resources = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar recursos");
  }
};

const handlerStore = async () => {
  if (!(await v$.value.$validate())) return;
  state.creating = true;
  try {
    const response = await http.post("/roles", state.data);
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

const toggleSelectAll = (selectAll) => {
  if (selectAll) {
    state.data.permissions = state.resources.flatMap((resource) =>
      resource.permissions.map((permission) => permission.id)
    );
  } else {
    state.data.permissions = [];
  }
};

const clean = () => {
  state.data = {
    name: "",
    permissions: [],
    active: true,
  };
  v$.value.$reset();
};
</script>

<template>
  <div class="row mb-2">
    <div class="col-12">
      <div class="d-flex flex-row justify-content-between align-items-center">
        <h4 class="fw-semibold">Cadastrar Papel</h4>
        <router-link
          :to="{ name: 'roles.index' }"
          v-can="'roles.index'"
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
              :to="{ name: 'roles.index' }"
              >papeis</router-link
            >
          </li>
          <li class="breadcrumb-item" aria-current="page">cadastrar papel</li>
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
                  <div class="col-12">
                    <div class="form-check form-switch mb-3">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        id="select-all"
                        :checked="
                          state.data.permissions.length ===
                          state.resources.flatMap((resource) => resource.permissions)
                            .length
                        "
                        @change="toggleSelectAll($event.target.checked)"
                      />
                      <label class="form-check-label" for="select-all">
                        Selecionar Todos
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row">
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
                  :disabled="state.creating"
                >
                  <span v-if="state.creating">Enviando...</span>
                  <span v-else>Cadastrar</span>
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
