<script setup>
import { onBeforeMount } from "vue";

onBeforeMount(() => {
  document.title = "Home";
});
</script>

<template>
  <div class="row mb-2">
    <div class="col-12">
      <div class="d-flex flex-row justify-content-between align-items-center">
        <h4 class="fw-semibold">Home</h4>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card bg-white overflow-hidden border shadow-none mt-3">
        <div class="card-body py-3">
          <div class="row justify-content-between align-items-center">
            <div class="col-sm-6">
              <h3 class="fw-semibold mb-9 fs-8">👋 Olá!</h3>
              <p class="mb-9">Essa é área administrativa</p>
              <!--<button class="btn btn-info">Sair</button>-->
            </div>
            <div class="col-sm-5">
              <div class="position-relative mb-n5 text-center">
                <img
                  src="./../../assets/img/track-bg.png"
                  alt="modernize-img"
                  class="img-fluid"
                  width="180"
                  height="230"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
