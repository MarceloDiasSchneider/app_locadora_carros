<template>
  <div class="alert" :class="tipo" role="alert">
    {{ message }}
  </div>
</template>
<script>
export default {
  props: {
    tipo: {
      type: String,
      required: true,
    },
    message: {
      type: String,
      required: true,
    },
  },
};
</script>