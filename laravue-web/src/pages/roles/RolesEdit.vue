<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import RoleService from "@/services/role.service";
import PreLoader from "@/components/PreLoader.vue";
import useVuelidate from "@vuelidate/core";
import rolesUpdateRules from "@/validations/roles/roles-update.rules";
import ResourceService from "@/services/resource.service";

onBeforeMount(() => {
  document.title = "Editar Papel";
});

onMounted(async () => {
  await fetchResources();
  await fetchRole();
});

const toast = useToast();
const router = useRouter();
const props = defineProps({ id: String });

const state = reactive({
  data: {
    name: "",
    permissions: [],
    active: false,
  },
  resources: [],
  loading: false,
  updating: false,
});

const v$ = useVuelidate(rolesUpdateRules, state);

const fetchResources = async () => {
  try {
    const response = await ResourceService.getAll();
    state.resources = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar recursos");
  }
};

const fetchRole = async () => {
  state.loading = true;
  try {
    const response = await RoleService.findById(props.id);
    state.data.name = response.data.data.name;
    state.data.active = response.data.data.active;
    state.data.permissions = response.data.data.permissions.map(
      (permission) => permission.id
    );
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar papel");
  } finally {
    state.loading = false;
  }
};

const update = async () => {
  if (!(await v$.value.$validate())) return;
  state.updating = true;
  try {
    await RoleService.update(props.id, state.data);
    toast.success("Papel atualizado com sucesso!");
    router.push({ name: "roles.index" });
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao atualizar papel");
  } finally {
    state.updating = false;
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
  <PreLoader :show="state.loading" />

  <div class="row mb-2">
    <div class="col-12 mb-3">
      <h4 class="fw-semibold mb-8">Editar Papel</h4>
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
          <li class="breadcrumb-item" aria-current="page">Editar Papel</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card border shadow-none py-3">
        <div class="card-body">
          <form @submit.prevent="update">
            <div class="row mb-3">
              <label for="name" class="col-sm-2 col-form-label text-md-end">Nome</label>
              <div class="col-md-4">
                <input
                  v-model="state.data.name"
                  type="text"
                  class="form-control"
                  id="name"
                  placeholder="Nome da pepel"
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
                  :disabled="state.updating"
                >
                  <span v-if="state.updating">Enviando...</span>
                  <span v-else>Atualizar</span>
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
