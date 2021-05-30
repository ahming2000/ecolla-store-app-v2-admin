<template>
  <div>
    <ul v-for="user in users" :key="user.id" class="list-group">
      <User :user="user" />
    </ul>
  </div>
</template>

<script>
import User from "./User";

export default {
  name: "users",
  props: {
    users: Array,
  },
  components: {
    User,
  },

  mounted() {
    console.log("Users Component mounted.");
  },
};
</script>
