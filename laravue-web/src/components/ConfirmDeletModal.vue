<script setup>
import { ref } from "vue";

const props = defineProps({
  itemId: {
    type: String,
    required: false,
    default: null,
  },
});
const emit = defineEmits(["confirm-delete"]);

const isModalOpen = ref(false);

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const confirmDelete = () => {
  if (props.itemId !== null) {
    emit("confirm-delete", props.itemId);
    closeModal();
  }
};

defineExpose({ openModal });
</script>

<template>
  <div
    v-show="isModalOpen"
    class="modal fade show"
    tabindex="-1"
    role="dialog"
    aria-labelledby="confirmDeleteModalLabel"
    @click.self="closeModal"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
          <button
            type="button"
            class="btn-close"
            aria-label="Close"
            @click="closeModal"
          ></button>
        </div>
        <div class="modal-body">Deseja excluir o registro?</div>
        <div class="modal-footer border-0 justify-content-start">
          <button type="button" class="btn btn-danger" @click="confirmDelete">
            Confirmar
          </button>
          <button
            type="button"
            class="btn bg-danger-subtle text-danger"
            @click="closeModal"
          >
            Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal {
  display: none;
}
.modal.show {
  display: block;
  background-color: rgba(0, 0, 0, 0.5);
}
.modal-dialog {
  margin: 1.75rem auto;
  max-width: 500px;
}
</style>
