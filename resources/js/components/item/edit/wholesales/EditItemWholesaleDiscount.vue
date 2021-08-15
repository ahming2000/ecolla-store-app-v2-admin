<template>
  <div class="container">
    {{ wholesale_discount.id }}
  </div>
</template>

<script>
export default {
  name: "edit-item-wholesale-discount",

  props: {
    original_price: Number,
    wholesale_discount: Object,
  },

  data() {
    return {};
  },

  computed: {
    classObject() {
      return {};
    },
  },

  methods: {},
};
</script>