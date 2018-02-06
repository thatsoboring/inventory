<template>
    <table class="table table-condensed table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in items.data" v-if="items.total != 0">
                <td>{{ increment(index, items.from) }}</td>
                <td>{{ item.code }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.category.name }}</td>
                <td>
                    <div class="btn-group" v-if="!item.category.deleted_at">
                        <button class="btn btn-info btn-xs" @click="(event) => { showItem(item, index) }">Edit</button>
                        <button class="btn btn-danger btn-xs" @click="deleteItem(item)">Delete</button>
                    </div>
                    <div v-if="item.category.deleted_at">
                        <small>Category has been deleted!
                        <br /> Restore to make action on this item.</small>
                    </div>
                </td>
            </tr>
            <tr v-if="items.total === 0">
                <td colspan="5" class="text-center"><p>No Available Items</p></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props:['items'],
    data(){
        return{
            counter: 0
        }
    },
    methods:{
        increment(index, from){
            let num = index;
            return num = num + from;
        },
        deleteItem(item){
            this.$emit('delete-item', item);
        },
        showItem(item, index){
           this.$emit('show-item',{item, index})
        }
    }
}
</script>

<style>
    .table tbody tr td{
        vertical-align: middle;
    }
</style>
