<template>
  <div
    class="modal fade"
    id="deleteItemImageModal"
    tabindex="-1"
    aria-labelledby="deleteItemImageLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteItemImageLabel">
            删除照片
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="col">
              <p>确定删除？此动作无法挽回。</p>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-center w-100">
          <div class="row w-100">
            <div class="col-6">
              <button
                type="button"
                :class="`btn btn-outline-primary btn-md shadow-none w-100`"
                data-bs-dismiss="modal"
              >
                取消
              </button>
            </div>
            <div class="col-6">
              <button
                type="submit"
                :class="`btn btn-danger btn-md w-100`"
                data-bs-dismiss="modal"
                @click.prevent="onConfirmDelete()"
              >
                确定删除
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
  name: "delete-image-modal",

  props: {
  },

  data() {
    return {
    };
  },

  watch: {
    object: function (val) {
      this.objectData = val;
    },
  },

  methods: {
      onConfirmDelete() {
        this.$emit("onConfirmDelete");
      }
  },
};
</script>