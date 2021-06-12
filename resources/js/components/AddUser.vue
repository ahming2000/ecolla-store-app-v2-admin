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
              <div
                class="mb-3"
                v-for="permissionGroup in permissions"
                :key="permissionGroup.key"
              >
                <ul
                  class="list-group"
                  v-for="permission in permissionGroup"
                  :key="permission.columnName"
                >
                  <li
                    v-if="(typeof permission.columnName) != 'undefined'"
                    class="list-group-item"
                    :class="{ 'ml-3': permission != permissionGroup['view'] }"
                  >
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
                            @change="
                              onPermissionChange(
                                $event,
                                permissionGroup,
                                permission
                              )
                            "
                          />
                          <span class="slider round"></span>
                        </label>
                      </div>
                    </div>
                  </li>
                  <div
                    v-if="typeof permission.columnName == 'undefined'"
                    class="ml-3"
                  >
                    <ul
                      class="list-group"
                      v-for="subPermission in permission"
                      :key="subPermission.columnName"
                    >
                      <li
                        class="list-group-item"
                        :class="{ 'ml-3': subPermission != permission['view'] }"
                      >
                        <div class="row">
                          <div class="col-8 d-flex align-items-center">
                            <p class="m-0">{{ subPermission.cnDisplayName }}</p>
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
                                :id="`editAccount${subPermission.elementId}`"
                                :name="subPermission.columnName"
                                :value="subPermission.columnName"
                                v-model="checkedPermissions"
                              />
                              <span class="slider round"></span>
                            </label>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                </ul>
              </div>
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
      checkedPermissions: this.getPermissionsCheckedByDefault(),
    };
  },

  mounted() {
    console.log("Add User Component mounted.");
  },

  methods: {
    getPermissionsCheckedByDefault() {
      const entries1 = Object.entries(this.permissions);
      const entries2 = entries1.map((entry) => entry[1]);

      const permissionsArray = entries2
        .map((permission) => Object.values(permission))
        .flat();

      const checkedPermissionsArray = permissionsArray.filter(
        (permission) => permission.checkedByDefault
      ).map(permission => permission.columnName);

      return checkedPermissionsArray;
    },
    addUser() {
      const user = {
        email: this.email,
        name: this.name,
        password: this.password,
        passwordConfirmation: this.passwordConfirmation,
        checkedPermissions: this.checkedPermissions,
      };
      this.$emit("addUser", user);

      this.email = "";
      this.name = "";
      this.password = "";
      this.passwordConfirmation = "";
      this.checkedPermissions = Object.keys(this.permissions).filter(
        (key) => this.permissions[key].checkedByDefault
      );
    },
    onPermissionChange(event, permissionGroup, permission) {
      console.log(permissionGroup);
      console.log(permission);

      if (
        !this.isViewPermissionChecked(permissionGroup) &&
        this.isViewPermission(permissionGroup, permission)
      ) {
        this.checkedPermissions = this.checkedPermissions.filter(
          (checkedPermission) => {
            return !this.isCheckedPermissionBelongsTo(
              checkedPermission,
              permissionGroup
            );
          }
        );
      } else if (
        !this.isViewPermissionChecked(permissionGroup) &&
        !this.isViewPermission(permissionGroup, permission)
      ) {
        this.checkedPermissions.push(this.getViewPermission(permissionGroup));
      }
    },
    isViewPermissionChecked(permissionGroup) {
      return this.checkedPermissions.includes(
        this.getViewPermission(permissionGroup)
      );
    },
    isViewPermission(permissionGroup, permission) {
      let viewPermission = Object.entries(permissionGroup).find(
        (entry) => entry[0] == "view"
      )[1];

      return permission == viewPermission;
    },

    isCheckedPermissionBelongsTo(checkedPermission, permissionGroup) {
      let matchedEntry = Object.entries(permissionGroup).find(
        (entry) => entry[1].columnName == checkedPermission
      );

      return matchedEntry !== undefined;
    },
    getViewPermission(permissionGroup) {
      let permissionGroupName = Object.entries(this.permissions).find(
        (entry) => entry[1] == permissionGroup
      )[0];

      return `${permissionGroupName}_view`;
    },
  },
};
</script>
