<template>
  <v-flex>
    <v-btn icon :disabled="value <= 0" @click.native="() => onDecrement()">
      <v-icon>mdi-arrow-down-drop-circle</v-icon>
    </v-btn>
    <span>{{ value }}</span>
    <v-btn icon @click.native="() => onIncrement()">
      <v-icon>mdi-arrow-up-drop-circle</v-icon>
    </v-btn>
  </v-flex>
</template>

<script>
export default {
  props: {
    value: {
      type: Number,
      required: true
    }
  },
  methods: {
    onIncrement() {
      this.$emit("input", this.value + 1);
    },
    onDecrement() {
      if (this.value <= 0) return;
      this.$emit("input", this.value - 1);
    }
  }
};
</script>
