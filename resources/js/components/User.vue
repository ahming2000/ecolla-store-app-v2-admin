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
              data-target="#editAccountModal"
              @click.prevent="sendUserToModal(user)"
            >
              <p class="text-center m-0">编辑</p>
            </button>
          </div>
          <div class="col-4 d-flex">
            <form @submit.prevent="updateStatus(user)">
              <button
                type="submit"
                class="btn btn-md d-flex justify-content-center align-items-center text-nowrap"
                :class="classObject"
              >
                <p class="text-center m-0" v-text="buttonText">停用</p>
              </button>
            </form>
          </div>
          <div class="col-4 d-flex">
            <button
              type="submit"
              class="btn btn-danger btn-md d-flex justify-content-center align-items-center text-nowrap"
              data-toggle="modal"
              data-target="#deleteAccountModal"
              @click.prevent="sendUserToModal(user)"
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

  data() {
    return {
      userData: this.user,
    };
  },

  mounted() {
    console.log("User Component mounted.");
  },

  computed: {
    buttonText() {
      switch (this.userData.status) {
        case "enabled": {
          return "停用";
        }
        case "disabled": {
          return "激活";
        }
        default:
      }
    },
    classObject() {
      return {
        "btn-warning": this.userData.status == "enabled",
        "btn-success": this.userData.status == "disabled",
      };
    },
  },

  methods: {
    updateStatus(user) {
      let action;
      switch (this.user.status) {
        case "enabled": {
          action = "deactivate";
          break;
        }
        case "disabled": {
          action = "activate";
          break;
        }
        default:
      }

      const body = { action: action };

      axios
        .post(`/account/${user.id}`, body)
        .then((res) => {
          this.userData.status = res.data.user_status;
          console.log(res.data.message);
        })
        .catch((error) => {
          this.errorMessage = error.message;
          console.error(
            `Failed to update user status for ${this.user.name}`,
            error
          );
        });
    },
    sendUserToModal(user) {
      this.$emit("sendUserToModal", user);
    },
  },
};
</script>
