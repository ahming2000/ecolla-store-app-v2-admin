<style lang="scss" scoped>
.add-variation {
  background-color: rgba(255, 255, 255, 0.5);
  width: 100%;
  padding-top: 50%; /* 2:1 Aspect Ratio */
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
  <div>
    <div class="container">
      <edit-item-variation
        v-for="variation in variationList"
        v-bind:key="variation.id"
        :variation="variation"
        v-on:onEdit="openEditModal($event)"
        v-on:onDelete="openDeleteModal($event)"
      ></edit-item-variation>

      <div
        class="add-variation"
        @click.prevent="openAddModal()"
        data-bs-toggle="modal"
        data-bs-target="#itemVariationModal"
      >
        <i class="icofont icofont-ui-add"></i>
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
    item_id: Number,
    variations: Array,
  },

  data() {
    return {
      itemId: this.item_id ?? null,
      variationList: this.variations ?? [],
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

      const body = {
        action: "delete",
        variation: this.selectedVariation,
      };
      console.log(body);
      axios
        .patch(`/item/${this.itemId}/variation`, body)
        .then((res) => {
          console.log(res);
          if (res.data.message !== "") {
            this.$emit("onResponse", res.data.message, "success");
            this.variationList = this.variationList.filter(
              (variation) => variation.id !== this.selectedVariation.id
            );
            this.selectedVariation = null;
          } else {
            this.$emit("onResponse", res.data.error, "error");
          }
        })
        .catch((error) => {
          console.error(error);
          this.$emit("onResponse", error.message, "error");
        });
    },

    confirmUpload(image) {
      console.log("confirmUpload()", image);
      // TODO Upload Image
      // TODO Update Variation Image
    },
  },
};
</script>