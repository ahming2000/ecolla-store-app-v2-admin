<style lang="scss" scoped>
input[type="radio"] {
  display: none;

  + label {
    padding: 0;
    display: flex;
    justify-content: center;
    color: #6f3bff;

    div {
      position: relative;
      height: 250px;
      width: 250px;

      img {
        height: 250px;
        width: 250px;
        background: #6f3bff;
        border: 2px solid #6f3bff;
        border-radius: 10px;
        box-shadow: 0px 0px 5px -2px hsla(150, 5%, 65%, 0.5);

        &:hover {
          cursor: pointer;
        }
      }

      span {
        width: 40px;
        height: 40px;
        cursor: pointer;
        border-radius: 50%;
        border: 2px solid #6f3bff;
        box-shadow: 0 0px 3px 0 rgba(0, 0, 0, 0.33);
        background-color: #ffffff;

        position: absolute;
        top: 0%;
        left: 100%;
        transform: translate(-50%, -50%);
        display: flex;
        align-items: center;
        justify-content: center;

        i {
          opacity: 0;
          font-size: 30px;
          transition: all 0.3s ease;
        }
      }
    }
  }

  &:checked + label img {
    box-shadow: 0px 0px 10px #6f3bff;
  }

  &:checked + label span {
    border: 5px solid #6f3bff;
  }

  &:checked + label span i {
    opacity: 1;
  }
}
</style>
<template>
  <div
    class="modal fade"
    :id="type + 'UploadImageModal'"
    tabindex="-1"
    :aria-labelledby="type + 'UploadImageLabel'"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" :id="type + 'UploadImageLabel'">
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
          <div
            class="
              row
              mx-2
              my-5
              d-flex
              align-items-center
              justify-content-center
            "
          >
            <input
              :id="type + 'FramedImage'"
              :name="type + 'ProcessedImages'"
              type="radio"
              value="framedImage"
              v-model="selectedImage"
            />
            <label :for="type + 'FramedImage'">
              <div>
                <img
                  :src="framedImage"
                  alt="Framed Image"
                  height="250px"
                  width="250px"
                />
                <span>
                  <i class="icofont icofont-check"></i>
                </span>
              </div>
            </label>
          </div>
          <div
            class="
              row
              mx-2
              my-5
              d-flex
              align-items-center
              justify-content-center
            "
          >
            <input
              :id="type + 'CroppedImage'"
              :name="type + 'ProcessedImages'"
              type="radio"
              value="croppedImage"
              v-model="selectedImage"
            />
            <label :for="type + 'CroppedImage'">
              <div>
                <img
                  :src="croppedImage"
                  alt="Cropped Image"
                  height="250px"
                  width="250px"
                />
                <span>
                  <i class="icofont icofont-check"></i>
                </span>
              </div>
            </label>
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
    type: String,
    rawImage: File,
  },

  mounted() {
    /**
     * no idea why "checked" in DOM doesn't work as expected,
     * use this instead
     */
    document.getElementById(this.type + "FramedImage").checked = true;
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
    },
  },

  methods: {
    confirmUpload() {
      let imageData;
      switch (this.selectedImage) {
        case "framedImage": {
          imageData = this.framedImage;
          break;
        }
        case "croppedImage": {
          imageData = this.croppedImage;
          break;
        }
        default: {
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