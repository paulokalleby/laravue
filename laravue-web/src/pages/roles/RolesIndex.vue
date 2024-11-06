<script setup>
import { reactive, onMounted, onBeforeMount, ref } from "vue";
import PreLoader from "@/components/PreLoader.vue";
import { Bootstrap5Pagination } from "laravel-vue-pagination";
import _ from "lodash";

import RoleService from "@/services/role.service";

import ConfirmDeleteModal from "@/components/ConfirmDeletModal.vue";
import { useToast } from "vue-toastification";

onBeforeMount(() => {
  document.title = "Pepéis";
});

onMounted(async () => {
  await fetchRoles();
});

const toast = useToast();
const itemIdToDelete = ref(null);
const deleteModal = ref(null);

const state = reactive({
  roles: {
    data: {},
  },
  search: {
    name: "",
    status: "",
  },
  status: [
    {
      label: "Ativos",
      value: 1,
    },
    {
      label: "Inativos",
      value: 0,
    },
  ],
  loading: false,
});

const doResearch = _.debounce(async () => {
  fetchRoles();
}, 500);

const clearSearch = () => {
  state.search = {
    name: "",
    status: "",
  };
  fetchRoles();
};

const showDeleteModal = (id) => {
  itemIdToDelete.value = id;
  deleteModal.value.openModal();
};

const handlerDelete = async (id) => {
  console.log(id);
  try {
    const response = await RoleService.delete(id);
    clearSearch();
    toast.success("Registro excluido com sucesso!");
  } catch (error) {
    console.log(error);
    toast.error("Erro ao excluir registro");
  }
};

const fetchRoles = async (page = 1) => {
  state.loading = true;
  try {
    const response = await RoleService.find(page, state.search);
    state.roles = response.data;
  } catch (error) {
    toast.error("Erro ao buscar registros");
  } finally {
    state.loading = false;
  }
};
</script>

<template>
  <PreLoader :show="state.loading" />

  <ConfirmDeleteModal
    :itemId="itemIdToDelete"
    ref="deleteModal"
    @confirm-delete="handlerDelete"
  />

  <div class="row mb-2">
    <div class="col-12 mb-3">
      <h4 class="fw-semibold mb-8">Papéis</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link
              class="text-muted text-decoration-none"
              :to="{ name: 'home.index' }"
              >Home</router-link
            >
          </li>
          <li class="breadcrumb-item" aria-current="page">Papéis</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row mb-2">
    <div class="col-md-3 col-12">
      <input
        class="form-control py-2 mb-3"
        type="text"
        placeholder="Pesquisar"
        v-model="state.search.name"
        @keyup="doResearch"
      />
    </div>
    <div class="col-md-2">
      <select
        class="form-select py-2 mb-3"
        aria-label="status"
        v-model="state.search.status"
        @change="doResearch"
      >
        <option selected value="">Status</option>
        <option v-for="status in state.status" :value="status.value" :key="status.value">
          {{ status.label }}
        </option>
      </select>
    </div>
    <div class="col-md-7">
      <button type="button" class="btn py-2" @click.prevent="clearSearch">
        <i class="fal fa-sync"></i>
      </button>
      <router-link
        :to="{ name: 'roles.create' }"
        v-can="'roles.store'"
        class="btn btn-primary py-2 float-end"
      >
        <i class="fal fa-plus"></i> Novo
      </router-link>
    </div>
  </div>

  <div v-if="state.roles.data.length <= 0" class="row text-center">
    <div class="col-12 py-5">
      <p>Nenhum registro encontrado</p>
    </div>
  </div>

  <div v-else class="row mb-3">
    <div class="col-12">
      <div class="table-responsive mb-4 border rounded-1">
        <table class="table text-nowrap mb-0 align-middle">
          <thead class="text-dark fs-4">
            <tr>
              <th scope="col">Nome</th>
              <th scope="col">Atualizado</th>
              <th scope="col">Status</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="role in state.roles.data" :key="role.id">
              <td class="py-3">{{ role.name }}</td>
              <td>{{ role.updated }}</td>
              <td>
                <span
                  v-if="role.active"
                  class="badge bg-success-subtle border border-success-subtle text-success-emphasis rounded-pill text-sm-end"
                >
                  Ativo
                </span>
                <span
                  v-else
                  class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill text-sm-end"
                >
                  Inativo
                </span>
              </td>
              <td>
                <a
                  href="#"
                  v-can="'roles.destroy'"
                  class="float-end btn btn-outline-primary btn-sm"
                  @click.prevent="showDeleteModal(role.id)"
                  >Excluir</a
                >
                <router-link
                  :to="{ name: 'roles.edit', params: { id: role.id } }"
                  v-can="'roles.update'"
                  class="float-end me-2 btn btn-outline-primary btn-sm"
                >
                  Editar
                </router-link>
                <router-link
                  :to="{ name: 'roles.show', params: { id: role.id } }"
                  v-can="'roles.show'"
                  class="float-end me-2 btn btn-outline-primary btn-sm"
                >
                  Detahes
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <Bootstrap5Pagination
    :data="state.roles"
    :limit="3"
    @pagination-change-page="fetchRoles"
    size="small"
    align="center"
  />
</template>
