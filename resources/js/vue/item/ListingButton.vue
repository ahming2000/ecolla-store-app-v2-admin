<template>
    <div>
        <div class="custom-control custom-switch" @click="listItem">
            <input type="checkbox" class="custom-control-input" v-bind:checked="checkBoxValue">
            <label class="custom-control-label"></label>
        </div>
    </div>
</template>

<script>
export default {
    name: "ListingButton",
    props: [
        'itemId',
        'isListed',
    ],

    mounted() {
        console.log('Listing Button Component mounted.')
    },

    data: function () {
        return {
            status: this.isListed === "1",
        }
    },

    methods: {
        listItem(){
            axios.post('/item/' + this.itemId + '/list')
            .then(response => {
                if(response.data){
                    this.status = !this.status;
                    console.log('listing success');
                } else{
                    alert("上架失败！请确保当前商品达成以下条件：\n商品资料完整（名称、描述、出产地）\n至少要有一个规格（名称、货号、价格）\n至少要有一个封面图")
                    console.log('listing fail');
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
