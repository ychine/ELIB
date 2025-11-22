<template>
  <IonModal
    v-bind="$attrs"
    :is-open="modelValue"
    :backdrop-dismiss="backdropDismiss"
    :presenting-element="undefined"
    class="modern-ion-modal"
    mode="ios"
    @didDismiss="handleDismiss"
  >
    <div class="modern-ion-modal__surface" :class="`modern-ion-modal__surface--${size}`">
      <header class="modern-ion-modal__header">
        <div>
          <p v-if="eyebrow" class="modern-ion-modal__eyebrow">
            {{ eyebrow }}
          </p>
          <slot name="title">
            <h2 class="modern-ion-modal__title">
              {{ title }}
            </h2>
          </slot>
          <p v-if="subtitle" class="modern-ion-modal__subtitle">
            {{ subtitle }}
          </p>
        </div>
        <IonButton
          v-if="showClose"
          class="modern-ion-modal__close"
          fill="clear"
          size="small"
          @click="requestClose"
        >
          <IonIcon :icon="closeOutline" slot="icon-only" aria-label="Close modal" />
        </IonButton>
      </header>

      <section class="modern-ion-modal__content">
        <slot />
      </section>

      <footer v-if="showFooter" class="modern-ion-modal__footer">
        <slot name="actions">
          <IonButton
            v-if="secondaryAction"
            class="modern-ion-modal__secondary"
            color="medium"
            fill="clear"
            :disabled="secondaryAction.disabled"
            @click="onSecondaryClick"
          >
            {{ secondaryAction.label }}
          </IonButton>
          <IonButton
            v-if="primaryAction"
            class="modern-ion-modal__primary"
            :disabled="primaryAction.disabled"
            :color="primaryAction.color || 'primary'"
            expand="block"
            @click="onPrimaryClick"
          >
            <IonSpinner v-if="primaryAction.loading" name="crescent" slot="start" />
            {{ primaryAction.label }}
          </IonButton>
        </slot>
      </footer>
    </div>
  </IonModal>
</template>

<script setup>
import { IonButton, IonIcon, IonModal, IonSpinner } from '@ionic/vue';
import { closeOutline } from 'ionicons/icons';
import { computed, useSlots, watch } from 'vue';

defineOptions({
  inheritAttrs: false,
});

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: '',
  },
  subtitle: {
    type: String,
    default: '',
  },
  eyebrow: {
    type: String,
    default: '',
  },
  showClose: {
    type: Boolean,
    default: true,
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
  primaryAction: {
    type: Object,
    default: null,
  },
  secondaryAction: {
    type: Object,
    default: null,
  },
  backdropDismiss: {
    type: Boolean,
    default: true,
  },
  presentingElement: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['update:modelValue', 'primary', 'secondary', 'dismiss']);
const slots = useSlots();

const showFooter = computed(() => !!props.primaryAction || !!props.secondaryAction || !!slots.actions);

// Lock body when modal opens to prevent content push
watch(() => props.modelValue, (isOpen) => {
  if (isOpen) {
    document.body.classList.add('modal-open');
  } else {
    document.body.classList.remove('modal-open');
  }
});

const handleDismiss = (event) => {
  document.body.classList.remove('modal-open');
  emit('update:modelValue', false);
  emit('dismiss', event);
};

const requestClose = () => {
  emit('update:modelValue', false);
};

const onPrimaryClick = (event) => {
  emit('primary', event);
  props.primaryAction?.handler?.(event);
};

const onSecondaryClick = (event) => {
  emit('secondary', event);
  props.secondaryAction?.handler?.(event);
};
</script>

