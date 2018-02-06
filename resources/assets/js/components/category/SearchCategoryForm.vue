<template>
    <div>
        <form method="POST" role="search"  @submit.prevent="searchCategories">
            <div class="input-group">
                <input type="text" class="form-control" name="q" v-model="search"
                    @keyup="searchCategories"
                    placeholder="Search category" style="border-radius:0px;"> <span class="input-group-btn">
                    <button type="submit" class="btn btn-default" style="border-radius:0px;">
                        <i class="fa fa-fw fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
    </div>
</template>


<style>

</style>


<script>
    export default{
        data(){
            return {
                search:''
            }
        },
        methods:{
            searchCategories(){
               this.$emit('search-categories', this.search)
            }
        }

    }
</script>