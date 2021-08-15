<template>
    <div>
        <div class="form-control form-switch" @click="listItem" style="border: 0;">
            <input type="checkbox" class="form-check-input" :checked="checkBoxValue" style="pointer-events:none">
            <label class="form-check-label"></label>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListingSwitch",
    props: {
        'itemId': String,
        'isListed': String
    },

    mounted() {
        console.log('Listing Switch Component mounted.')
    },

    data() {
        return {
            status: this.isListed === "1",
        }
    },

    methods: {
        listItem() {
            axios.post('/item/' + this.itemId + '/list')
                .then(response => {
                    if (response.data) {
                        this.status = !this.status;
                        console.log('Item ' + this.itemId + ' list/unlist successfully!');
                    } else {
                        alert("上架失败！请确保当前商品达成以下条件：\n商品资料完整（名称、描述、出产地）\n至少要有一个规格（名称、货号）")
                        console.log('Item ' + this.itemId + ' list failed!');
                    }
                });
        },
    },

    computed: {
        checkBoxValue() {
            return this.status ? 'checked' : '';
        },
    },
}
</script>
