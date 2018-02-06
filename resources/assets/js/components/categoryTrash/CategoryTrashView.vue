<template>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h4 class="pull-left">Deleted Categories</h4>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item clearfix" v-for="(category, index) in deletedCategories.data">
                           <span>{{category.code}} - {{category.name}} <small><i>(Deleted {{ category.diffForHumansDeletedAt }})</i></small></span> 
                           <div class="btn-group pull-right">
                                <button class="btn btn-info btn-xs" @click="(event) => { restoreCategory(category) }">Restore</button>
                                <button class="btn btn-danger btn-xs" @click="permanentlyDeleteCategory(category)">Delete Permanently</button>
                            </div>
                        </li>
                    </ul>
                    <div class="empty" v-show="deletedCategories.total === 0">No available Deleted Category.</div>
                    <div class="clearfix" v-show="deletedCategories.total > 0">
                        <p class="pull-left">Showing <b>{{ deletedCategories.from }}</b> to <b>{{ deletedCategories.to }}</b> of <b>{{ deletedCategories.total }}</b> entries</p>
                    </div>


                    <pagination :pagination="deletedCategories" v-show="deletedCategories.total > deletedCategories.per_page"
                     @paginate="getDeletedCategory()"
                     :offset="4">   
                    </pagination>
                    <div>
                        <a v-bind:href="backLink" class="btn btn-default"><i class="fa fa-fw fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import swal from 'sweetalert'
import CripNotice from 'crip-vue-notice'
import pagination from './../helper_component/Pagination.vue'

Vue.use(CripNotice)

export default {
    components:{
        'pagination': pagination
    },
    props:[
        'backLink',
        'baseUrl'
    ],
    data(){
        return{
            deletedCategories:{
                total:0,
                per_page:2,
                from:1,
                to:0,
                current_page:1
            },
            offset:4
        }
    },
    mounted(){
        this.getDeletedCategory()
    },
    created(){
    },
    methods:{
        notification(param){
            this.$notice[param.notice]({
                title: param.title,
                description: param.description,
                onClose() {
                // tslint:disable-next-line:no-console
                // console.log("Success notice now is closed")
                },
            });
        },
        permanentlyDeleteCategory(category){
            swal({
                title: "Permanently Delete?",
                text: category.name,
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(willDelete => {
                if(willDelete){

                    axios.delete(this.baseUrl+'/category/permanentlyDelete/'+category.id)
                    .then(response => {
                        if(response.data.status == 409){
                            swal(response.data.ErrMsg,
                            {
                                title: "Unable to delete",
                                icon:"error"
                            });
                        }else{
                            if(this.deletedCategories.data.length === 1){
                                this.deletedCategories.current_page = this.deletedCategories.current_page - 1
                            }
                            this.getDeletedCategory();
                            swal(category.name+" is successfully deleted!",
                            {
                                icon:"success"
                            });
                        }
                    });

                }
            });
        },
        restoreCategory(category){
            swal({
                title: "Restore "+category.name+"?",
                text: "Do you want to restore this category?",
                icon:"info",
                buttons:true,
            }).then(willRestore => {
                if(willRestore){

                axios.post(this.baseUrl+'/category/restore/'+category.id)
                .then(response => {
                    if(this.deletedCategories.data.length === 1){
                        this.deletedCategories.current_page = this.deletedCategories.current_page - 1
                    }
                    this.getDeletedCategory();
                    swal(category.name+" is successfully restored!",
                    {
                        icon:"success"
                    });
                    
                });
                   
                }
            });
        },
        getDeletedCategory(){
            axios.get(this.baseUrl+'/category/getSoftDeletesCategories?page='+this.deletedCategories.current_page)
            .then(response => {
                // console.log(response);
                this.deletedCategories = response.data
            });
        }
    }
}
</script>

<style>
.empty{
    text-align: center;
    padding:1em;
}
</style>
