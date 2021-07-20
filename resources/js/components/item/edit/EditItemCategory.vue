<style lang="scss" scoped>
input[type="checkbox"] {
  display: none;

  + label {
    background-color: hsl(46, 100%, 50%);
    border: 2px solid white;
    border-radius: 30px;
    padding: 3px;
    padding-right: 10px;
    color: white;
    position: relative;
    width: 75%;
    margin-bottom: 10px;
    transition: all 0.3s ease;

    span {
      position: absolute;
      left: 0%;
      top: 50%;
      transform: translate(-50%, -50%);
      background-color: white;
      border: 2px solid hsl(46, 100%, 50%);
      border-radius: 50%;
      padding: 5px;
      width: 40px;
      height: 40px;
      vertical-align: middle;
      text-align: center;
      transition: all 0.3s ease;

      i {
        font-size: 18px;
        color: #969696;
        opacity: 0;
        transition: all 0.3s ease;
      }
    }

    p {
      text-align: end;
      margin: auto;
    }
  }

  &:checked + label {
    background-color: hsl(103, 100%, 36%);
    transition: all 0.3s ease;
    span {
      border: 2px solid hsl(46, 100%, 50%);
      transition: all 0.3s ease;
      i {
        opacity: 1;
        transition: all 0.3s ease;
      }
    }
  }
}
</style>
<template>
  <div>
    <div class="container" style="margin-bottom: 100px">
      <div
        class="form-check d-flex justify-content-center"
        v-for="category in allCategories"
        v-bind:key="category.id"
      >
        <input
          class="form-check-input"
          type="checkbox"
          :value="category.id"
          :id="`category${category.id}`"
          v-model="checkedCategories"
        />

        <label class="form-check-label" :for="`category${category.id}`">
          <span>
            <i class="icofont icofont-book-mark"></i>
          </span>
          <p>{{ category.name }}</p>
        </label>
      </div>
    </div>
    <div
      class="
        container
        d-flex
        align-items-center
        justify-content-center
        fixed-bottom
        shadow-lg
      "
      style="height: 100px; background-color: white; z-index: 10"
    >
      <button
        class="btn btn-primary w-100"
        type="submit"
        @click.prevent="updateCategory()"
      >
        保存
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "edit-item-category",

  props: {
    item_id: Number,
    allCategories: Array,
    categories: Array,
  },

  data() {
    return {
      itemId: this.item_id ?? null,
      checkedCategories: this.categories.map((category) => category.id),
    };
  },

  computed: {
    classObject() {
      return {};
    },
  },

  methods: {
    updateCategory() {
      const body = {
        categories: this.checkedCategories,
      };

      console.log(body);
      axios
        .patch(`/item/${this.itemId}/category`, body)
        .then((res) => {
          console.log(res);
          if (res.status === 200) {
            if (res.data.message !== "") {
              this.$emit("onResponse", res.data.message, "success");
            } else {
              this.$emit("onResponse", res.data.error, "error");
            }
          }
        })
        .catch((error) => {
          console.error(error);
          this.$emit("onResponse", error.message, "error");
        });
    },
  },
};
</script>
