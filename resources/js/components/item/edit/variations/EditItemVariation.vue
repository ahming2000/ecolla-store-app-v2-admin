<template>
  <li class="card my-2">
    <div class="card-body d-flex justify-content-center flex-wrap">
      <div class="col">
        <div class="d-flex row">
          <div class="media">
            <img
              v-if="variation.image !== null && variation.image !== undefined"
              class="figure-img img-fluid rounded mr-3"
              :src="variation.image"
              height="100px"
              width="100px"
            />
            <!-- TODO Image Placeholder -->
            <img
              v-else
              class="figure-img img-fluid rounded mr-3"
              src="http://management.ecolla.laravel:8081/img/icon/ecolla_icon.png"
              height="100px"
              width="100px"
            />
          </div>
          <div class="media-body">
            <h5>{{ variation.name }}</h5>
            <p>{{ variation.name_en }}</p>
            <!-- Original Price -->
            <span class="badge badge-pill badge-primary shadow-none p-2 mr-3">
              RM {{ variation.price.toFixed(2) }}
            </span>
            <!-- Discounted Price -->
            <span class="badge badge-pill badge-primary shadow-none p-2">
              RM ???
            </span>
          </div>
        </div>
        <div class="d-flex row justify-content-around mt-3">
          <button
            class="btn btn-primary"
            type="submit"
            data-toggle="modal"
            data-target="#editItemVariationModal"
            @click.prevent="onEdit()"
          >
            编辑
          </button>
          <button
            class="btn btn-danger"
            type="submit"
            data-toggle="modal"
            data-target="#deleteItemVariationModal"
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