<template>
  <div>
    <ul v-for="user in usersData" :key="user.id" class="list-group">
      <user v-bind:user="user" v-on:sendUserToModal="sendUserToModal($event)" />
    </ul>
    <!-- Delete Account Modal -->
    <delete-user
      v-bind:user="selectedUser"
      v-on:deleteUser="deleteUser($event)"
    ></delete-user>
  </div>
</template>


<script>
import User from "./User";
import DeleteUser from "./DeleteUser";

export default {
  name: "users",
  props: {
    users: Array,
  },

  data() {
    return {
      selectedUser: null,
      usersData: this.users,
    };
  },

  components: {
    User,
    DeleteUser,
  },

  mounted() {
    console.log("Users Component mounted.");
  },

  methods: {
    sendUserToModal(user) {
      this.selectedUser = user;
    },

    deleteUser(user) {
      const action = "delete";
      const userId = user.id;
      const body = { action: action };

      axios
        .post(`/account/${user.id}`, body)
        .then((res) => {
          this.usersData = this.usersData.filter((user) => {
            return (user.id !== userId);
          });
          console.log(res.data.message);
        })
        .catch((error) => {
          this.errorMessage = error.message;
          console.error(
            `Failed to delete user ID for ${user.name}`,
            error
          );
        });
    },
  },
};
</script>
