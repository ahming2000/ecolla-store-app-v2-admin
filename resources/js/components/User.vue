<template>
  <li class="card my-2">
    <div class="card-body d-flex justify-content-center flex-wrap">
      <div class="col-12 col-lg-8 d-flex align-items-center my-2">
        <div class="flex-column">
          <div class="d-flex align-items-center">
            <h4 class="card-title m-0">{{ user.name }}</h4>
            <span
              v-if="user.status == 'disabled'"
              class="badge rounded-pill bg-warning text-white ms-2"
              >未激活</span
            >
          </div>
          <div class="row">
            <p class="card-subtitle text-secondary m-0">{{ user.email }}</p>
          </div>
        </div>
      </div>
      <div
        class="
          col-12 col-lg-4
          d-flex
          align-items-center
          justify-content-center
          my-2
        "
      >
        <div class="row">
          <div class="col-4 d-flex">
            <button
              type="submit"
              class="
                btn btn-primary btn-md
                d-flex
                justify-content-center
                align-items-center
                text-nowrap
              "
              data-bs-toggle="modal"
              data-bs-target="#editAccountModal"
              @click.prevent="sendUserToParent()"
              :disabled="user.status == 'disabled'"
            >
              <p class="text-center m-0">编辑</p>
            </button>
          </div>
          <div class="col-4 d-flex">
            <button
              class="
                btn
                d-flex
                justify-content-center
                align-items-center
                text-nowrap
              "
              :class="classObject"
              @click.prevent="updateStatus()"
            >
              <p class="text-center m-0" v-text="buttonText">停用</p>
            </button>
          </div>
          <div class="col-4 d-flex">
            <button
              type="submit"
              class="
                btn btn-danger btn-md
                d-flex
                justify-content-center
                align-items-center
                text-nowrap
              "
              data-bs-toggle="modal"
              data-bs-target="#deleteAccountModal"
              @click.prevent="sendUserToParent()"
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
    updateStatus() {
      let action;
      switch (this.userData.status) {
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

      const data = { user: this.user, action: action };

      this.$emit("updateStatus", data);
    },
    sendUserToParent() {
      this.$emit("sendUserToParent", this.userData);
    },
  },
};
</script>
