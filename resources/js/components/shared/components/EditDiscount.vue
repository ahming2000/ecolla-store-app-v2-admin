<template>
  <div class="container mt-3">
    <div class="form-group">
      <label class="label" for="discountRateRange">折扣率</label>
      <input
        type="range"
        class="form-control-range"
        :max="originalPrice"
        min="0"
        step="0.01"
        v-model="discountPrice"
        id="discountRateRange"
      />
    </div>
    <div class="row">
      <div class="col-6 pr-0">
        <div class="form-group w-100">
          <div class="input-group">
            <span class="input-group-text" id="negativeCurrencyUnit">- RM</span>
            <input
              type="number"
              class="form-control"
              id="discountPrice"
              v-model="discountPrice"
            />
          </div>
        </div>
      </div>
      <div class="col-6 pl-0">
        <div class="form-group w-100">
          <div class="input-group">
            <span class="input-group-text" id="openBracket">(</span>
            <input
              type="number"
              class="form-control"
              id="discountRate"
              min="0"
              max="100"
              step="1"
              v-model="discountRatePercentage"
              v-on:change="onChange($event)"
            />
            <span class="input-group-text" id="percentage">%)</span>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group w-100">
      <label class="label" for="discountedPrice">折扣后的价钱</label>
      <div class="input-group">
        <span class="input-group-text" id="currencyUnit">RM</span>
        <input
          type="number"
          class="form-control"
          id="discountedPrice"
          v-model="discountedPrice"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "edit-discount",

  props: {
    original_price: Number,
    rate: Number,
  },

  data() {
    return {
      discountRate: this.rate ?? 0,
      originalPrice: this.original_price ?? 0,
    };
  },

  watch: {
    rate: function (val) {
      this.refetchDiscountRate(val);
    },
    original_price: function (val) {
      this.refetchOriginalPrice(val);
    },
    discountRate: function(val) {
      this.onRateChange(val);
    }
  },

  computed: {
    discountPrice: {
      get: function () {
        return !this.discountRate
          ? 0
          : (this.originalPrice * this.discountRate).toFixed(2);
      },
      set: function (val) {
        this.discountRate = val / this.originalPrice;
      },
    },
    discountRatePercentage: {
      get: function () {
        return !this.discountRate ? 0 : (this.discountRate * 100).toFixed(2);
      },
      set: function (val) {
        this.discountRate = val / 100;
      },
    },
    discountedPrice: {
      get: function () {
        return !this.discountRate
          ? this.originalPrice
          : (this.originalPrice - this.discountPrice).toFixed(2);
      },
      set: function (val) {
        this.discountRate = (this.originalPrice - val) / this.originalPrice;
      },
    },
  },

  methods: {
    refetchDiscountRate(val) {
      this.discountRate = 0;
      this.discountRate = val;
    },
    refetchOriginalPrice(val) {
      this.originalPrice = 0;
      this.originalPrice = val;
    },
    onRateChange(val) {
      this.$emit("onRateChange", val);
    },
  },
};
</script>