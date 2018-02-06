<template>
  <form method="POST" @submit.prevent="searchItem">
        <div class="input-group">
            <input type="text" class="form-control" name="q" v-model="search"
                @keyup="searchItem"
                placeholder="Search items" style="border-radius:0px;"> <span class="input-group-btn">
                <button type="submit" class="btn btn-default" style="border-radius:0px;">
                    <i class="fa fa-fw fa-search"></i>
                </button>
            </span>
        </div>
  </form>
</template>

<script>
export default {
    data(){
        return{
            search:''
        }
    },
    methods:{
        searchItem(){
            this.$emit('search-item', this.search);
        }
    }
}
</script>

<style>

</style>
