<template>
  <div
    class="modern-ion-field"
    :class="[
      densityClass,
      {
        'modern-ion-field--focused': isFocused,
        'modern-ion-field--dirty': hasValue,
        'modern-ion-field--invalid': !!error,
        'modern-ion-field--disabled': disabled,
      },
    ]"
  >
    <IonItem
      lines="none"
      class="modern-ion-field__item"
      mode="ios"
      @click="focusInput"
    >
      <IonLabel class="modern-ion-field__label" position="floating">
        {{ label }}
        <span v-if="required" aria-hidden="true" class="modern-ion-field__required">*</span>
      </IonLabel>
      <IonInput
        ref="inputRef"
        class="modern-ion-field__input"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :inputmode="inputmode"
        :clear-input="clearInput"
        :disabled="disabled"
        :maxlength="maxlength"
        label-placement="floating"
        :enterkeyhint="enterKeyHint"
        autocapitalize="off"
        autocomplete="off"
        @ionInput="onInput"
        @ionBlur="handleBlur"
        @ionFocus="handleFocus"
      />
    </IonItem>
    <div class="modern-ion-field__meta">
      <p v-if="!error && helper" class="modern-ion-field__helper">
        {{ helper }}
      </p>
      <p v-if="error" class="modern-ion-field__error" role="status">
        {{ error }}
      </p>
      <div class="modern-ion-field__suffix">
        <slot name="suffix" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { IonInput, IonItem, IonLabel } from '@ionic/vue';
import { computed, ref } from 'vue';

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: '',
  },
  label: {
    type: String,
    required: true,
  },
  placeholder: {
    type: String,
    default: '',
  },
  helper: {
    type: String,
    default: '',
  },
  error: {
    type: String,
    default: '',
  },
  required: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: 'text',
  },
  inputmode: {
    type: String,
    default: undefined,
  },
  maxlength: {
    type: Number,
    default: undefined,
  },
  clearInput: {
    type: Boolean,
    default: false,
  },
  enterKeyHint: {
    type: String,
    default: undefined,
  },
  density: {
    type: String,
    default: 'comfortable',
    validator: (value) => ['compact', 'comfortable', 'spacious'].includes(value),
  },
});

const emit = defineEmits(['update:modelValue', 'focus', 'blur', 'change']);

const inputRef = ref();
const isFocused = ref(false);

const hasValue = computed(() => props.modelValue !== null && props.modelValue !== undefined && props.modelValue !== '');
const densityClass = computed(() => `modern-ion-field--${props.density}`);

const onInput = (event) => {
  emit('update:modelValue', event.detail?.value ?? '');
};

const handleFocus = (event) => {
  isFocused.value = true;
  emit('focus', event);
};

const handleBlur = (event) => {
  isFocused.value = false;
  emit('blur', event);
  emit('change', event.detail?.value ?? '');
};

const focusInput = () => {
  inputRef.value?.$el?.setFocus?.();
};
</script>

<style scoped>
.modern-ion-field {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  width: 100%;
}

.modern-ion-field__meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.5rem;
  min-height: 1.25rem;
}

.modern-ion-field__helper {
  font-size: 0.8125rem;
  color: rgba(15, 23, 42, 0.6);
  margin: 0;
}

.modern-ion-field__error {
  font-size: 0.8125rem;
  color: #e11d48;
  margin: 0;
  font-weight: 500;
}

.modern-ion-field__suffix {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  margin-left: auto;
}

.modern-ion-field--disabled {
  opacity: 0.7;
  pointer-events: none;
}
</style>

