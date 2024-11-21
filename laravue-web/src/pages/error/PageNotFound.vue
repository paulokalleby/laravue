<script setup>
import { onBeforeMount } from "vue";

onBeforeMount(() => {
  document.title = "Erro 404";
});
</script>

<template>
  <div id="main-wrapper">
    <div
      class="position-relative overflow-hidden min-vh-100 w-100 d-flex align-items-center justify-content-center"
    >
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-lg-4">
            <div class="text-center">
              <img
                src="./../../assets/img/errorimg.svg"
                alt="modernize-img"
                class="img-fluid"
                width="500"
              />
              <h1 class="fw-semibold mb-7 fs-9">Opps!!!</h1>
              <h4 class="fw-semibold mb-7">
                Esta página que você procura não foi encontrada.
              </h4>
              <router-link :to="{ name: 'home.index' }" class="btn btn-primary">
                Voltar para a Home
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="dark-transparent sidebartoggler"></div>
</template>
