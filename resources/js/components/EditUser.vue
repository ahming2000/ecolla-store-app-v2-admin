<template>
  <div
    class="modal fade"
    id="editAccountModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="editAccountModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAccountModalLabel">编辑员工账户</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body bg-light">
          <div class="form-floating mb-3">
            <input
              type="email"
              class="form-control"
              id="editAccountEmailControl"
              name="email"
              v-model="email"
              readonly
              placeholder="员工邮箱"
            />
            <label class="label" for="editAccountNewEmailControl"
              >员工邮箱</label
            >
          </div>
          <div class="form-floating mb-3">
            <input
              type="text"
              class="form-control"
              id="editAccountFullNameControl"
              name="name"
              v-model="name"
              placeholder="员工姓名"
            />
            <label class="label" for="editAccountNewFullNameControl"
              >员工姓名</label
            >
            <span class="invalid-feedback" role="alert">
              <strong></strong>
            </span>
          </div>
          <div class="form-floating mb-3">
            <input
              type="password"
              class="form-control"
              id="editAccountPasswordControl"
              name="password"
              placeholder="员工密码"
              v-model="password"
            />
            <label class="label" for="editAccountNewPasswordControl"
              >员工密码</label
            >
            <span class="invalid-feedback" role="alert">
              <strong></strong>
            </span>
          </div>
          <div class="form-floating mb-3">
            <input
              type="password"
              class="form-control"
              id="editAccountConfirmPasswordControl"
              name="password_confirmation"
              placeholder="员工密码（重填确认）"
              v-model="passwordConfirmation"
            />
            <label class="label" for="editAccountConfirmPasswordControl"
              >员工密码（重填确认）</label
            >
            <span class="invalid-feedback" role="alert">
              <strong></strong>
            </span>
          </div>

          <div class="form-group">
            <label>权限</label>
            <div
              class="mb-3"
              v-for="group in permissions"
              :key="group.groupName"
            >
              <ul
                class="list-group"
                v-for="(permission, index) in group.permissions"
                :key="permission.columnName"
              >
                <li class="list-group-item" :class="{ 'ms-3': index > 0 }">
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
                          @change="onPermissionChange($event, group)"
                        />
                        <span class="slider round"></span>
                      </label>
                    </div>
                  </div>
                </li>
              </ul>
              <!-- subGroup -->
              <div v-if="group.subGroups.length > 0" class="ms-3">
                <div
                  v-for="subGroup in group.subGroups"
                  :key="subGroup.groupName"
                >
                  <ul
                    class="list-group"
                    v-for="(permission, index) in subGroup.permissions"
                    :key="permission.columnName"
                  >
                    <li class="list-group-item" :class="{ 'ms-3': index > 0 }">
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
                              @change="onPermissionChange($event, subGroup)"
                            />
                            <span class="slider round"></span>
                          </label>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-outline-danger btn-md shadow-none"
            data-bs-dismiss="modal"
          >
            取消
          </button>
          <button
            type="submit"
            class="btn btn-primary btn-md"
            data-bs-dismiss="modal"
            @click.prevent="editUser()"
          >
            更新
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "edit-user",

  props: {
    user: Object,
    permissions: Array,
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
        .filter(
          (entry) =>
            entry[0] !== "user_id" &&
            entry[0] !== "created_at" &&
            entry[0] !== "updated_at" &&
            entry[1] == 1
        )
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

  computed: {},

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
      this.password = "";
      this.passwordConfirmation = "";
    },

    onPermissionChange(event, group) {
      console.log(event.target.value);

      // if user turned off '{group}_view'
      if (
        event.target.value == this.getViewPermissionName(group) &&
        !this.isViewPermissionChecked(group)
      ) {
        // auto turns off '{group}_*'
        this.checkedPermissions = this.checkedPermissions.filter(
          (checkedPermission) =>
            !this.isCheckedPermissionBelongsToGroup(checkedPermission, group)
        );

        if (this.hasSubGroups(group)) {
          // auto turns off '{subGroup}_*' as well
          this.checkedPermissions = this.checkedPermissions.filter(
            (checkedPermission) =>
              !this.isCheckedPermissionBelongsToGroup(
                checkedPermission,
                group.subGroups[0]
              )
          );
        }
      }
      // if user turned on/off '{group}_*' while '{group}_view' is turned off
      else if (
        event.target.value != this.getViewPermissionName(group) &&
        !this.isViewPermissionChecked(group)
      ) {
        // auto turns on '{group}_view'
        this.checkedPermissions.push(this.getViewPermissionName(group));

        // if '{parentGroup}_view' is turned off
        if (
          this.hasParentGroup(group) &&
          !this.isViewPermissionChecked(this.getParentGroup(group))
        ) {
          // auto turns on '{parentGroup}_view' as well
          this.checkedPermissions.push(
            this.getViewPermissionName(this.getParentGroup(group))
          );
        }
      }
      // if user turned on '{group}_view'
      else if (
        event.target.value == this.getViewPermissionName(group) &&
        this.isViewPermissionChecked(group)
      ) {
        // if '{parentGroup}_view' is turned off
        if (
          this.hasParentGroup(group) &&
          !this.isViewPermissionChecked(this.getParentGroup(group))
        ) {
          // auto turns on '{parentGroup}_view'
          this.checkedPermissions.push(
            this.getViewPermissionName(this.getParentGroup(group))
          );
        }
      }

      console.log("\n");
    },

    isViewPermissionChecked(group) {
      console.log(
        `isViewPermissionChecked(${group})`,
        this.checkedPermissions.includes(group.permissions[0].columnName)
      );

      return this.checkedPermissions.includes(group.permissions[0].columnName);
    },

    getViewPermissionName(group) {
      console.log(
        `getViewPermissionName(${group})`,
        group.permissions[0].columnName
      );

      return group.permissions[0].columnName;
    },

    isCheckedPermissionBelongsToGroup(checkedPermission, group) {
      console.log(
        `isCheckedPermissionBelongsToGroup(${checkedPermission}, ${group})`,
        group.permissions.find(
          (permission) => checkedPermission == permission.columnName
        ) !== undefined
      );

      return (
        group.permissions.find(
          (permission) => checkedPermission == permission.columnName
        ) !== undefined
      );
    },

    hasSubGroups(group) {
      console.log(`hasSubGroups(${group})`, group.subGroups.length > 0);

      return group.subGroups.length > 0;
    },

    getParentGroup(group) {
      return this.permissions.find(
        (permission) => permission.groupName == group.parent
      );
    },

    hasParentGroup(group) {
      console.log(`hasParentGroup(${group})`, group.parent !== "root");

      return group.parent !== "root";
    },
  },
};
</script>
