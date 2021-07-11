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
        <div class="modal-body">
          <div class="row d-flex justify-content-center">
            <h5 class="fw-light text-center">图片预览</h5>
          </div>
          <div class="row">
            <img :src="imageData" class="rounded mx-auto d-block" alt="..." />
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
    image: String,
  },

  data() {
    return {
      imageData: this.image ?? null,
    };
  },

  watch: {
    image: function (val) {
      this.imageData = val;
    },
  },

  methods: {
    confirmUpload() {
      this.$emit("onUpload", this.imageData);
      this.clearImageData();
    },

    clearImageData() {
      this.imageData = null;
    },
  },
};
</script>