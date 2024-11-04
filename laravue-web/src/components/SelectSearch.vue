<template>
  <div class="position-relative">
    <input
      type="search"
      class="form-control"
      :placeholder="placeholder"
      v-model="searchQuery"
      @focus="showOptions = true"
      @input="filterOptions"
    />
    <ul v-if="showOptions" class="list-group position-absolute w-100 shadow border">
      <li
        v-for="option in filteredOptions"
        :key="option[valueKey]"
        class="list-group-item list-group-item-action text-dark"
        @click="selectOption(option)"
      >
        {{ option[labelKey] }}
      </li>
      <li
        v-if="!filteredOptions.length"
        class="list-group-item list-group-item-action text-muted"
      >
        Nenhum resultado encontrado
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";

const props = defineProps({
  options: {
    type: Array,
    default: () => [],
  },
  modelValue: {
    type: [String, Number],
    default: "",
  },
  valueKey: {
    type: String,
    default: "value",
  },
  labelKey: {
    type: String,
    default: "label",
  },
  placeholder: {
    type: String,
    default: "Buscar...",
  },
});

const emit = defineEmits(["update:modelValue"]);

const searchQuery = ref("");
const showOptions = ref(false);

const selectOption = (option) => {
  emit("update:modelValue", option[props.valueKey]);
  showOptions.value = false;
  searchQuery.value = option[props.labelKey];
};

const filteredOptions = computed(() => {
  return props.options.filter((option) =>
    option[props.labelKey].toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

watch(
  () => props.modelValue,
  (newValue) => {
    const selectedOption = props.options.find(
      (option) => option[props.valueKey] === newValue
    );
    if (selectedOption) {
      searchQuery.value = selectedOption[props.labelKey];
    }
  },
  { immediate: true }
);

const handleClickOutside = (event) => {
  if (!event.target.closest(".position-relative")) {
    showOptions.value = false;
  }
};

watch(showOptions, (value) => {
  if (value) {
    document.addEventListener("click", handleClickOutside);
  } else {
    document.removeEventListener("click", handleClickOutside);
  }
});
</script>

<style scoped>
.list-group-item-action {
  z-index: 999;
  background-color: #fff;
}
.list-group-item-action:hover {
  background-color: #f1f1f1;
}
</style>
