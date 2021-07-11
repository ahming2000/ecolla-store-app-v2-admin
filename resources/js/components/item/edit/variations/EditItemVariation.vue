<template>
  <li class="card my-2">
    <div class="card-body d-flex justify-content-center flex-wrap">
      <div class="d-flex row mb-3">
        <div class="col-4">
          <img
            v-if="variation.image !== null && variation.image !== undefined"
            class="img-thumbnail img-fluid rounded mr-3"
            :src="variation.image"
            height="200px"
            width="200px"
          />
          <!-- TODO Image Placeholder -->
          <img
            v-else
            class="img-thumbnail img-fluid rounded mr-3"
            src="http://management.ecolla.laravel:8081/img/icon/ecolla_icon.png"
            height="200px"
            width="200px"
          />
        </div>
        <div class="col-8">
          <h5>{{ variation.name }}</h5>
          <p>{{ variation.name_en }}</p>
          <!-- Original Price -->
          <span class="badge rounded-pill bg-success shadow-none p-2 mr-3">
            RM {{ variation.price.toFixed(2) }}
          </span>
          <!-- Discounted Price -->
          <span
            v-if="variation.discount"
            class="badge rounded-pill bg-success shadow-none p-2"
          >
            RM ???
          </span>
        </div>
      </div>
      <div class="row justify-content-center w-100">
        <div class="col-6">
          <button
            class="btn btn-primary w-100"
            type="submit"
            data-bs-toggle="modal"
            data-bs-target="#itemVariationModal"
            @click.prevent="onEdit()"
          >
            编辑
          </button>
        </div>
        <div class="col-6">
          <button
            class="btn btn-danger w-100"
            type="submit"
            data-bs-toggle="modal"
            data-bs-target="#itemVariationModal"
            @click.prevent="onDelete()"
          >
            删除
          </button>
        </div>
      </div>
    </div>
  </li>
</template>

<script>
export default {
  name: "edit-item-variation",

  props: {
    variation: Object,
  },

  data() {
    return {};
  },

  computed: {
    classObject() {
      return {};
    },
  },

  methods: {
    onEdit() {
      this.$emit("onEdit", this.variation);
    },

    onDelete() {
      this.$emit("onDelete", this.variation);
    },
  },
};
</script>