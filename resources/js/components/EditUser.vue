<template>
  <div
    class="modal fade"
    id="editAccountModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="editAccountModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAccountModalLabel">编辑员工账户</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form>
          <div class="modal-body">
            <div class="form-group md-form">
              <input
                type="email"
                class="form-control"
                id="editAccountEmailControl"
                name="email"
                v-model="email"
                readonly
                placeholder="员工邮箱"
              />
            </div>
            <div class="form-group md-form">
              <input
                type="text"
                class="form-control"
                id="editAccountFullNameControl"
                name="name"
                v-model="name"
                placeholder="员工姓名"
              />
              <span class="invalid-feedback" role="alert">
                <strong></strong>
              </span>
            </div>
            <div class="form-group md-form">
              <input
                type="password"
                class="form-control"
                id="editAccountPasswordControl"
                name="password"
                placeholder="员工密码"
                v-model="password"
              />
              <span class="invalid-feedback" role="alert">
                <strong></strong>
              </span>
            </div>
            <div class="form-group md-form">
              <input
                type="password"
                class="form-control"
                id="editAccountConfirmPasswordControl"
                name="password_confirmation"
                placeholder="员工密码（重填确认）"
                v-model="passwordConfirmation"
              />
              <span class="invalid-feedback" role="alert">
                <strong></strong>
              </span>
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
                      <p class="m-0">{{ permission.cnDisplayName }}</p>
                    </div>
                    <div
                      class="
                        col-4
                        d-flex
                        align-items-center
                        justify-content-end
                      "
                    >
                      <label class="switch m-0">
                        <input
                          type="checkbox"
                          class="form-control"
                          :id="`editAccount${permission.elementId}`"
                          :name="permission.columnName"
                          :value="permission.columnName"
                          v-model="checkedPermissions"
                        />
                        <span class="slider round"></span>
                      </label>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
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
              type="submit"
              class="btn btn-primary btn-md"
              data-dismiss="modal"
              @click.prevent="editUser()"
            >
              更新
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "edit-user",

  props: {
    user: Object,
    permissions: Object,
  },

  data() {
    return {
      email: "",
      name: "",
      password: "",
      passwordConfirmation: "",
      checkedPermissions: [],
    };
  },

  watch: {
    user: function () {
      this.email = this.user.email;
      this.name = this.user.name;
      this.checkedPermissions = Object.entries(this.user.permission)
        .filter((entry) => entry[0] !== "user_id" && entry[0] !== "created_at" && entry[0] !== "updated_at" && entry[1] == 1)
        .map((entry) => entry[0]);
    },
    checkedPermissions: function (val) {
      console.log("watch val", val);
      this.checkedPermissions = val;
    },
  },

  mounted() {
    console.log("Edit User Component mounted.");
  },

  methods: {
    editUser() {
      const user = {
        id: this.user.id,
        name: this.name,
        password: this.password,
        passwordConfirmation: this.passwordConfirmation,
        checkedPermissions: this.checkedPermissions,
      };
      this.$emit("editUser", user);
      this.password = '';
      this.passwordConfirmation = '';
    },
  },
};
</script>