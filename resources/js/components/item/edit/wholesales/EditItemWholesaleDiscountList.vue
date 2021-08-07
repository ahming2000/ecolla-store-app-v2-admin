<template>
  <div>
    <div class="container">
      <div v-if="isWholesaleDiscountAllowed">
        <edit-item-wholesale-discount
          v-for="wholesaleDiscount in wholesaleDiscounts"
          v-bind:key="wholesaleDiscount.id"
          :wholesale_discount="wholesaleDiscount"
          :original_price="originalPrice"
          @onEdit="onEdit($event)"
          @onDelete="onDelete($event)"
        ></edit-item-wholesale-discount>
      </div>
      <div
        v-else
        class="d-flex align-items-center justify-content-center text-center"
      >
        <div class="py-5">
          <i
            class="icofont icofont-close-circled icofont-10x text-secondary"
            style="font-size: 120px"
          ></i>
          <p class="fs-5 text-secondary">此商品无法使用批发功能</p>
        </div>
      </div>
    </div>

    <!-- Add/Edit/Delete Modal -->
    <!-- TODO -->
  </div>
</template>

<script>
import EditItemWholesaleDiscount from "./EditItemWholesaleDiscount.vue";

const fakeWholesaleDiscounts = [
  {
    id: 1,
    rate: 0.2,
    min: 10,
    max: 19,
    step: 10,
  },
  {
    id: 2,
    rate: 0.3,
    min: 20,
    max: 29,
    step: 10,
  },
  {
    id: 3,
    rate: 0.4,
    min: 30,
    max: null,
    step: 10,
  },
];

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
      originalPrice: this.original_price ?? null,
      // TODO remove mock data
      wholesaleDiscounts: (!this.wholesale_discounts || this.wholesale_discounts?.length === 0) ? fakeWholesaleDiscounts : this.wholesale_discounts,

      selectedWholesaleDiscount: null,
    };
  },

  watch: {
    is_wholesale_discount_allowed: function (val) {
      this.isWholesaleDiscountAllowed = val;
    },
  },

  methods: {
    saveAdd(newWholesaleDiscount) {},

    saveEdit(wholesaleDiscount) {
      this.selectedWholesaleDiscount = wholesaleDiscount;
    },

    onEdit(wholesaleDiscount) {},

    onDelete(wholesaleDiscount) {
      this.selectedWholesaleDiscount = wholesaleDiscount;
    },

    confirmDelete(wholesaleDiscount) {
      this.selectedWholesaleDiscount = wholesaleDiscount;
    },

    onResponse(...args) {
      this.$emit("onResponse", ...args);
    },
  },
};
</script>