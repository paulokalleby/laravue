<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import PreLoader from "@/components/PreLoader.vue";
import http from "@/http";

onBeforeMount(() => (document.title = "Detalhes do Papel"));

onMounted(async () => {
  await fetchRole();
});

const toast = useToast();
const props = defineProps({ id: String });

const state = reactive({
  data: {
    name: "",
    permissions: [],
    active: false,
  },
  updating: false,
});

const fetchRole = async () => {
  state.loading = true;
  try {
    const response = await http.get("/roles/" + props.id);
    state.data = response.data.data;
  } catch (error) {
    toast.error(error.response?.data?.message || "Erro ao buscar papel");
  } finally {
    state.loading = false;
  }
};
</script>

<template>
  <PreLoader :show="state.loading" />

  <div class="row mb-2">
    <div class="d-flex flex-row justify-content-between align-items-center">
      <h4 class="fw-semibold">Detalhes do Papel</h4>
      <router-link
        :to="{ name: 'roles.index' }"
        v-can="'roles.index'"
        class="btn btn-primary float-end"
      >
        <i class="fal fa-long-arrow-left"></i> Voltar
      </router-link>
    </div>
    <div class="col-12">
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
          <li class="breadcrumb-item" aria-current="page">detalhes do papel</li>
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
