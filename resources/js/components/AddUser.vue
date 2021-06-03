<template>
  <div
    class="modal fade"
    id="addAccountModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="addAccountModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAccountModalLabel">添加员工账户</h5>
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
          <form>
            <div class="form-group md-form">
              <input
                type="email"
                class="form-control"
                id="addAccountNewEmailControl"
                name="email"
                placeholder="新员工邮箱"
                v-model="email"
              />
            </div>
            <div class="form-group md-form">
              <input
                type="text"
                class="form-control"
                id="addAccountNewFullNameControl"
                name="name"
                placeholder="新员工姓名"
                v-model="name"
              />
            </div>
            <div class="form-group md-form">
              <input
                type="password"
                class="form-control"
                id="addAccountNewPasswordControl"
                name="password"
                placeholder="新员工密码"
                v-model="password"
              />
            </div>
            <div class="form-group md-form">
              <input
                type="password"
                class="form-control"
                id="addAccountConfirmPasswordControl"
                name="password_confirmation"
                placeholder="新员工密码（重填确认）"
                v-model="passwordConfirmation"
              />
            </div>
            <div class="form-group">
              <label>权限</label>
              <ul
                class="list-group"
                v-for="permission in permissions"
                :key="permission.columnName"
              >
                <li class="list-group-item">
                  <div class="row">
                    <div class="col-8 d-flex align-items-center">
                      <p class="m-0" v-text="permission.cnDisplayName"></p>
                    </div>
                    <div
                      class="col-4 d-flex align-items-center justify-content-end"
                    >
                      <label class="switch m-0">
                        <input
                          type="checkbox"
                          class="form-control"
                          :id="`addAccount${permission.elementId}`"
                          :name="permission.columnName"
                          :value="permission.columnName"
                          v-model="checkedPermissions"
                          :checked="permission.checkedByDefault"
                        />
                        <span class="slider round"></span>
                      </label>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-danger btn-md shadow-none"
            data-dismiss="modal"
          >
            取消
          </button>
          <button
            type="button"
            class="btn btn-primary btn-md"
            data-dismiss="modal"
            @click.prevent="addUser()"
          >
            添加
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "add-user",

  props: {
    permissions: Object,
  },

  data() {
    return {
      email: "",
      name: "",
      password: "",
      passwordConfirmation: "",
      checkedPermissions: Object.keys(this.permissions)
        .filter((key) => this.permissions[key].checkedByDefault)
        .map((key) => this.permissions[key].columnName),
    };
  },

  mounted() {
    console.log("Add User Component mounted.");
  },

  methods: {
    addUser() {
      const user = {
        email: this.email,
        name: this.name,
        password: this.password,
        passwordConfirmation: this.passwordConfirmation,
        checkedPermissions: this.checkedPermissions,
      };
      this.$emit("addUser", user);
    },
  },
};
</script>
