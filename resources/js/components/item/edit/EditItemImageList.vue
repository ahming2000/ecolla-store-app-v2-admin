<template>
  <div class="container">

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
    
  },
};
</script>
