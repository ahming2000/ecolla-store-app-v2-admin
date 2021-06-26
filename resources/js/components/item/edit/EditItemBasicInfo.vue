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
        class="btn btn-primary btn-block"
        type="submit"
        @click.prevent="updateBasicInfo()"
      >
        保存
      </button>
    </div>
  </div>
</template>


<script>
export default {
  name: "edit-item-basic-info",

  props: {
    item_info: Object,
    images: Array,
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
    updateBasicInfo() {
      console.log('updateBasicInfo()')
    },
  },
};
</script>
