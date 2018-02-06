<template>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Deleted Items</h4>
                </div>
                <div class="panel-body">
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
                            <tr v-for="(item, index) in trashedItems.data">
                                <td>{{ increment(index, trashedItems.from) }}</td>
                                <td>{{ item.code }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.category.name }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-info btn-xs" @click="restoreItem(item)">Restore</button>
                                        <button class="btn btn-danger btn-xs" @click="deletePermanently(item)">Delete Permanently</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="trashedItems.total === 0">
                                <td class="text-center" colspan="5"><p>No Available Deleted Items</p></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="clearfix" v-show="trashedItems.total > 0">
                        <p class="pull-left">Showing <b>{{ trashedItems.from }}</b> to <b>{{ trashedItems.to }}</b> of <b>{{ trashedItems.total }}</b> entries</p>
                    </div>

                    <pagination
                        :pagination="trashedItems"
                        @paginate="getTrashedItems()"
                        v-show="trashedItems.total > trashedItems.per_page"
                        :offset="4"
                    ></pagination>

                    <div>
                        <a v-bind:href="this.baseUrl+'/item'" class="btn btn-default"><i class="fa fa-fw fa-arrow-left"></i> Back</a>
                    </div>
                    
                </div>  
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from './../helper_component/Pagination.vue'
import swal from 'sweetalert'
export default {
    components:{
        'pagination':Pagination
    },
    props:['baseUrl'],
    data(){
        return{
            trashedItems:{
                current_page: 1,
                from: 1,
                to: 0,
                total: 0,
                per_page:2
            },
            offset:4
        }
    },
    mounted(){
        this.getTrashedItems();
    },
    methods:{
        increment(index, from){

            return index + from;
        },
        getTrashedItems(){
            axios.get(this.baseUrl+'/item/getSoftDeleteItems?page='+this.trashedItems.current_page).then(response => {
                this.trashedItems = response.data;
            });
        },
        restoreItem(item){
            swal({
                title: 'Restore '+item.name+'?',
                text: 'Do you want to restore this item?',
                icon: 'info',
                buttons: true
            }).then(willRestore => {
                if(willRestore){
                    axios.post(this.baseUrl+'/item/restore/'+item.id).then(response => {
                        this.getTrashedItems();
                        swal({
                            text: item.name+' is successfully restored!',
                            icon: 'success'
                        });
                    });
                }
            });
        },
        deletePermanently(item){

            swal({
              title: "Permanently Delete?",
              text: item.name,
              icon: "warning",
              buttons: true,
              dangerMode: true
            }).then(willDelete => {
                if(willDelete){

                    axios.delete(this.baseUrl+'/item/permanentlyDelete/'+item.id).then(response => {
                        if(response.data.status == 409){

                        }else{

                            if(this.trashedItems.data.length === 1){
                                this.trashedItems.current_page = this.trashedItems.current_page - 1;
                            }
                            this.getTrashedItems();
                            swal({
                                text: item.name+" is successfully deleted!",
                                icon: "success"
                            });
                        }
                    });
                   
                }
            });

        }
    }
}

</script>

<style>

</style>
