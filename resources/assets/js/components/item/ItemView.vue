<template>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h4 class="pull-left">List of Items</h4>
                    <span class="pull-right" style="margin: 5px 0px 5px 0px;">
                        <button class="btn btn-primary btn-sm" @click="openModal(true)">Add New Item </button>
                    </span>
                </div>
                <div class="panel-body">

                    <search-item-form
                    @search-item="searchItem"></search-item-form>

                    <list-of-items 
                        :items = "items"
                        @delete-item="deleteItem"
                        @show-item="showItem"
                    ></list-of-items>

                    <div class="clearfix">
                        <p class="pull-left">Showing <b>{{ items.from }}</b> to <b>{{ items.to }}</b> of <b>{{ items.total }}</b> entries</p>
                        <a v-bind:href="baseUrl+'/item/trash'" class="btn btn-default pull-right btn-sm"><i class="fa fa-fw fa-trash"></i> Trash</a>
                    </div>

                    <pagination :pagination="items" @paginate="getItems()" :offset="4"
                    ></pagination>
                    
                </div>
            </div>
        </div>

        <modal-form
            :item = "item"
            :categories = "categories"
            :isUpdate = "isUpdate"
            :formErrors = "formErrors"
            :showModal = "showModal"
            @close-modal = "openModal(false)"
            @submit-item = "submitItem"
        ></modal-form>

    </div>

</template>

<script>
import ListOfItems from './ListOfItems'
import modalForm from './ItemModalForm.vue'
import CripNotice from 'crip-vue-notice'
import Pagination from './../helper_component/Pagination.vue'
import SearchItemForm from './SearchItemForm.vue'

export default {
    props:[
        'baseUrl'
    ],
    data(){
        return{
            showModal:false,
            item:{
                category_id:'',
                code:'',
                name:''
            },
            items:{
                current_page:1,
                from:1,
                to:0,
                total:0,
                per_page:2
            },
            offset:4,
            categories:{},
            formErrors:{},
            index: -1,
            isUpdate: false,
            search:''
        }
    },
    components:{
        'modal-form':modalForm,
        'list-of-items':ListOfItems,
        'pagination': Pagination,
        'search-item-form': SearchItemForm
    },
    created(){
        // axios.get(this.baseUrl+'/item/getCategories')
        // .then(response => {
        //     // console.log(response.data)
        //     this.categories = response.data
        // });
    },
    mounted(){
        this.getItems();
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
        clearInputs(){
            this.item = {
                category_id:'',
                code:'',
                name:''
            };
            this.formErrors = {}
        },
        openModal(status){
            this.clearInputs()
            this.showModal = status
            if(status == false){
                this.index = -1
                this.isUpdate = false
            }
        },
        submitItem(item){
            
            if(this.index >= 0){
                var getItem = this.items.data[this.index];
                axios.put(this.baseUrl+'/item/'+getItem.id, item).then(response => {
                    console.log(response)
                    let args = {
                            notice: 'success',
                        title: 'Successfully Updated',
                        description: item.name+"'s Successfully Updated!"
                    };
                    this.notification(args);
                    this.getItems()
                    this.index = -1
                    this.clearInputs()
                    this.openModal(false)
                }).catch(error => {
                    console.log(error)
                    var errors = error.response.data.errors;
                    this.formErrors = errors;
                });

            }else{
                axios.post(this.baseUrl+'/item', item)
                .then(response => {
                    let args = {
                        notice: 'success',
                        title: 'Successfully Added',
                        description: item.name+"'s Successfully Added!"
                    };
                    this.notification(args);
                    this.clearInputs();
                    this.getItems();
                }).catch(error => {
                    var errors = error.response.data.errors;
                    this.formErrors = errors;
                });
            }
          


        },
        getItems(){

            if(this.search != ''){
                axios.get(this.baseUrl+'/item/searchItems?q='+this.search+'&page='+this.items.current_page)
                .then(response => {
                    // console.log(response)
                    this.items = response.data
                });
            }else{
                axios.get(this.baseUrl+'/item/getItems?page='+this.items.current_page)
                .then(response => {
                    // console.log(response)
                    this.items = response.data
                });
            }
            
        },
        searchItem(input){
           this.search = input
           this.items.current_page = 1
           this.getItems()
        },
        deleteItem(item){
            swal({
                title: "Delete "+item.name+"?",
                text:"Do you want to delete this item?",
                icon:"warning",
                buttons: true,
                dangerMode:true
            }).then( (willDelete) => {
                if(willDelete){
                    axios.delete('/item/'+item.id)
                    .then(response => {
                        
                        if(this.items.data.length === 1){
                            this.items.current_page = this.items.current_page - 1
                        }
                        this.getItems();
                        swal("Your item has been deleted!", {
                            icon: "success",
                        });
                    })
                }
            })
            
        },
        showItem(param){
            this.openModal(true)
            this.clearInputs()
            this.item.category_id = param.item.category_id
            this.item.code = param.item.code
            this.item.name = param.item.name

            this.index = param.index
            if(param.index >= 0){
                this.isUpdate = true
            }

        }
    }
}
</script>

<style>

</style>
