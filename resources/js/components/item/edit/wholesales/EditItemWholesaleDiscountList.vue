<template>
  <div class="container">
    <edit-item-wholesale-discount
      v-for="wholesale_discount in wholesale_discounts"
      v-bind:key="wholesale_discount.id"
      :wholesale_discount="wholesale_discount"
      :original_price="original_price"
    ></edit-item-wholesale-discount>
  </div>
</template>

<script>
import EditItemWholesaleDiscount from "./EditItemWholesaleDiscount.vue";
export default {
  components: { EditItemWholesaleDiscount },
  name: "edit-item-wholesale-discount-list",

  props: {
    orignal_price: Number,
    wholesale_discounts: Array,
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