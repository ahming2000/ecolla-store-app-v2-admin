<template>
  <div class="container">
      <edit-item-variation v-for="variation in variations" v-bind:key="variation.id" :variation="variation"></edit-item-variation>
  </div>
</template>

<script>
import EditItemVariation from './EditItemVariation.vue';
export default {
  name: "edit-item-variation-list",

  props: {
    variations: Array,
  },

  data() {
    return {};
  },

  components: { EditItemVariation },

  computed: {
    classObject() {
      return {};
    },
  },

  methods: {},
};
</script>