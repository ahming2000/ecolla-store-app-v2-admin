<template>
  <div class="container">
    <div v-if="isWholesaleDiscountAllowed">
      <edit-item-wholesale-discount
        v-for="wholesaleDiscount in wholesaleDiscounts"
        v-bind:key="wholesaleDiscount.id"
        :wholesale_discount="wholesaleDiscount"
        :original_price="originalPrice"
      ></edit-item-wholesale-discount>
    </div>
    <div v-else class="d-flex align-items-center justify-content-center text-center">
      <div class="py-5">
        <i class="icofont icofont-close-circled icofont-10x text-secondary" style="font-size: 120px;"></i>
        <p class="fs-5 text-secondary">此商品无法使用批发功能</p>
      </div>
    </div>
  </div>
</template>

<script>
import EditItemWholesaleDiscount from "./EditItemWholesaleDiscount.vue";
export default {
  components: { EditItemWholesaleDiscount },
  name: "edit-item-wholesale-discount-list",

  props: {
    is_wholesale_discount_allowed: Boolean,
    original_price: Number,
    wholesale_discounts: Array,
  },

  data() {
    return {
      isWholesaleDiscountAllowed: this.is_wholesale_discount_allowed,
      originalPrice: this.originalPrice ?? null,
      wholesaleDiscounts: this.wholesale_discounts ?? [],
    };
  },

  computed: {
    classObject() {
      return {};
    },
  },

  methods: {},
};
</script>