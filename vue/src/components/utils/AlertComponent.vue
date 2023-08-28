<template>
  <div :class="type" role="" v-if="show">
    <div style="float: right; margin-top: -6px;">
      <button type="button" class="btn" @click="close()">X</button>
    </div>
    <h3>
      {{ message }}
    </h3>
  </div>
</template>

<script type="text/javascript">

export default {
  props: {
    message: String,
    show: Boolean,
    type: String,
  },
  methods: {
    close() {
      this.$emit('close');
    }
  }
};
</script>