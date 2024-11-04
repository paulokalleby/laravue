<script setup>
import { reactive, onBeforeMount, onMounted } from "vue";
import { useToast } from "vue-toastification";
import UserService from "@/services/user.service";
import PreLoader from "@/components/PreLoader.vue";

onBeforeMount(() => {
  document.title = "Detalhes do Usuário";
});

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
    const response = await UserService.findById(props.id);
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
    <div class="col-12 mb-3">
      <h4 class="fw-semibold mb-8">Detalhes do Usuário</h4>
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
          <li class="breadcrumb-item" aria-current="page">Detalhes do Usuário</li>
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
                <label class="form-label text-md-end col-md-3">Email:</label>
                <div class="col-md-9">
                  <p>{{ state.data.email }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-md-end col-md-3">Sessão:</label>
                <div class="col-md-9">
                  <p>{{ state.data.session }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="form-label text-md-end col-md-3">Papéis:</label>
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
                    :to="{ name: 'users.index' }"
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
