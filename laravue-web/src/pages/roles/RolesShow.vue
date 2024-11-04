<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import RoleService from "@/services/role.service";
import PreLoader from "@/components/PreLoader.vue";

onBeforeMount(() => {
  document.title = "Detalhes do Papel";
});

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
    const response = await RoleService.findById(props.id);
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
    <div class="col-12 mb-3">
      <h4 class="fw-semibold mb-8">Detalhes do Papel</h4>
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
          <li class="breadcrumb-item" aria-current="page">Detalhes do Papel</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card border shadow-none px-md-3">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-md-end col-md-3">UUID:</label>
                <div class="col-md-9">
                  <p>{{ state.data.id }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-md-end col-md-3">Nome:</label>
                <div class="col-md-9">
                  <p>{{ state.data.name }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-md-end col-md-3">Atualizado:</label>
                <div class="col-md-9">
                  <p>{{ state.data.updated }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-md-end col-md-3">Status:</label>
                <div class="col-md-9">
                  <p>{{ state.data.active ? "Ativo" : "Inativo" }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-md-end col-md-3"></label>
                <div class="col-md-9">
                  <router-link
                    :to="{ name: 'roles.index' }"
                    class="btn btn-outline-primary"
                  >
                    Voltar
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
