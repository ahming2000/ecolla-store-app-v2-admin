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
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteAccountModalLabel">删除员工账户</h5>
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
            data-dismiss="modal"
          >
            取消
          </button>
          <button
            type="submit"
            class="btn btn-danger btn-md"
            data-dismiss="modal"
            @click.prevent="deleteUser(user)"
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
      userData: this.user,
    };
  },

  mounted() {
    console.log("Delete User Component mounted.");
  },

  computed: {
    name() {
      return this.user?.name;
    },
    email() {
      return this.user?.email;
    },
  },

  methods: {
    deleteUser(user) {
      this.$emit('deleteUser', user);
    },
  },
};
</script>
