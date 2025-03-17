<template>
    <div class="form-input mb-4">
      <label :for="inputId" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ label }}</label>
      <input
        :id="inputId"
        v-model="inputValue"
        :type="type"
        :placeholder="placeholder"
        :class="[
          'bg-gray-50 border-gray-200 text-gray-700 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 placeholder:text-xs dark:placeholder-black-900 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
          { '': disabled }
        ]"
        @input="$emit('update:modelValue', inputValue)"
        :disabled="disabled"
        :required="required"
        :aria-label="disabled ? 'disabled input' : undefined"
      />
    </div>
  </template>

  <script lang="ts" setup>
  import { ref, defineProps, defineEmits, watch } from 'vue';

  const props = defineProps<{
    inputId:string;
    label: string;
    modelValue: string;
    type?: string;
    placeholder?: string;
    disabled?: boolean;
    required?: boolean;
  }>();

  const emits = defineEmits(['update:modelValue']);

  const inputValue = ref(props.modelValue);

  watch(
    () => props.modelValue,
    (newValue) => {
      inputValue.value = newValue;
    }
  );
  </script>

  <style scoped>

  </style>
