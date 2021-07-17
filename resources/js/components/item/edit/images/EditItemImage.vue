<template>
  <div class="col">
    <div class="position-relative" v-if="itemImage">
      <img class="img-fluid rounded" :src="itemImage" />
      <button
        class="
          btn btn-danger
          rounded-circle
          position-absolute
          top-0
          start-100
          translate-middle
          d-flex
          justify-content-center
          align-items-center
        "
        style="height: 30px; width: 30px"
        type="submit"
        data-bs-toggle="modal"
        data-bs-target="#deleteItemImageModal"
        @click.prevent="onDelete()"
      >
        <i class="icofont icofont-close-line" style="font-size: 20px"></i>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "edit-item-image",

  props: {
    image: Object,
  },

  data() {
    return {
      itemImage: this.image?.image ?? null,
    };
  },

  computed: {
    classObject() {
      return {};
    },
  },

  methods: {
    onDelete() {
      this.$emit("onDelete", this.image);
    },
  },
};
</script>
