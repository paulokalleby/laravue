<script setup>
import { onBeforeUnmount } from "vue";

onBeforeUnmount(() => {
  document.title = "Home";
});
</script>

<template>
  <div class="row">
    <div class="col-12">
      <div class="card bg-info-subtle overflow-hidden shadow-none">
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
