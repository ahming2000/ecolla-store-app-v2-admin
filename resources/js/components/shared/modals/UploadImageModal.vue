<style lang="scss" scoped>
input[type="radio"] {
  display: none;

  + label {
    color: #6f3bff;
    font-family: Arial, sans-serif;
    font-size: 14px;

    span {
      width: 40px;
      height: 40px;
      cursor: pointer;
      border-radius: 50%;
      border: 2px solid #6f3bff;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.33);
      background-color: #ffffff;
      background-repeat: no-repeat;
      background-position: center;

      i {
        opacity: 0;
        font-size: 30px;
        transition: all 0.3s ease;
      }
    }
  }

  &:checked + label span i {
    opacity: 1;
  }
}
</style>
<template>
  <div
    class="modal fade"
    id="uploadImageModal"
    tabindex="-1"
    aria-labelledby="uploadImageLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="uploadImageLabel">
            请选择照片的缩放方式
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="row m-5 position-relative">
            <img
              :src="framedImage"
              class="rounded border border-primary border-3 p-0"
              alt="Framed Image"
            />
            <input
              id="framedImage"
              name="processedImages"
              type="radio"
              value="framedImage"
              v-model="selectedImage"
            />
            <label
              class="position-absolute top-0 start-100 translate-middle w-auto"
              for="framedImage"
            >
              <span class="d-flex align-items-center justify-content-center">
                <i class="icofont icofont-check"></i>
              </span>
            </label>
          </div>
          <div class="row m-5 position-relative">
            <input
              id="croppedImage"
              name="processedImages"
              type="radio"
              value="croppedImage"
              v-model="selectedImage"
            />
            <label
              class="position-absolute top-0 start-100 translate-middle w-auto"
              for="croppedImage"
            >
              <span class="d-flex align-items-center justify-content-center">
                <i class="icofont icofont-check"></i>
              </span>
            </label>
            <img
              :src="croppedImage"
              class="rounded border border-primary border-3 p-0"
              alt="Cropped Image"
            />
          </div>
        </div>
        <div class="modal-footer justify-content-center w-100">
          <div class="row w-100">
            <div class="col-6">
              <button
                type="button"
                class="btn btn-outline-danger btn-md shadow-none w-100"
                data-bs-dismiss="modal"
              >
                取消
              </button>
            </div>
            <div class="col-6">
              <button
                type="submit"
                class="btn btn-primary btn-md w-100"
                data-bs-dismiss="modal"
                @click.prevent="confirmUpload()"
              >
                确定
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "upload-image-modal",

  props: {
    rawImage: File,
  },

  data() {
    return {
      rawImageData: this.rawImage ?? null,
      framedImage: null,
      croppedImage: null,
      selectedImage: "framedImage",
    };
  },

  watch: {
    rawImage: function (val) {
      this.rawImageData = val;
      this.framedImage = null;
      this.croppedImage = null;
      this.processImageFrame(val);
      this.processImageCrop(val);
      /**
       * no idea why "checked" in DOM doesn't work as expected,
       * use this instead
       */
      document.getElementById("framedImage").checked = true;
    },
  },

  methods: {
    confirmUpload() {
      let imageData;
      switch (this.selectedImage) {
        case "framedImage": {
          imageData = this.framedImage;
        }
        case "croppedImage": {
          imageData = this.croppedImage;
        }
      }
      this.$emit("onUpload", imageData);
      this.clearRawImageData();
    },

    processImageFrame(rawImage) {
      const formData = new FormData();
      formData.append("image", rawImage, rawImage.name);
      formData.append("mode", "frame");
      axios
        .post("/item/image/process", formData, {})
        .then((res) => {
          console.log(res);
          if (res.data.image !== "") {
            this.framedImage = res.data.image;
          } else {
            this.$emit("onResponse", res.data.error, "error");
          }
        })
        .catch((error) => {
          console.error(error);
          this.$emit("onResponse", error.message, "error");
        });
    },

    processImageCrop(rawImage) {
      const formData = new FormData();
      formData.append("image", rawImage, rawImage.name);
      formData.append("mode", "crop");
      axios
        .post("/item/image/process", formData, {})
        .then((res) => {
          console.log(res);
          if (res.data.image !== "") {
            this.croppedImage = res.data.image;
          } else {
            this.$emit("onResponse", res.data.error, "error");
          }
        })
        .catch((error) => {
          console.error(error);
          this.$emit("onResponse", error.message, "error");
        });
    },

    clearRawImageData() {
      this.rawImageData = null;
    },
  },
};
</script>