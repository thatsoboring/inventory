<template>
    <ul class="list-group">
        <li class="list-group-item clearfix" v-for="(category, index) in categories.data">
            <span>{{ category.code }} - {{category.name}}</span>
            <div class="btn-group pull-right">
                <button class="btn btn-info btn-xs" @click="(event) => { showCategory(category, index) }">Edit</button>
                <button class="btn btn-danger btn-xs" @click="deleteCategory(category)">Delete</button>
            </div>
        </li>
    </ul>
</template>


<style>

</style>


<script>
    export default{
        props: ['categories'],
        methods:{
            showCategory(category,index){
                this.$emit('show-category', {category, index})
            },
            deleteCategory(category){
                this.$emit('delete-category', category)
            }
        }
    }
</script>