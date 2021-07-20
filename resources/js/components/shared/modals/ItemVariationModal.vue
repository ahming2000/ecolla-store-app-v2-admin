<style lang="scss" scoped>
.add-image {
  background-color: rgba(255, 255, 255, 0.5);
  width: 100%;
  padding-top: 100%; /* 1:1 Aspect Ratio */
  position: relative;
  border-radius: 15px;
  // box-shadow: 0 0 10px -2px hsla(0, 0%, 0%, 0.5);
  transition: all 0.3s ease;
  cursor: pointer;

  i {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    color: hsla(172, 100%, 47%, 0.5);
    font-size: 25px;
    transition: all 0.3s ease;
  }

  &:hover,
  &:active {
    background-color: #00eece;
    transition: all 0.3s ease;

    i {
      color: white;
      transition: all 0.3s ease;
    }
  }
}

/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 20px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 15px;
  width: 15px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

input:checked + .slider {
  background-color: #2196f3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196f3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(18px);
  -ms-transform: translateX(18px);
  transform: translateX(18px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 35px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<template>
  <div
    class="modal fade"
    id="itemVariationModal"
    tabindex="-1"
    aria-labelledby="itemVariationLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="itemVariationLabel">
            {{ actionName }}规格
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body bg-light">
          <!-- form content -->
          <div v-if="actionContentType === 'form'" class="container">
            <div class="row mb-3">
              <div
                class="col-4 d-flex justify-content-center align-items-center"
              >
                <input
                  class="d-none"
                  type="file"
                  @change="onFileSelected($event)"
                  ref="fileInput"
                />
                <!-- If image exists -->
                <div class="position-relative" v-if="variationImage">
                  <img
                    class="img-fluid border border-primary border-2 rounded"
                    :src="variationImage"
                    height="100px"
                    width="100px"
                  />
                  <button
                    class="
                      btn btn-primary
                      rounded-circle
                      position-absolute
                      top-100
                      start-100
                      translate-middle
                      d-flex
                      justify-content-center
                      align-items-center
                    "
                    style="height: 30px; width: 30px"
                    type="submit"
                    @click.prevent="$refs.fileInput.click()"
                  >
                    <i
                      class="icofont icofont-ui-edit"
                      style="font-size: 10px"
                    ></i>
                  </button>
                </div>
                <!-- If image doesn't exist -->
                <div
                  v-else
                  class="add-image"
                  @click.prevent="$refs.fileInput.click()"
                >
                  <i class="icofont icofont-ui-add"></i>
                </div>
              </div>
              <div class="col-8">
                <div class="form-floating mb-3">
                  <input
                    class="form-control"
                    type="text"
                    id="variationName"
                    placeholder="规格"
                    :value="variationName"
                    v-on:change="onChange($event, 'name')"
                  />
                  <label for="variationName">规格</label>
                </div>
                <div class="form-floating">
                  <input
                    class="form-control"
                    type="text"
                    id="variationEnName"
                    placeholder="Variation"
                    :value="variationEnName"
                    v-on:change="onChange($event, 'enName')"
                  />
                  <label for="variationEnName">Variation</label>
                </div>
              </div>
            </div>
            <div class="form-floating mb-3 w-100">
              <input
                class="form-control"
                type="text"
                id="variationBarcode"
                placeholder="货号"
                :value="variationBarcode"
                v-on:change="onChange($event, 'barcode')"
              />
              <label class="label" for="variationBarcode">货号</label>
            </div>
            <div class="row mb-3">
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
            <div class="form-group mb-3 w-100">
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
            <div class="row mb-3">
              <div class="col-8 d-flex align-items-center">
                <p class="m-0 font-weight-bold">折扣</p>
              </div>
              <div class="col-4 d-flex align-items-center justify-content-end">
                <label class="switch m-0">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    id="variationDiscountToggle"
                    v-model="isVariationDiscountEnabled"
                  />
                  <span class="slider round"></span>
                </label>
              </div>
            </div>
            <div v-if="isVariationDiscountEnabled">
              <div class="row mb-3">
                <edit-discount
                  :original_price="variationPrice"
                  :rate="0.3"
                  v-on:onRateChange="onRateChange($event)"
                ></edit-discount>
              </div>
              <!-- discount date range picker -->
              <div class="row mb-3">
                <div class="col-6">
                  <div class="form-group">
                    <label class="label" for="discountStartDate"
                      >折扣开始日期</label
                    >
                    <input
                      type="date"
                      class="form-control"
                      id="discountStartDate"
                      :value="variationDiscountStart"
                      v-on:change="onChange($event, 'discountStart')"
                    />
                  </div>
                </div>
                <div class="col-6">
                  <div v-if="isDurationLimited" class="row mb-3">
                    <div class="form-group">
                      <label class="label" for="discountEndDate"
                        >折扣结束日期</label
                      >
                      <input
                        type="date"
                        class="form-control"
                        id="discountEndDate"
                        :value="variationDiscountEnd"
                        v-on:change="onChange($event, 'discountEnd')"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  name="discountDuration"
                  :value="true"
                  v-model="isDurationLimited"
                  id="limitedDuration"
                />
                <label class="form-check-label" for="limitedDuration">
                  有期限
                </label>
              </div>
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  id="unlimitedDuration"
                  name="discountDuration"
                  :value="false"
                  v-model="isDurationLimited"
                  checked
                />
                <label class="form-check-label" for="unlimitedDuration">
                  无期限
                </label>
              </div>
            </div>
          </div>

          <!-- confirmation content -->
          <div
            v-else-if="actionContentType === 'confirmation'"
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
        <div class="modal-footer justify-content-center w-100">
          <div class="row w-100">
            <div class="col-6">
              <button
                type="button"
                :class="`btn ${actionButtonCancelClass} btn-md shadow-none w-100`"
                data-bs-dismiss="modal"
              >
                {{ actionButtonCancelName }}
              </button>
            </div>
            <div class="col-6">
              <button
                type="submit"
                :class="`btn ${actionButtonConfirmClass} btn-md w-100`"
                data-bs-dismiss="modal"
                @click.prevent="onPrimaryPressed()"
              >
                {{ actionButtonConfirmName }}
              </button>
            </div>
          </div>
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
      actionName: this.action?.name ?? null,
      actionContentType: this.action?.contentType ?? null,
      actionButtonConfirmName: this.action?.button.confirm.name ?? null,
      actionButtonConfirmClass: this.action?.button.confirm.class ?? null,
      actionButtonCancelName: this.action?.button.cancel.name ?? null,
      actionButtonCancelClass: this.action?.button.cancel.class ?? null,

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
      variationDiscountStart:
        this.variation?.discount?.start ?? this.getToday(),
      variationDiscountEnd: this.variation?.discount?.end ?? null,
      isVariationDiscountEnabled: this.variation?.discount ?? false,
      isDurationLimited: this.variation?.discount?.end ?? false,
    };
  },

  watch: {
    variation: function (val) {
      this.clearVariationData();
      this.fetchVariationData(val);
    },
    action: function (val) {
      this.clearActionData();
      this.fetchActionData(val);
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

    onFileSelected(event) {
      let newImage = event.target.files[0];
      let reader = new FileReader();
      reader.readAsDataURL(newImage);
      reader.onload = (e) => {
        let rawImage = e.target.result;
        this.$emit("onImageSelect", rawImage);
      };
    },

    uploadImage() {
      console.log("uploadImage()");
      // TODO add image
    },

    onChange(event, name) {
      let newValue = event.target.value.trim();

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
        case "discountStart": {
          this.variationDiscountStart = newValue;
          break;
        }
        case "discountEnd": {
          this.variationDiscountEnd = newValue;
          break;
        }
        default: {
        }
      }
    },

    onRateChange(newRate) {
      this.variationDiscountRate = newRate;
    },

    getToday() {
      const today = new Date();
      const date =
        today.getFullYear() +
        "-" +
        (today.getMonth() + 1) +
        "-" +
        today.getDate();

      return date;
    },

    fetchVariationData(val) {
      this.variationId = val?.id ?? null;
      this.variationImage = val?.image ?? null;
      this.variationName = val?.name ?? null;
      this.variationEnName = val?.name_en ?? null;
      this.variationBarcode = val?.barcode ?? null;
      this.variationPrice = val?.price ?? 0;
      this.variationStock = val?.stock ?? 0;
      this.variationWeight = val?.weight ?? 0;
      this.variationDiscount = val?.discount ?? null;
      this.variationDiscountRate = val?.discount?.rate ?? 0;
      this.variationDiscountStart = val?.discount?.start ?? this.getToday();
      this.variationDiscountEnd = val?.discount?.end ?? null;
      this.isVariationDiscountEnabled = val?.discount ?? false;
      this.isDurationLimited = val?.discount?.end ?? false;
    },

    fetchActionData(val) {
      this.actionName = val?.name ?? null;
      this.actionContentType = val?.contentType ?? null;
      this.actionButtonConfirmName = val?.button.confirm.name ?? null;
      this.actionButtonConfirmClass = val?.button.confirm.class ?? null;
      this.actionButtonCancelName = val?.button.cancel.name ?? null;
      this.actionButtonCancelClass = val?.button.cancel.class ?? null;
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
      this.variationDiscountStart = null;
      this.variationDiscountEnd = null;
      this.isVariationDiscountEnabled = false;
      this.isDurationLimited = false;
    },

    clearActionData() {
      this.actionName = null;
      this.actionContentType = null;
      this.actionButtonConfirmName = null;
      this.actionButtonConfirmClass = null;
      this.actionButtonCancelName = null;
      this.actionButtonCancelClass = null;
    },
  },
};
</script>