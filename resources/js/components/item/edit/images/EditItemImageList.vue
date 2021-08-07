<style lang="scss">
.add-image {
  background-color: rgba(255, 255, 255, 0.5);
  width: 100%;
  padding-top: 100%; /* 1:1 Aspect Ratio */
  position: relative;
  border-radius: 15px;
  // box-shadow: 0 0 10px -2px hsla(0, 0%, 0%, 0.5);
  transition: all 0.3s ease;
  cursor: pointer;

  i {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    color: hsla(172, 100%, 47%, 0.5);
    font-size: 50px;
    transition: all 0.3s ease;
  }

  &:hover,
  &:active {
    background-color: #00eece;
    transition: all 0.3s ease;

    i {
      color: white;
      transition: all 0.3s ease;
    }
  }
}
</style>
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
        <input
          class="d-none"
          type="file"
          name="image"
          @change="onFileSelected($event)"
          ref="fileInput"
          accept="image/png, image/gif, image/jpeg, image/jpg"
        />
        <div class="add-image" @click.prevent="$refs.fileInput.click()">
          <i class="icofont icofont-ui-add"></i>
        </div>
      </div>
    </div>
    <!-- Delete Item Image Modal -->
    <delete-item-image-modal
      @onConfirmDelete="confirmDelete()"
    ></delete-item-image-modal>
    <!-- Upload Item Image Modal -->
    <upload-image-modal
      type="itemBasic"
      :rawImage="newImage"
      @onUpload="confirmUpload($event)"
      @onResponse="(...args) => onResponse(...args)"
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
    item_id: Number,
    images: Array,
  },

  data() {
    return {
      itemId: this.item_id ?? null,
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
        .patch(`/item/${this.itemId}/images`, body)
        .then((res) => {
          console.log(res);
          if (res.data.ok) {
            this.$emit("onResponse", res.data.messages, "success");
            this.itemImages = this.itemImages.filter(
              (image) => image !== this.selectedImage
            );
          } else {
            this.$emit("onResponse", res.data.messages, "error");
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
      event.target.value = "";
    },

    confirmUpload(newImageData) {
      console.log("confirmUpload()", newImageData);

      const body = {
        action: "add",
        image: newImageData,
      };
      console.log(body);
      axios
        .patch(`/item/${this.itemId}/images`, body)
        .then((res) => {
          console.log(res);
          if (res.data.ok) {
            this.$emit("onResponse", res.data.messages, "success");
            this.itemImages = [
              ...this.itemImages,
              {
                id: res.data.data.id,
                image: newImageData,
              },
            ];
          } else {
            this.$emit("onResponse", res.data.messages, "error");
          }
        })
        .catch((error) => {
          console.error(error);
          this.$emit("onResponse", error.message, "error");
        });
    },

    openUploadImageModal() {
      const uploadImageModal = new Modal(
        document.getElementById("itemBasicUploadImageModal")
      );
      uploadImageModal.show();
    },

    onResponse(...args) {
      this.$emit("onResponse", ...args);
    },
  },
};
</script>
