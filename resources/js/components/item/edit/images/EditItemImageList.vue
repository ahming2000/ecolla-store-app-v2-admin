<template>
  <div class="container">
    <div class="row row-cols-2 g-4">
      <edit-item-image
        v-for="itemImage in itemImages"
        v-bind:key="itemImage.id"
        :image="itemImage"
        @onDelete="onDelete($event)"
      ></edit-item-image>
      <div class="col">
        <div class="position-relative">
          <img
            class="img-fluid border-2 rounded"
            src="http://management.ecolla.laravel:8081/img/alt/image-upload-alt.png"
            @click.prevent="onAdd()"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import EditItemImage from "./EditItemImage.vue";
export default {
  components: { EditItemImage },
  name: "edit-item-image-list",

  props: {
    images: Array,
  },

  data() {
    return {
      itemImages: this.images ?? [],
      selectedImage: null,
    };
  },

  computed: {
    classObject() {
      return {};
    },
  },

  methods: {
    onDelete(image) {
      this.selectedImage = image;
    },

    onAdd() {
      console.log("add");
    },
  },
};
</script>
