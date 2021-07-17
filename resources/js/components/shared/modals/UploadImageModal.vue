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
          <h5 class="modal-title" id="uploadImageLabel">确定上传此照片？</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body bg-danger">
          <div class="row d-flex justify-content-center">
            <h5 class="fw-light text-center">图片预览</h5>
          </div>
          <div class="row">
            <img :src="framedImage" class="rounded mx-auto d-block" alt="..." />
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
    };
  },

  watch: {
    rawImage: function (val) {
      this.rawImageData = val;
      this.framedImage = this.processImageFrame(val);
      this.croppedImage = this.processImageCrop(val);
    },
  },

  methods: {
    confirmUpload() {
      this.$emit("onUpload", this.imageData);
      this.clearImageData();
    },

    processImageFrame(rawImage) {
      const formData = new FormData();
      formData.append("image", rawImage, rawImage.name);
      formData.append("mode", "frame");
      axios.post("/item/image/process", formData, {}).then((res) => {
        console.log(res);
        this.framedImage = res.data;
      });
    },

    processImageCrop(rawImage) {
      return null;
    },

    clearImageData() {
      this.imageData = null;
    },
  },
};
</script>