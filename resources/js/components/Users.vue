<template>
  <div>
    <ul v-for="user in users" :key="user.id" class="list-group">
      <user v-bind:user="user" v-on:sendUserToModal="sendUserToModal($event)" />
    </ul>
    <!-- Delete Account Modal -->
    <delete-user v-bind:user="selectedUser"></delete-user>
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
  },
};
</script>
