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
        class="btn btn-primary btn-block"
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
    allCategories: Array,
    categories: Array,
  },

  data() {
    return {
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
      console.log('checkedCategories', this.checkedCategories);
      // TODO call BE with Axios
    },
  },
};
</script>
