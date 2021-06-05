<template>
  <div>
    <!-- User List -->
    <ul v-for="user in usersData" :key="user.id" class="list-group">
      <user v-bind:user="user" v-on:sendUserToModal="sendUserToModal($event)" />
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

  methods: {
    sendUserToModal(user) {
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
        })
        .catch((error) => {
          const errorMessage = error.message;
          console.error(`Failed to add user ${user.name}`, errorMessage);
        });
    },
    editUser(user) {
      const body = {
        name: user.name,
        password: user.password,
        password_confirmation: user.passwordConfirmation,
        permissions: user.checkedPermissions,
      };

      console.log("PATCH Request Body", body)

      axios
        .patch(`/account/${user.id}`, body)
        .then((res) => {
          /// TODO update UI
          console.log(res.data.message);
        })
        .catch((error) => {
          const errorMessage = error.message;
          console.error(`Failed to edit user ${user.name}`, errorMessage);
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
        })
        .catch((error) => {
          const errorMessage = error.message;
          console.error(`Failed to delete user ID for ${user.name}`, errorMessage);
        });
    },
  },
};
</script>
