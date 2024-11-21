<script setup>
import { onMounted, ref } from "vue";
import { RouterView } from "vue-router";

onMounted(() => {
  document.documentElement.setAttribute("data-bs-theme", theme.value);
});

const theme = ref(localStorage.getItem("theme") || "light");
</script>

<template>
  <div id="main-wrapper" class="auth-customizer-none">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100">
      <div class="position-relative z-index-5">
        <div class="row">
          <div class="col-xl-7 col-xxl-8">
            <h1 class="text-nowrap logo-img d-block px-4 py-9 w-100 fs-8">
              <b>Laravue</b>
            </h1>
            <div class="d-none d-xl-flex align-items-center justify-content-center h-n80">
              <img
                src="./../assets/img/login-security.svg"
                alt="modernize-img"
                class="img-fluid"
                width="500"
              />
            </div>
          </div>
          <div class="col-xl-5 col-xxl-4">
            <div
              class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4"
            >
              <RouterView />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
