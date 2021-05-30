<template>
  <li class="card my-2">
    <div class="card-body d-flex justify-content-center flex-wrap">
      <div class="col-md-8 d-flex align-items-center my-2">
        <div class="flex-column">
          <div class="row d-flex align-items-center">
            <h4 class="card-title m-0">{{ user.name }}</h4>
            <span
              v-if="user.status == 'disabled'"
              class="badge rounded-pill bg-warning shadow-none text-dark ml-2 px-2 h-75"
              >未激活</span
            >
          </div>
          <div class="row">
            <p class="card-subtitle m-0">{{ user.email }}</p>
          </div>
        </div>
      </div>
      <div
        class="flex-column d-flex align-items-center justify-content-center my-2"
      >
        <div class="row">
          <div class="col-4 d-flex">
            <button
              type="submit"
              class="btn btn-secondary btn-md d-flex justify-content-center align-items-center text-nowrap"
              data-toggle="modal"
            >
              <p class="text-center m-0">编辑</p>
            </button>
          </div>
          <div class="col-4 d-flex">
            <form
              @submit.prevent="_deactivate(user)"
              v-if="user.status == 'enabled'"
            >
              <button
                type="submit"
                class="btn btn-warning btn-md d-flex justify-content-center align-items-center text-nowrap"
              >
                <p class="text-center m-0">停用</p>
              </button>
            </form>
            <form
              @submit.prevent="_activate(user)"
              v-else-if="user.status == 'disabled'"
            >
              <button
                type="submit"
                class="btn btn-success btn-md d-flex justify-content-center align-items-center text-nowrap"
              >
                <p class="text-center m-0">激活</p>
              </button>
            </form>
          </div>
          <div class="col-4 d-flex">
            <button
              type="submit"
              class="btn btn-danger btn-md d-flex justify-content-center align-items-center text-nowrap"
              data-toggle="modal"
              data-target="#deleteAccountModal"
            >
              <p class="text-center m-0">删除</p>
            </button>
          </div>
        </div>
      </div>
    </div>
  </li>
</template>

<script>
export default {
  name: "user",
  props: {
    user: Object,
  },

  mounted() {
    console.log("User Component mounted.");
  },

  methods: {
    _activate(user) {
      console.log(`User status before post: ${user.status}`);
      const body = {action: 'activate'};
      axios
        .post(`/account/${user.id}`, body)
        .then((response) => console.log(response.data))
        .catch((error) => {
          this.errorMessage = error.message;
          console.error("Failed to deactivate user " + user.name, error);
        });

      // this.$emit("activate", user);
      // console.log("User " + user.name + " is activated");

      console.log(`User status after post: ${user.status}`);
    },

    _deactivate(user) {
      console.log(`User status before post: ${user.status}`);
      const body = {action: 'deactivate'};
      axios
        .post(`/account/${user.id}`, body)
        .then((response) => console.log(response.data))
        .catch((error) => {
          this.errorMessage = error.message;
          console.error("Failed to deactivate user " + user.name, error);
        });

      // this.$emit("deactivate", user);
      // console.log("User " + user.name + " is deactivated");

      console.log(`User status after post: ${user.status}`);
    },
  },
};
</script>
