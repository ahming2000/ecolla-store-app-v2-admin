<template>
  <div class="container">
    <div
      class="form-check"
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
        {{ category.name }}
      </label>
    </div>

    <div
      class="
        container
        d-flex
        align-items-center
        justify-content-center
        fixed-bottom
      "
      style="height: 100px; background-color: white"
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
          // TODO Success Message
        })
        .catch((error) => {
          console.error(error);
          // TODO Error Message
        });
    },
  },
};
</script>
