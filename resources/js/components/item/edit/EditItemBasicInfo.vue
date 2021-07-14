<template>
  <div class="container">
    <div class="form-floating mb-3 w-100">
      <input
        class="form-control"
        type="text"
        id="itemName"
        placeholder="商品名称"
        :value="itemName"
        v-on:change="onChange($event, 'name')"
      />
      <label class="label" for="itemName">商品名称</label>
    </div>
    <div class="form-floating mb-3 w-100">
      <input
        class="form-control"
        type="text"
        id="itemEnName"
        placeholder="Item Name"
        :value="itemEnName"
        v-on:change="onChange($event, 'enName')"
      />
      <label class="label" for="itemEnName">Item Name</label>
    </div>
    <div class="form-floating mb-3 w-100">
      <textarea
        style="height: 200px"
        class="form-control"
        id="itemDescription"
        placeholder="Item Description"
        :value="itemDescription"
        v-on:change="onChange($event, 'description')"
      />
      <label class="label" for="itemDescription">Item Description</label>
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
            v-on:change="onChange($event, 'origin')"
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
            v-on:change="onChange($event, 'enOrigin')"
          />
          <label class="label" for="itemEnOrigin">Item Origin</label>
        </div>
      </div>
    </div>
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
        class="btn btn-primary w-100"
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
      const body = {
        item_info: {
          name: this.itemName,
          name_en: this.itemEnName,
          desc: this.itemDescription,
          origin: this.itemOrigin,
          origin_en: this.itemEnOrigin,
        }
      };
      console.log(body);
      axios
        .patch(`/item/${this.itemId}/itemBasic`, body)
        .then((res) => {
          console.log(res);
          if(res.data.message !== "") {
              this.$emit("onResponse", res.data.message);
            } else {
              this.$emit("onResponse", res.data.error);
            }
        })
        .catch((error) => {
          onsole.error(error);
          this.$emit("onResponse", error.message);
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
  },
};
</script>
