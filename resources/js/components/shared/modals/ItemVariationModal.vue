<template>
  <div
    v-if="action"
    class="modal fade"
    :id="action.modalId"
    tabindex="-1"
    role="dialog"
    :aria-labelledby="`${action.modalId}Label`"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" :id="`${action.modalId}Label`">
            {{ action.name }}规格
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div v-if="action.contentType === 'form'">
            Form
            {{ action.name }}
            {{ id }}
          </div>
          <div v-else-if="action.contentType === 'confirmation'">
            Confirmation
            {{ action.name }}
            {{ id }}
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            :class="`btn ${action.button.cancel.class} btn-md shadow-none`"
            data-dismiss="modal"
          >
            {{ action.button.cancel.name }}
          </button>
          <button
            type="submit"
            :class="`btn ${action.button.confirm.class} btn-md`"
            data-dismiss="modal"
            @click.prevent="onPrimaryPressed()"
          >
            {{ action.button.confirm.name }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "item-variation-modal",

  props: {
    variation: Object,
    action: Object,
  },

  data() {
    return {
      variationData: null,
    };
  },

  watch: {
    variation: function (val) {
      this.refetchVariationData();
    },
    action: function (val) {
      // if(this.action.)
    },
  },

  /**
   * Because Vue doesn't support null safety in binding expression,
   *
   * e.g.
   * {{ parent?.child }}
   *
   * so I have to use this way to workaround
   */
  computed: {
    id: function () {
      return this.variationData === null ? null : this.variationData.id;
    },
  },

  methods: {
    onPrimaryPressed() {
      console.log("onPrimaryPressed()");
      switch (this.action.value) {
        case "add": {
          this.$emit("onSaveAdd", this.variationData);
          break;
        }
        case "edit": {
          this.$emit("onSaveEdit", this.variationData);
          break;
        }
        case "delete": {
          this.$emit("onConfirmDelete", this.variationData);
          break;
        }
      }
    },

    refetchVariationData() {
      this.variationData = null;
      this.variationData = this.variation;
    },
  },
};
</script>