<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import PreLoader from "@/components/PreLoader.vue";
import http from "@/http";

onBeforeMount(() => (document.title = "Detalhes do Usuário"));

onMounted(async () => {
  await fetchUser();
});

const toast = useToast();
const props = defineProps({ id: String });

const state = reactive({
  data: {
    name: "",
    email: "",
    roles: [],
    active: false,
  },
  roles: [],
  loading: false,
});

const fetchUser = async () => {
  state.loading = true;
  try {
    const response = await http.get("/users/" + props.id);
    state.data = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar usuário");
  } finally {
    state.loading = false;
  }
};
</script>

<template>
  <PreLoader :show="state.loading" />

  <div class="row mb-2">
    <div class="col-12">
      <div class="d-flex flex-row justify-content-between align-items-center">
        <h4 class="fw-semibold">Detalhes do Usuário</h4>
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
          <li class="breadcrumb-item" aria-current="page">detalhes do usuario</li>
        </ol>
      </nav>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card border shadow-none px-md-2">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label col-md-3">Nome:</label>
                <div class="col-md-9">
                  <p>{{ state.data.name }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label col-md-3">Email:</label>
                <div class="col-md-9">
                  <p>{{ state.data.email }}</p>
                </div>
              </div>
            </div>
          </div>
          <div v-if="state.data.roles.length > 0" class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label col-md-3">Papéis:</label>
                <div class="col-md-9">
                  <p>
                    <span
                      v-for="role in state.data.roles"
                      :key="role.id"
                      class="badge bg-secondary-subtle border border-secondary-subtle text-secondary-emphasis rounded-pill mb-1"
                    >
                      {{ role.name }}
                    </span>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label col-md-3">Atualizado:</label>
                <div class="col-md-9">
                  <p>{{ state.data.updated }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label col-md-3">Status:</label>
                <div class="col-md-9">
                  <p>{{ state.data.active ? "Ativo" : "Inativo" }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
