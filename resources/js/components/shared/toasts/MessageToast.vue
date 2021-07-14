<template>
  <div style="z-index: 11">
    <div
      id="liveToast"
      class="toast hide top-50 start-0 translate-middle-y"
      role="alert"
      aria-live="assertive"
      aria-atomic="true"
    >
      <div class="toast-header">
        <img
          src="http://management.ecolla.laravel:8081/img/icon/ecolla_icon.png"
          class="rounded me-2"
          alt="..."
          height="25px"
          width="25px"
        />
        <strong class="me-auto">通知</strong>
        <small>11 mins ago</small>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="toast"
          aria-label="Close"
        ></button>
      </div>
      <div class="toast-body">{{ messageData }}}.</div>
    </div>
  </div>
</template>


<script>
export default {
  name: "message-toast",

  props: {
    message: String,
    type: String,
  },

  watch: {
    message: function (val) {
      this.messageData = val;
    },
  },

  data() {
    return {
      messageData: this.message ?? "",
    };
  },

  methods: {},
};
</script>
