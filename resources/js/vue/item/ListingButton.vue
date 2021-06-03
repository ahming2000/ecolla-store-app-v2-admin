<template>
    <div>
        <button class="btn btn-sm mb-1" @click="listItem" :class="classObject">
            <i class="icofont icofont-basket"></i> <span v-text="buttonText"></span>
        </button>
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
        buttonText() {
            return this.status ? '下架' : '上架';
        },
        classObject() {
            return {
                'btn-danger': !this.status,
                'btn-success': this.status,
            }
        }
    },
}
</script>
