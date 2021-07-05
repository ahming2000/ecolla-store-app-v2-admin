<template>
  <div class="container">
    <div class="form-group w-100">
      <label class="label" for="discountRateRange">折扣率</label>
      <input
        type="range"
        class="form-range"
        max="1"
        min="0"
        step="0.0001"
        v-model="discountRateRange"
        id="discountRateRange"
      />
    </div>
    <div class="row mb-3">
      <div class="form-group w-100">
        <div class="input-group">
          <span class="input-group-text" id="negativeCurrencyUnit">- RM</span>
          <input
            type="number"
            class="form-control"
            id="discountPrice"
            :value="discountPrice.toFixed(2)"
            v-on:change="onChange($event, 'discountPrice')"
          />
          <span class="input-group-text" id="openBracket">(</span>
          <input
            type="number"
            class="form-control"
            id="discountRate"
            min="0"
            max="100"
            step="1"
            :value="discountRatePercentage.toFixed(2)"
            v-on:change="onChange($event, 'discountRatePercentage')"
          />
          <span class="input-group-text" id="percentage">%)</span>
        </div>
      </div>
    </div>

    <div class="form-group mb-3 w-100">
      <label class="label" for="discountedPrice">折扣后的价钱</label>
      <div class="input-group">
        <span class="input-group-text" id="currencyUnit">RM</span>
        <input
          type="number"
          class="form-control"
          id="discountedPrice"
          :value="discountedPrice.toFixed(2)"
          v-on:change="onChange($event, 'discountedPrice')"
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

      discountPrice: !this.rate ? 0 : this.original_price * this.rate,
      discountRatePercentage: !this.rate ? 0 : this.rate * 100,
      discountedPrice: this.original_price - this.original_price * this.rate,
    };
  },

  watch: {
    original_price: function (val) {
      this.fetchDiscountData(this.discountRate, val);
    },
    rate: function (val) {
      this.fetchDiscountData(val, this.originalPrice);
    },
    discountRate: function (val) {
      this.fetchDiscountData(val, this.originalPrice);
    },
  },

  computed: {
    discountRateRange: {
      get: function () {
        return !this.discountRate ? 0 : this.discountRate;
      },
      set: function (val) {
        this.discountRate = val;
      },
    },
  },

  methods: {
    clearDiscountData() {
      this.discountRate = 0;
      this.originalPrice = 0;

      this.discountPrice = 0;
      this.discountRatePercentage = 0;
      this.discountedPrice = this.original_price;
    },

    fetchDiscountData(rate, originalPrice) {
      this.discountRate = rate;
      this.originalPrice = originalPrice ?? 0;

      this.discountPrice = !rate ? 0 : originalPrice * rate;
      this.discountRatePercentage = !rate ? 0 : rate * 100;
      this.discountedPrice = originalPrice - this.discountPrice;
    },

    onChange(event, name) {
      let newValue = event.target.value.trim();

      switch (name) {
        case "discountPrice": {
          this.discountPrice = Number(newValue);
          this.discountRate = newValue / this.originalPrice;
          break;
        }
        case "discountRatePercentage": {
          this.discountRatePercentage = Number(newValue);
          this.discountRate = newValue / 100;
          break;
        }
        case "discountedPrice": {
          this.discountedPrice = Number(newValue);
          this.discountRate =
            (this.originalPrice - newValue) / this.originalPrice;
          break;
        }
        default: {
        }
      }
    },

    onRateChange(val) {
      this.$emit("onRateChange", val);
    },
  },
};
</script>