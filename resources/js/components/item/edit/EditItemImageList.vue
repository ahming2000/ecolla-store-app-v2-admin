<template>
  <div class="container">
    

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
        @click.prevent="updateItemImages()"
      >
        保存
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "edit-item-image-list",

  props: {
    images: Array,
  },

  data() {
    return {
      itemImages: this.images,
    };
  },

  computed: {
    classObject() {
      return {};
    },
  },

  methods: {
    updateItemImages() {
      console.log('iamges', this.images);
      // TODO call BE with Axios
    },
  },
};
</script>
