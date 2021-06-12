<template>
  <div>
    <div
      v-if="alerts[0].message.length > 0"
      class="alert alert-dismissable"
      :class="classObject"
      role="alert"
    >
      {{ alerts[0].message }}
    </div>

    <!-- User List -->
    <ul v-for="user in usersData" :key="user.id" class="list-group">
      <user
        v-bind:user="user"
        v-on:updateStatus="updateStatus($event)"
        v-on:sendUserToParent="sendUserToParent($event)"
      />
    </ul>

    <!-- Add Account Modal -->
    <add-user
      v-on:addUser="addUser($event)"
      v-bind:permissions="permissions"
    ></add-user>

    <!-- Edit Account Modal -->
    <edit-user
      v-on:editUser="editUser($event)"
      v-bind:user="selectedUser"
      v-bind:permissions="permissions"
    ></edit-user>

    <!-- Delete Account Modal -->
    <delete-user
      v-bind:user="selectedUser"
      v-on:deleteUser="deleteUser($event)"
    ></delete-user>
  </div>
</template>


<script>
import User from "./User";
import AddUser from "./AddUser";
import EditUser from "./EditUser";
import DeleteUser from "./DeleteUser";

export default {
  name: "users",
  props: {
    users: Array,
    permissions: Object,
  },

  data() {
    return {
      selectedUser: null,
      usersData: this.users,
      alerts: [
        {
          message: "",
          type: "",
        },
      ],
    };
  },

  components: {
    User,
    AddUser,
    EditUser,
    DeleteUser,
  },

  mounted() {
    console.log("Users Component mounted.");
  },

  computed: {
    classObject() {
      return {
        "alert-success": this.alerts[0].type == "success",
        "alert-danger": this.alerts[0].type == "error",
      };
    },
  },

  methods: {
    sendUserToParent(user) {
      this.selectedUser = user;
    },

    addUser(user) {
      const body = {
        email: user.email,
        name: user.name,
        password: user.password,
        password_confirmation: user.passwordConfirmation,
        permissions: user.checkedPermissions,
      };

      axios
        .post(`/account`, body)
        .then((res) => {
          this.usersData = [...this.usersData, res.data.user];

          console.log(res.data.message);
          this.makeAlert(res.data.message, "success");
        })
        .catch((error) => {
          let errorMessage;
          if (error.response.data.errors.email) {
            errorMessage = error.response.data.errors.email[0];
          } else if (error.response.data.errors.name) {
            errorMessage = error.response.data.errors.name[0];
          } else if (error.response.data.errors.password) {
            errorMessage = error.response.data.errors.password[0];
          } else if (error.request) {
            errorMessage = error.request.data;
          }
          console.error(`Failed to edit user ${user.name}`, errorMessage);
          this.makeAlert(errorMessage, "error");
        });
    },
    editUser(user) {
      const body = {
        name: user.name,
        password: user.password,
        password_confirmation: user.passwordConfirmation,
        permissions: user.checkedPermissions,
      };

      console.log("PATCH Request Body", body);

      axios
        .patch(`/account/${user.id}`, body)
        .then((res) => {
          this.selectedUser.name = res.data.user.name;
          this.selectedUser.permission = res.data.user.permission;

          console.log(res.data.message);
          this.makeAlert(res.data.message, "success");
        })
        .catch((error) => {
          let errorMessage;
          if (error.response.data.errors.name) {
            errorMessage = error.response.data.errors.name[0];
          } else if (error.response.data.errors.password) {
            errorMessage = error.response.data.errors.password[0];
          } else if (error.request) {
            errorMessage = error.request.data;
          }
          console.error(`Failed to edit user ${user.name}`, errorMessage);
          this.makeAlert(errorMessage, "error");
        });
    },
    updateStatus(data) {
      this.selectedUser = data.user;

      const body = { action: data.action };

      axios
        .post(`/account/${data.user.id}`, body)
        .then((res) => {
          this.selectedUser.status = res.data.user_status;
          console.log(res.data.message);

          this.makeAlert(res.data.message, "success");
        })
        .catch((error) => {
          const errorMessage = error.message;

          console.error(
            `Failed to update user status for ${data.user.name}`,
            errorMessage
          );
          this.makeAlert(errorMessage, "error");
        });
    },
    deleteUser(user) {
      const action = "delete";
      const userId = user.id;
      const body = { action: action };

      axios
        .post(`/account/${user.id}`, body)
        .then((res) => {
          this.usersData = this.usersData.filter((user) => {
            return user.id !== userId;
          });

          console.log(res.data.message);
          this.makeAlert(res.data.message, "success");
        })
        .catch((error) => {
          const errorMessage = error.message;

          console.error(
            `Failed to delete user ID for ${user.name}`,
            errorMessage
          );
          this.makeAlert(errorMessage, "error");
        });
    },
    makeAlert(message, type) {
      this.alerts = [
        {
          message: message,
          type: type,
        },
      ];
    },
  },
};
</script>
