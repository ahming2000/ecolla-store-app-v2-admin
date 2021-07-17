<style scoped>
.btn-circle-60 {
  width: 60px;
  height: 60px;
  padding: 0px 0px 0px 0px;
  border-radius: 50px;
  font-size: 18px;
  text-align: center;
}
</style>

<template>
  <div>
    <div class="container">
      <edit-item-variation
        v-for="variation in variations"
        v-bind:key="variation.id"
        :variation="variation"
        v-on:onEdit="openEditModal($event)"
        v-on:onDelete="openDeleteModal($event)"
      ></edit-item-variation>

      <div class="d-flex justify-content-center mt-3">
        <button
          class="btn btn-outline-primary btn-circle-60 shadow-none"
          type="submit"
          data-bs-toggle="modal"
          data-bs-target="#itemVariationModal"
          @click.prevent="openAddModal()"
        >
          <i class="icofont icofont-ui-add icofont-2x"></i>
        </button>
      </div>
    </div>

    <!-- Add/Edit/Delete Modal -->
    <item-variation-modal
      :action="action"
      :variation="selectedVariation"
      v-on:onImageSelect="onImageSelect($event)"
      v-on:onSaveAdd="saveAdd($event)"
      v-on:onSaveEdit="saveEdit($event)"
      v-on:onConfirmDelete="confirmDelete($event)"
    ></item-variation-modal>

    <!-- Upload Image Modal -->
    <button
      class="d-none"
      type="submit"
      data-bs-toggle="modal"
      data-bs-target="#uploadImageModal"
      ref="imagePreviewButton"
    ></button>
    <upload-image-modal
      v-on:onUpload="confirmUpload($event)"
      :rawImage="processedImage"
    ></upload-image-modal>
  </div>
</template>

<script>
import ItemVariationModal from "../../../shared/modals/ItemVariationModal.vue";
import UploadImageModal from "../../../shared/modals/UploadImageModal.vue";
import EditItemVariation from "./EditItemVariation.vue";
export default {
  name: "edit-item-variation-list",

  components: { EditItemVariation, ItemVariationModal, UploadImageModal },

  props: {
    variations: Array,
  },

  data() {
    return {
      action: null,
      selectedVariation: null,
      selectedImage: null,
      processedImage: null,
    };
  },

  methods: {
    openAddModal() {
      console.log("openAddModal()");

      this.action = {
        name: "添加",
        enName: "Add",
        value: "add",
        contentType: "form",
        button: {
          confirm: {
            name: "添加",
            enName: "Add",
            class: "btn-primary",
          },
          cancel: {
            name: "取消",
            enName: "Cancel",
            class: "btn-outline-danger",
          },
        },
      };

      this.selectedVariation = null;

      console.log(this.action);
    },

    openEditModal(variation) {
      console.log("openEditModal()");

      this.action = {
        name: "编辑",
        enName: "Edit",
        value: "edit",
        contentType: "form",
        button: {
          confirm: {
            name: "保存",
            enName: "Save",
            class: "btn-primary",
          },
          cancel: {
            name: "取消",
            enName: "Cancel",
            class: "btn-outline-danger",
          },
        },
      };

      this.selectedVariation = variation;

      console.log(this.action, this.selectedVariation);
    },

    openDeleteModal(variation) {
      console.log("openDeleteModal()");

      this.action = {
        name: "删除",
        enName: "Delete",
        value: "delete",
        contentType: "confirmation",
        button: {
          confirm: {
            name: "删除",
            enName: "Delete",
            class: "btn-danger",
          },
          cancel: {
            name: "取消",
            enName: "Cancel",
            class: "btn-outline-primary",
          },
        },
      };

      this.selectedVariation = variation;

      console.log(this.action, this.selectedVariation);
    },

    onImageSelect(rawImage) {
      this.selectedImage = rawImage;
      this.processImage(this.selectedImage);
    },

    processImage(rawImage) {
      // TODO Call BE to process raw image and return processed image
      this.processedImage = rawImage;
      this.$refs.imagePreviewButton.click();
    },

    saveAdd(variation) {
      console.log("saveAdd()");

      console.log(variation);

      // TODO Save Added Variation
      // TODO Update Variations
    },

    saveEdit(variation) {
      console.log("saveEdit()");

      this.selectedVariation = variation;

      console.log(this.selectedVariation);

      // TODO Save Edited Variation
      // TODO Update Variations
    },

    confirmDelete(variation) {
      console.log("confirmDelete()");

      this.selectedVariation = variation;

      console.log(this.selectedVariation);

      // TODO Delete Variation
      // TODO Update Variations
    },

    confirmUpload(image) {
      console.log("confirmUpload()", image);
      // TODO Upload Image
      // TODO Update Variation Image
    },
  },
};
</script>