<style scoped>
.modern-ion-modal__surface {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  padding: clamp(1.5rem, 3vw, 2.25rem);
  border-radius: 1.5rem !important;
  background: rgba(255, 255, 255, 0.98) !important;
  backdrop-filter: blur(22px);
  z-index: 10001;
  position: relative;
  max-height: 90vh;
  overflow-y: auto;
  margin: auto;
  width: 100%;
  max-width: 720px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

/* COMPLETE OVERRIDE - Force modal to be centered overlay, NOT a sheet */
:deep(ion-modal.modern-ion-modal) {
  --width: 100% !important;
  --max-width: 720px !important;
  --height: auto !important;
  --max-height: 90vh !important;
  --border-radius: 1.5rem !important;
  --box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
  --backdrop-opacity: 0.5 !important;
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  bottom: 0 !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  z-index: 10000 !important;
  transform: none !important;
  margin: 0 !important;
  padding: 0 !important;
}

/* Prevent any sheet-like behavior */
:deep(ion-modal.modern-ion-modal::part(content)) {
  position: relative !important;
  top: auto !important;
  bottom: auto !important;
  left: auto !important;
  right: auto !important;
  transform: none !important;
  margin: auto !important;
  max-width: 720px !important;
  width: 100% !important;
  height: auto !important;
  max-height: 90vh !important;
  border-radius: 1.5rem !important;
}

:deep(ion-modal.modern-ion-modal .modal-wrapper) {
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  bottom: 0 !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 100% !important;
  height: 100% !important;
  pointer-events: none !important;
  transform: none !important;
  margin: 0 !important;
  padding: 0 !important;
}

:deep(ion-modal.modern-ion-modal .modal-wrapper > *:first-child) {
  pointer-events: auto !important;
  margin: auto !important;
  position: relative !important;
  transform: none !important;
  top: auto !important;
  bottom: auto !important;
}

/* Fix backdrop - uniform light overlay */
:deep(ion-modal.modern-ion-modal ion-backdrop) {
  background: rgba(0, 0, 0, 0.5) !important;
  opacity: 1 !important;
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  bottom: 0 !important;
  z-index: -1 !important;
}

/* Account settings specific - ALWAYS center, NO sheet behavior */
:deep(ion-modal.account-settings-overlay) {
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  bottom: 0 !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  transform: none !important;
  margin: 0 !important;
  padding: 0 !important;
}

:deep(ion-modal.account-settings-overlay::part(content)) {
  position: relative !important;
  top: auto !important;
  bottom: auto !important;
  transform: none !important;
  margin: auto !important;
}

:deep(ion-modal.account-settings-overlay .modal-wrapper) {
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  bottom: 0 !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  pointer-events: none !important;
  transform: none !important;
  margin: 0 !important;
  padding: 0 !important;
}

:deep(ion-modal.account-settings-overlay .modern-ion-modal__surface) {
  border-radius: 1.5rem !important;
  margin: auto !important;
  pointer-events: auto !important;
  position: relative !important;
  transform: none !important;
  top: auto !important;
  bottom: auto !important;
  left: auto !important;
  right: auto !important;
}

:deep(ion-modal.account-settings-overlay ion-backdrop) {
  background: rgba(0, 0, 0, 0.5) !important;
  opacity: 1 !important;
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  bottom: 0 !important;
  z-index: -1 !important;
}

.modern-ion-modal__surface--sm {
  width: min(420px, 92vw);
}

.modern-ion-modal__surface--md {
  width: min(520px, 94vw);
}

.modern-ion-modal__surface--lg {
  width: min(720px, 95vw);
}

.modern-ion-modal__header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 1.25rem;
}

.modern-ion-modal__eyebrow {
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.14em;
  color: rgba(15, 23, 42, 0.5);
  margin-bottom: 0.4rem;
}

.modern-ion-modal__title {
  font-size: clamp(1.25rem, 2vw, 1.65rem);
  margin: 0;
  color: #0f172a;
}

.modern-ion-modal__subtitle {
  margin: 0.35rem 0 0;
  color: rgba(15, 23, 42, 0.72);
  line-height: 1.4;
}

.modern-ion-modal__content {
  color: rgba(15, 23, 42, 0.85);
  font-size: 0.975rem;
  line-height: 1.6;
}

.modern-ion-modal__footer {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.modern-ion-modal__close::part(native) {
  border-radius: 999px;
  --padding-start: 0.25rem;
  --padding-end: 0.25rem;
  --color: rgba(15, 23, 42, 0.5);
}

.modern-ion-modal__primary::part(native) {
  border-radius: 999px;
  min-height: 48px;
  font-weight: 600;
  letter-spacing: 0.01em;
}

.modern-ion-modal__secondary::part(native) {
  border-radius: 999px;
  font-weight: 600;
  color: rgba(15, 23, 42, 0.7);
}

/* Force light mode for account settings modal */
.account-settings-modal .modern-ion-modal__surface {
  background: rgba(255, 255, 255, 0.98) !important;
  color: #0f172a !important;
}

.account-settings-modal .modern-ion-modal__title {
  color: #0f172a !important;
}

.account-settings-modal .modern-ion-modal__subtitle {
  color: rgba(15, 23, 42, 0.72) !important;
}

@media (prefers-color-scheme: dark) {
  .modern-ion-modal__surface:not(.account-settings-modal .modern-ion-modal__surface) {
    background: rgba(15, 23, 42, 0.9);
    color: #f4f5f7;
  }

  .modern-ion-modal__title:not(.account-settings-modal .modern-ion-modal__title) {
    color: #f4f5f7;
  }

  .modern-ion-modal__subtitle:not(.account-settings-modal .modern-ion-modal__subtitle) {
    color: rgba(244, 245, 247, 0.7);
  }
}
</style>

