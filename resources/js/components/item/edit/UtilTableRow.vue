<template>
    <tr>
        <th scope="row" style="border: 3px solid lightgrey;">{{ type.display }}</th>
        <td style="border: 3px solid lightgrey;">
            <input type="text"
                   class="form-control form-control-sm"
                   :value="type.value"
                   disabled>
        </td>
        <td class="" style="border: 3px solid lightgrey;">
            <button type="submit"
                    class="btn btn-primary btn-sm m-0" @click="$emit('toggle-reset', type.funcName)" :disabled="buttonStatus">
                重置
            </button>
        </td>
    </tr>
</template>

<script>
export default {
    name: "UtilTableRow",
    props: {
        'type': Object
    },
    data() {
        return {

        }
    },

    mounted() {
        console.log('Util Table Row ' + this.type.name + ' mounted.')
    },

    computed: {
        buttonStatus() {
            return this.type.value === 0;
        }
    }
}
</script>

<style scoped>

</style>
