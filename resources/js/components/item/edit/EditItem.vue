<template>
  <div>
    <!-- Tab Panel -->
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item" role="presentation">
        <a
          class="nav-link active"
          id="pills-basic-info-tab"
          data-toggle="pill"
          href="#pills-basic-info"
          role="tab"
          aria-controls="pills-basic-info"
          aria-selected="true"
          >基本资讯</a
        >
      </li>
      <li class="nav-item" role="presentation">
        <a
          class="nav-link"
          id="pills-category-tab"
          data-toggle="pill"
          href="#pills-category"
          role="tab"
          aria-controls="pills-category"
          aria-selected="false"
          >商品类别/标签</a
        >
      </li>
      <li class="nav-item" role="presentation">
        <a
          class="nav-link"
          id="pills-variation-tab"
          data-toggle="pill"
          href="#pills-variation"
          role="tab"
          aria-controls="pills-variation"
          aria-selected="false"
          >规格资讯</a
        >
      </li>
      <li class="nav-item" role="presentation">
        <a
          class="nav-link"
          id="pills-wholesale-discount-tab"
          data-toggle="pill"
          href="#pills-wholesale-discount"
          role="tab"
          aria-controls="pills-wholesale-discount"
          aria-selected="false"
          >批发折扣管理</a
        >
      </li>
      <li class="nav-item" role="presentation">
        <a
          class="nav-link"
          id="pills-util-tab"
          data-toggle="pill"
          href="#pills-util"
          role="tab"
          aria-controls="pills-util"
          aria-selected="false"
          >其他商品设定</a
        >
      </li>
    </ul>

    <!-- Content -->
    <div class="tab-content" id="pills-tabContent">
      <div
        class="tab-pane fade show active"
        id="pills-basic-info"
        role="tabpanel"
        aria-labelledby="pills-basic-info-tab"
      >
        <edit-item-basic-info
          :item_info="item_info"
          :images="item.images"
        ></edit-item-basic-info>
      </div>
      <div
        class="tab-pane fade"
        id="pills-category"
        role="tabpanel"
        aria-labelledby="pills-category-tab"
      >
        <edit-item-category :categories="item.categories"></edit-item-category>
      </div>
      <div
        class="tab-pane fade"
        id="pills-variation"
        role="tabpanel"
        aria-labelledby="pills-variation-tab"
      >
        <edit-item-variation-list
          :variations="item.variations"
        ></edit-item-variation-list>
      </div>
      <div
        class="tab-pane fade"
        id="pills-wholesale-discount"
        role="tabpanel"
        aria-labelledby="pills-wholesale-discount-tab"
      >
        <edit-item-wholesale-discount-list
          :wholesale_discounts="item.discounts"
          :orignal_price="item.variations[0].price"
        ></edit-item-wholesale-discount-list>
      </div>
      <div
        class="tab-pane fade"
        id="pills-util"
        role="tabpanel"
        aria-labelledby="pills-util-tab"
      >
        <util-table :util="item.util" :itemId="item.id"></util-table>
      </div>
    </div>
  </div>
</template>


<script>
import EditItemBasicInfo from "./EditItemBasicInfo.vue";
import EditItemCategory from "./EditItemCategory.vue";
import UtilTable from "./UtilTable.vue";
import EditItemVariationList from "./variations/EditItemVariationList.vue";
import EditItemWholesaleDiscountList from "./wholesales/EditItemWholesaleDiscountList.vue";

export default {
  name: "edit-item",

  props: {
    item: Object,
  },

  data() {
    return {
      item_info: {
        id: this.item.id,
        name: this.item.name,
        name_en: this.item.name_en,
        origin: this.item.origin,
        origin_en: this.item.origin_en,
        created_at: this.item.created_at,
        updated_at: this.item.updated_at,
      },
    };
  },

  components: {
    EditItemBasicInfo,
    EditItemCategory,
    EditItemVariationList,
    EditItemWholesaleDiscountList,
    UtilTable,
    // TODO All EditItem Components
  },

  computed: {
    classObject() {
      return {};
    },
  },

  methods: {},
};
</script>
