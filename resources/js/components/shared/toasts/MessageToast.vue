<template>
  <div style="z-index: 11">
    <div
      id="liveToast"
      class="toast hide"
      role="alert"
      aria-live="assertive"
      aria-atomic="true"
    >
      <div :class="headerBgClass">
        <img
          src="http://management.ecolla.laravel:8081/img/icon/ecolla_icon.png"
          class="rounded me-2"
          alt="..."
          height="25px"
          width="25px"
        />
        <strong class="me-auto">通知</strong>
        <button
          type="button"
          class="btn-close btn-close-white"
          data-bs-dismiss="toast"
          aria-label="Close"
        ></button>
      </div>
      <div
        :class="bodyTextColorClass"
        v-html="messageData"
      ></div>
    </div>
  </div>
</template>


<script>
export default {
  name: "message-toast",

  props: {
    message: Object,
  },

  watch: {
    message: function (val) {
      this.messageData = val.message;
      this.typeData = val.type;
    },
  },

  data() {
    return {
      messageData: this.message?.message ?? "",
      typeData: this.message?.type ?? "",
    };
  },

  computed: {
    headerBgClass() {
      return {
        'toast-header': true,
        'text-light': true,
        'bg-success': this.typeData === "success",
        'bg-danger': this.typeData === "error",
      };
    },
    bodyTextColorClass() {
      return {
        'toast-body': true,
        'text-center': true,
        'text-wrap': true,
        'fw-light': true,
        'text-success': this.typeData === "success",
        'text-danger': this.typeData === "error",
      };
    },
  },

  methods: {},
};
</script>
