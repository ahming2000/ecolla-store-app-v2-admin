<template>
  <div class="container">
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
      <edit-item-image
        v-for="itemImage in itemImages"
        v-bind:key="itemImage.id"
        :image="itemImage"
        @onDelete="openDeleteModal($event)"
      ></edit-item-image>
      <div class="col">
        <form @submit.prevent="onSubmit">
          <input
            class="d-none"
            type="file"
            name="image"
            @change="onFileSelected($event)"
            ref="fileInput"
            accept="image/png, image/gif, image/jpeg, image/jpg"
          />
        </form>
        <img
          class="img-fluid w-100 h-100"
          src="http://management.ecolla.laravel:8081/img/alt/image-upload-alt.png"
          @click.prevent="$refs.fileInput.click()"
        />
      </div>
    </div>
    <!-- Delete Item Image Modal -->
    <delete-item-image-modal
      @onConfirmDelete="confirmDelete()"
    ></delete-item-image-modal>
    <!-- Upload Item Image Modal -->
    <upload-image-modal
      v-on:onUpload="confirmUpload($event)"
      :rawImage="newImage"
    ></upload-image-modal>
  </div>
</template>

<script>
import DeleteItemImageModal from "./DeleteItemImageModal.vue";
import EditItemImage from "./EditItemImage.vue";
import { Modal } from "bootstrap";
export default {
  components: { EditItemImage, DeleteItemImageModal },
  name: "edit-item-image-list",

  props: {
    images: Array,
  },

  data() {
    return {
      itemImages: this.images ?? [],
      selectedImage: null,
      newImage: null,
    };
  },

  methods: {
    openDeleteModal(image) {
      this.selectedImage = image;
    },

    confirmDelete() {
      console.log("confirmDelete", this.selectedImage);

      const body = {
        action: "delete",
        image: this.selectedImage.id,
      };
      console.log(body);
      axios
        .patch(`/item/${this.selectedImage.item_id}/images`, body)
        .then((res) => {
          console.log(res);
          if (res.data.message !== "") {
            this.$emit("onResponse", res.data.message, "success");
            this.itemImages = this.itemImages.filter(
              (image) => image !== this.selectedImage
            );
          } else {
            this.$emit("onResponse", res.data.error, "error");
          }
        })
        .catch((error) => {
          console.error(error);
          this.$emit("onResponse", error.message, "error");
        });
    },

    onFileSelected(event) {
      let newImage = event.target.files[0];
      console.log(newImage);
      this.newImage = newImage;
      this.openUploadImageModal();
    },

    openUploadImageModal() {
      const uploadImageModal = new Modal(
        document.getElementById("uploadImageModal")
      );
      uploadImageModal.show();
    },
  },
};
</script>
