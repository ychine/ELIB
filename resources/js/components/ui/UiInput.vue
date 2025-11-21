<template>
  <div class="ui-input-wrapper">
    <input
      :id="inputId"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :required="required"
      :class="[
        'ui-input',
        {
          'ui-input--error': hasError,
          'ui-input--disabled': disabled,
        }
      ]"
      @input="handleInput"
      @blur="handleBlur"
      @focus="handleFocus"
    />
    <div v-if="hasError && errorMessage" class="ui-input__error">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: '',
  },
  type: {
    type: String,
    default: 'text',
  },
  placeholder: {
    type: String,
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  required: {
    type: Boolean,
    default: false,
  },
  error: {
    type: String,
    default: '',
  },
  inputId: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['update:modelValue', 'blur', 'focus']);

const isFocused = ref(false);
const hasError = computed(() => !!props.error);
const errorMessage = computed(() => props.error);

const handleInput = (event) => {
  emit('update:modelValue', event.target.value);
};

const handleBlur = (event) => {
  isFocused.value = false;
  emit('blur', event);
};

const handleFocus = (event) => {
  isFocused.value = true;
  emit('focus', event);
};
</script>

<style scoped>
.ui-input-wrapper {
  width: 100%;
}

.ui-input {
  width: 100%;
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  line-height: 1.5;
  color: #1f2937;
  background: #ffffff;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  font-family: 'Inter', sans-serif;
}

.ui-input::placeholder {
  color: #9ca3af;
}

.ui-input:focus {
  outline: none;
  border-color: #22c55e;
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

.ui-input--error {
  border-color: #ef4444;
}

.ui-input--error:focus {
  border-color: #ef4444;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.ui-input--disabled {
  background: #f3f4f6;
  color: #9ca3af;
  cursor: not-allowed;
}

.ui-input--disabled:focus {
  border-color: #d1d5db;
  box-shadow: none;
}

.ui-input__error {
  margin-top: 0.25rem;
  font-size: 0.75rem;
  color: #ef4444;
}
</style>




