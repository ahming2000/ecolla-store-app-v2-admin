<template>
  <!-- Delete Account Modal -->
  <div
    class="modal fade"
    id="deleteAccountModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="deleteAccountModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteAccountModalLabel">删除员工账户</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="col">
            <p>确定删除此员工账户？此动作无法挽回。</p>
            <div class="col flex-column d-inline-flex justify-content-center">
              <p
                id="deleteAccountFullNameDisplay"
                class="m-0 p-0 h5 d-inline-flex"
                v-text="name"
              ></p>
              <p
                id="deleteAccountEmailDisplay"
                class="m-0 p-0 text-muted d-inline-flex"
                v-text="email"
              ></p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-primary btn-md shadow-none"
            data-bs-dismiss="modal"
          >
            取消
          </button>
          <button
            type="submit"
            class="btn btn-danger btn-md"
            data-bs-dismiss="modal"
            @click.prevent="deleteUser()"
          >
            确定删除
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "delete-user",

  props: {
    user: Object,
  },

  data() {
    return {
      email: "",
      name: "",
    };
  },

  mounted() {
    console.log("Delete User Component mounted.");
  },

  watch: {
    user: function () {
      this.email = this.user.email;
      this.name = this.user.name;
    },
  },

  methods: {
    deleteUser() {
      this.$emit("deleteUser", this.user);
    },
  },
};
</script>
