<template>
  <v-dialog :value="value" :max-width="maxWidth" @click:outside="() => $emit('cancel')">
    <v-card>
      <v-card-title class="title">{{ title }}</v-card-title>
      <v-card-text v-if="subtitle">{{ subtitle }}</v-card-text>

      <slot />

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text @click="() => $emit('cancel')">Cancelar</v-btn>
        <v-btn color="primary" text @click="() => $emit('confirm')">{{ okText }}</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: {
    value: {
      type: Boolean
    },
    title: {
      type: String,
      required: true
    },
    subtitle: {
      type: String,
      required: false
    },
    maxWidth: {
      type: Number,
      default: 320
    },
    okText: {
      type: String,
      default: "Aceptar"
    }
  }
};
</script>
