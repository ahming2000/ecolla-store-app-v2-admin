<template>
  <div class="row mb-3">
    <table class="table table-light">
      <thead>
        <tr>
          <th scope="col" style="border: 3px solid lightgrey">功能</th>
          <th scope="col" style="border: 3px solid lightgrey">数值</th>
          <th scope="col" style="border: 3px solid lightgrey">操作</th>
        </tr>
      </thead>

      <tbody>
        <UtilTableRow @toggle-reset="resetUtil" :type="viewCount" />
        <UtilTableRow @toggle-reset="resetUtil" :type="sold" />
      </tbody>
    </table>
  </div>
</template>

<script>
import UtilTableRow from "./UtilTableRow";

export default {
  name: "UtilTable",
  props: {
    util: Object,
    itemId: Number,
  },

  mounted() {
    console.log("Util Table mounted.");
  },

  data() {
    return {
      viewCount: {
        funcName: "viewCount",
        name: "view_count",
        display: "浏览次数",
        value: this.util.view_count,
      },
      sold: {
        funcName: "sold",
        name: "sold",
        display: "销售量",
        value: this.util.sold,
      },
    };
  },

  components: {
    UtilTableRow,
  },

  methods: {
    resetUtil(funcName) {
      if (confirm("确定要重置 " + this[funcName].display + " 吗？")) {
        axios
          .post("/item/" + this.itemId + "/util/reset/" + this[funcName].name)
          .then((response) => {
            if (response.data) {
              this[funcName].value = 0;
              console.log("Reset " + this[funcName].name + " successfully!");
            } else {
              console.log("Reset " + this[funcName].name + " fail!");
            }
          });
      }
    },
  },

  computed: {},
};
</script>

<style scoped>
</style>
