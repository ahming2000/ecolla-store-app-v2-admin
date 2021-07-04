<template>
  <div
    v-if="action"
    class="modal fade"
    :id="action.modalId"
    tabindex="-1"
    role="dialog"
    :aria-labelledby="`${action.modalId}Label`"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" :id="`${action.modalId}Label`">
            {{ action.name }}规格
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- form content -->
          <div v-if="action.contentType === 'form'" class="container">
            <div class="row">
              <div
                class="media d-flex justify-content-center align-items-center"
              >
                <img
                  v-if="variationImage"
                  class="
                    figure-img
                    img-fluid
                    border border-primary border-2
                    rounded
                    mr-3
                  "
                  :src="variationImage"
                  height="100px"
                  width="100px"
                />
                <div
                  v-else
                  class="
                    figure-img
                    img-fluid
                    border border-light border-2
                    rounded
                    mr-3
                    bg-white
                    d-flex
                    justify-content-center
                    align-items-center
                  "
                  style="height: 100px; width: 100px"
                >
                  <button
                    v-if="!variationImage"
                    class="
                      btn btn-outline-light btn-circle-40 btn-image
                      border-4
                      shadow-none
                      d-flex
                      justify-content-center
                      align-items-center
                    "
                    type="submit"
                    @click.prevent="addImage()"
                  >
                    <i class="icofont icofont-ui-add"></i>
                  </button>
                </div>
              </div>
              <div class="media-body">
                <div class="form-group">
                  <label class="label" for="variationName">规格</label>
                  <input
                    class="form-control"
                    type="text"
                    id="variationName"
                    :value="variationName"
                    v-on:change="onChange($event, 'name')"
                  />
                </div>
                <div class="form-group">
                  <label class="label" for="variationEnName">Variation</label>
                  <input
                    class="form-control"
                    type="text"
                    id="variationEnName"
                    :value="variationEnName"
                    v-on:change="onChange($event, 'enName')"
                  />
                </div>
              </div>
              <div class="form-group w-100">
                <label class="label" for="variationBarcode">货号</label>
                <input
                  class="form-control"
                  type="text"
                  id="variationBarcode"
                  :value="variationBarcode"
                  v-on:change="onChange($event, 'barcode')"
                />
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group w-100">
                    <label class="label" for="variationPrice">原价</label>
                    <div class="input-group">
                      <span class="input-group-text" id="currencyUnit">RM</span>
                      <input
                        type="number"
                        class="form-control"
                        id="variationPrice"
                        min="0.00"
                        step="0.10"
                        :value="variationPrice.toFixed(2)"
                        v-on:change="onChange($event, 'price')"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group w-100">
                    <label class="label" for="variationStock">库存</label>
                    <input
                      class="form-control"
                      type="number"
                      id="variationStock"
                      min="0"
                      step="1"
                      :value="variationStock"
                      v-on:change="onChange($event, 'stock')"
                    />
                  </div>
                </div>
              </div>
              <div class="form-group w-100">
                <label class="label" for="variationWeight">重量</label>
                <div class="input-group">
                  <input
                    type="number"
                    class="form-control"
                    id="variationWeight"
                    min="0.01"
                    step="0.01"
                    :value="variationWeight.toFixed(2)"
                    v-on:change="onChange($event, 'weight')"
                  />
                  <span class="input-group-text" id="weightUnit">kg</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-8 d-flex align-items-center">
                <p class="m-0 font-weight-bold">折扣</p>
              </div>
              <div class="col-4 d-flex align-items-center justify-content-end">
                <label class="switch m-0">
                  <input
                    type="checkbox"
                    class="form-control"
                    id="variationDiscountToggle"
                    v-model="isVariationDiscountEnabled"
                  />
                  <span class="slider round"></span>
                </label>
              </div>
            </div>
            <div v-if="isVariationDiscountEnabled">
              <div class="row">
                <edit-discount
                  :original_price="variationPrice"
                  :rate="variationDiscountRate"
                  v-on:onRateChange="onRateChange($event)"
                ></edit-discount>
              </div>
              <div class="row">
                <!-- discount date range picker -->
              </div>
            </div>
          </div>

          <!-- confirmation content -->
          <div
            v-else-if="action.contentType === 'confirmation'"
            class="container"
          >
            <div class="col">
              <p>确定删除此规格？此动作无法挽回。</p>
              <div class="col flex-column d-inline-flex justify-content-center">
                <p
                  id="variationName"
                  class="m-0 p-0 h5 d-inline-flex"
                  v-text="variationName"
                ></p>
                <p
                  id="variationEnName"
                  class="m-0 p-0 text-muted d-inline-flex"
                  v-text="variationEnName"
                ></p>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            :class="`btn ${action.button.cancel.class} btn-md shadow-none`"
            data-dismiss="modal"
          >
            {{ action.button.cancel.name }}
          </button>
          <button
            type="submit"
            :class="`btn ${action.button.confirm.class} btn-md`"
            data-dismiss="modal"
            @click.prevent="onPrimaryPressed()"
          >
            {{ action.button.confirm.name }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import EditDiscount from "../components/EditDiscount.vue";
export default {
  components: { EditDiscount },

  name: "item-variation-modal",

  props: {
    variation: Object,
    action: Object,
  },

  /**
   * Because Vue doesn't support null safety in binding expression,
   *
   * e.g.
   * {{ parent?.child }}
   *
   * so I have to use this way to workaround >:(
   */
  data() {
    return {
      variationId: this.variation?.id ?? null,
      variationImage: this.variation?.image ?? null,
      variationName: this.variation?.name ?? null,
      variationEnName: this.variation?.name_en ?? null,
      variationBarcode: this.variation?.barcode ?? null,
      variationPrice: this.variation?.price ?? 0,
      variationStock: this.variation?.stock ?? 0,
      variationWeight: this.variation?.weight ?? 0,
      variationDiscount: this.variation?.discount ?? null,
      variationDiscountRate: this.variation?.discount?.rate ?? 0,
      isVariationDiscountEnabled: this.variation?.discount ?? false,
    };
  },

  watch: {
    variation: function (val) {
      this.clearVariationData();
      this.fetchVariationData(val);
    },
  },

  methods: {
    onPrimaryPressed() {
      console.log("onPrimaryPressed()");
      switch (this.action.value) {
        case "add": {
          this.$emit("onSaveAdd", this.variationData);
          break;
        }
        case "edit": {
          this.$emit("onSaveEdit", this.variationData);
          break;
        }
        case "delete": {
          this.$emit("onConfirmDelete", this.variationData);
          break;
        }
      }
    },

    addImage() {
      console.log("addImage()");
      // TODO add image
    },

    onChange(event, name) {
      let newValue = event.target.value.trim();
      console.log(newValue);

      switch (name) {
        case "name": {
          this.variationName = newValue;
          break;
        }
        case "enName": {
          this.variationEnName = newValue;
          break;
        }
        case "barcode": {
          this.variationBarcode = newValue;
          break;
        }
        case "price": {
          this.variationPrice = Number(newValue);
          break;
        }
        case "stock": {
          this.variationStock = Number(newValue);
          break;
        }
        case "weight": {
          this.variationWeight = Number(newValue);
          break;
        }
        default: {
        }
      }
    },

    onRateChange(newRate) {
      this.variationDiscountRate = newRate;
    },

    fetchVariationData(val) {
      this.variationId = val.id ?? null;
      this.variationImage = val.image ?? null;
      this.variationName = val.name ?? null;
      this.variationEnName = val.name_en ?? null;
      this.variationBarcode = val.barcode ?? null;
      this.variationPrice = val.price ?? 0;
      this.variationStock = val.stock ?? 0;
      this.variationWeight = val.weight ?? 0;
      this.variationDiscount = val.discount ?? null;
      this.variationDiscountRate = val.discount?.rate ?? 0;
      this.isVariationDiscountEnabled = val.discount ?? false;
    },

    clearVariationData() {
      this.variationId = null;
      this.variationImage = null;
      this.variationName = null;
      this.variationEnName = null;
      this.variationBarcode = null;
      this.variationPrice = 0;
      this.variationStock = 0;
      this.variationWeight = 0;
      this.variationDiscount = null;
      this.variationDiscountRate = 0;
      this.isVariationDiscountEnabled = false;
    },
  },
};
</script>