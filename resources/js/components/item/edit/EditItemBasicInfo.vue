<style lang="scss" scoped>
.no-rounded {
  border-radius: 0px;
}
</style>
<template>
  <div>
    <div class="container" style="margin-bottom: 100px">
      <div class="form-floating mb-3 w-100">
        <input
          :class="{ 'form-control': true, 'is-invalid': !this.itemName }"
          type="text"
          id="itemName"
          placeholder="商品名称"
          :value="itemName"
          @change="onChange($event, 'name')"
        />
        <label class="label" for="itemName">商品名称</label>
        <div class="invalid-feedback"><b>商品名称</b> 为必填选项</div>
      </div>
      <div class="form-floating mb-3 w-100">
        <input
          class="form-control"
          id="itemEnName"
          placeholder="Item Name"
          :value="itemEnName"
          @change="onChange($event, 'enName')"
        />
        <label class="label" for="itemEnName">Item Name</label>
      </div>
      <div class="form-floating mb-3 w-100">
        <textarea
          style="height: 200px"
          class="form-control"
          id="itemDescription"
          placeholder="商品描述 Item Description"
          :value="itemDescription"
          @change="onChange($event, 'description')"
        />
        <label class="label" for="itemDescription"
          >商品描述 Item Description</label
        >
      </div>
      <div class="row">
        <div class="col-6">
          <div class="form-floating mb-3 w-100">
            <input
              class="form-control"
              type="text"
              id="itemOrigin"
              placeholder="出产地"
              :value="itemOrigin"
              @change="onChange($event, 'origin')"
            />
            <label class="label" for="itemOrigin">出产地</label>
          </div>
        </div>
        <div class="col-6">
          <div class="form-floating mb-3 w-100">
            <input
              class="form-control"
              type="text"
              id="itemEnOrigin"
              placeholder="Item Origin"
              :value="itemEnOrigin"
              @change="onChange($event, 'enOrigin')"
            />
            <label class="label" for="itemEnOrigin">Item Origin</label>
          </div>
        </div>
      </div>
    </div>
    <button
      class="
        btn btn-primary
        container
        d-flex
        align-items-center
        justify-content-center
        fixed-bottom
        shadow-lg
        no-rounded
        min-vw-100
      "
      @click.prevent="updateBasicInfo()"
      :disabled="!isAllValid()"
      style="height: 80px; z-index: 10"
    >
      <p class="text-center fw-bolder text-white m-0">保存</p>
    </button>
  </div>
</template>


<script>
export default {
  name: "edit-item-basic-info",

  props: {
    item_info: Object,
  },

  data() {
    return {
      itemId: this.item_info.id ?? null,
      itemName: this.item_info.name ?? null,
      itemEnName: this.item_info.name_en ?? null,
      itemDescription: this.item_info.desc ?? null,
      itemOrigin: this.item_info.origin ?? null,
      itemEnOrigin: this.item_info.origin_en ?? null,
    };
  },

  computed: {
    classObject() {
      return {};
    },
  },

  methods: {
    updateBasicInfo() {
      console.log("updateBasicInfo()");

      if (!this.isAllValid()) {
        this.$emit("onResponse", "对不起", "error");
        return;
      }

      const body = {
        item_info: {
          name: this.itemName,
          name_en: this.itemEnName,
          desc: this.itemDescription,
          origin: this.itemOrigin,
          origin_en: this.itemEnOrigin,
        },
      };
      console.log(body);
      axios
        .patch(`/item/${this.itemId}/itemBasic`, body)
        .then((res) => {
          console.log(res);
          if (res.data.message !== "") {
            this.$emit("onResponse", res.data.message, "success");
          } else {
            this.$emit("onResponse", res.data.error, "error");
          }
        })
        .catch((error) => {
          console.error(error);
          this.$emit("onResponse", error.message, "error");
        });
    },

    onChange(event, name) {
      let newValue = event.target.value.trim();

      switch (name) {
        case "name": {
          this.itemName = newValue;
          break;
        }
        case "enName": {
          this.itemEnName = newValue;
          break;
        }
        case "description": {
          this.itemDescription = newValue;
          break;
        }
        case "origin": {
          this.itemOrigin = newValue;
          break;
        }
        case "enOrigin": {
          this.itemEnOrigin = newValue;
          break;
        }
        default: {
        }
      }
    },

    isAllValid() {
      return this.itemName;
    },
  },
};
</script>
