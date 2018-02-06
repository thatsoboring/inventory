<template>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h4 class="pull-left">List of Categories</h4>
                    <span class="pull-right" style="margin: 5px 0px 5px 0px;">
                        <button class="btn btn-primary btn-sm" @click="openModal(true)">Add New Category</button>
                    </span>
                   
                </div>
                <div class="panel-body">
                    <search-category 
                        @search-categories="searchCategories"></search-category>
                    <ul class="list-group"  v-if="cats.total > 0">
                        <list-of-categories
                            :categories = "cats"
                            @show-category = "show"
                            @delete-category = "removeCategory"
                        ></list-of-categories>
                    </ul>
                    <div class="empty" v-if="cats.total === 0">No available Category.</div>
                    <div class="clearfix">
                        <p class="pull-left">Showing <b>{{ cats.from }}</b> to <b>{{ cats.to }}</b> of <b>{{ cats.total }}</b> entries</p>
                        <a v-bind:href="linkTrash" class="btn btn-default btn-sm pull-right"><i class="fa fa-fw fa-trash"></i> Trash</a>
                    </div>
                    <vue-pagination :pagination="cats" v-show="cats.total > cats.per_page"
                     @paginate="getCats()"
                     :offset="4">   
                    </vue-pagination>
                </div>
            </div>
        </div>

        <modal-form
            :isUpdate = "isUpdate"
            :category = "category"
            :formErrors = "formErrors"
            :showModal = "showModal"
            @close-modal = "openModal(false)"
            @submit-category = "submitCategory"
        ></modal-form>

    </div>
</template>

<style>
.empty{
    text-align: center;
    padding:1em;
}
</style>

<script>
    import searchCategory from './SearchCategoryForm'
    import modalForm from './CategoryModalForm.vue'
    import listOfCategories from './ListOfCategories.vue'
    import vuepagination from './VuePagination.vue'
    import swal from 'sweetalert'
    import CripNotice from 'crip-vue-notice'

    Vue.use(CripNotice)

    export default{
        props:[
                'linkTrash',
                'baseUrl'
            ],
         
        components:{
            'modal-form': modalForm,
            'list-of-categories': listOfCategories,
            'vue-pagination': vuepagination,
            'search-category': searchCategory
        },
        data() {
            return {
                search:'',
                category: {
                    code:'',
                    name:''
                },
                cats: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4,
                showModal: false,   
                formErrors:{
                },
                index: -1,
                isUpdate: false
            }
        },
        mounted() {
            this.getCats()
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
            searchCategories(search){
                this.search = search
                this.cats.current_page = 1
                this.getCats()
            },
            getCats(){
                if(this.search != ''){
                    axios.get(this.baseUrl+'/category/searchCategories?q='+this.search+'&page='+this.cats.current_page)
                    .then(response => {
                        this.cats = response.data
                    });
                }else{
                    axios.get(this.baseUrl+'/category/get_categories?page='+this.cats.current_page)
                    .then(response => {
                        console.log(response.data)
                        this.cats = response.data;  
                    }).catch(error => {
                    });
                }
            },
            clearInputs(){
                this.category = {
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
            submitCategory(category){
 
                if(this.index >= 0){
                    var cat = this.cats.data[this.index];
                    axios.put(this.baseUrl+'/category/'+cat.id, category).then(response => {
                        let args = {
                            notice: 'success',
                            title: 'Successfully Updated',
                            description: category.name+"'s Successfully Updated!"
                        };
                        this.notification(args);
                        this.getCats()
                        this.index = -1
                        this.clearInputs()
                        this.openModal(false)
                    }).catch(error => {
                        var errors = error.response.data.errors;
                        this.formErrors = errors;
                    });
                   
                }else{
                    axios.post(this.baseUrl+'/category', this.category).then(response => {
                        let args = {
                            notice: 'success',
                            title: 'Successfully Added',
                            description: category.name+"'s Successfully Added!"
                        };
                        this.notification(args);
                        this.getCats()
                        this.clearInputs()
                    }).catch(error => {
                        var errors = error.response.data.errors;
                        this.formErrors = errors;
                    });
                
                }
            },
            show(param){
                console.log(this.cats);
                this.openModal(true)
                this.clearInputs()
                this.category.name = param.category.name
                this.category.code = param.category.code

                this.index = param.index;
                if(param.index >= 0){
                    this.isUpdate = true
                }
            },
            removeCategory(category){
                swal({
                title: "Delete "+category.name+"?",
                text: "Do you want to delete this category?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        axios.delete(this.baseUrl+'/category/'+category.id).then(response => {
                            if(this.cats.data.length === 1){
                                this.cats.current_page = this.cats.current_page - 1
                            }
                            this.getCats()
                        });

                        swal("Your category has been deleted!", {
                            icon: "success",
                        });
                    } else {
                        // swal("Your imaginary file is safe!");
                    }
                });
               
            }
        }
    }
</script>