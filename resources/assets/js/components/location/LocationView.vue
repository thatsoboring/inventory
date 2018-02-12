<template>
  <div class="row">
      <div class="col-md-8 col-md-offset-2">
          <div class="panel panel-default">
              <div class="panel-heading clearfix">
                  <h4 class="pull-left">List of Locations</h4>
                  <span class="pull-right" style="margin: 5px 0px 5px 0px">
                      <button class="btn btn-primary btn-sm" @click="openModal(true)">Add New Location</button>
                  </span>
              </div>
              <div class="panel-body">
                  <ul class="list-group">
                      <li class="list-group-item clearfix" v-for="(location, index) in listOfLocations.data">
                          <span>{{ location.description }}</span>
                          <div class="btn-group pull-right">
                              <button class="btn btn-info btn-xs" @click="(event) => { showLocation(location, index) }">Edit</button>
                              <button class="btn btn-danger btn-xs" @click="deleteLocation">Delete</button>
                          </div>
                      </li>
                  </ul>

                  <pagination
                  :pagination="listOfLocations"
                  :offset="4"
                  @paginate="getLocations()"
                  ></pagination>
              </div>
          </div>
      </div>

      <location-modal-form
        :showModal="showModal"
        :formErrors="formErrors"
        :location="location"
        :isUpdate="isUpdate"
        @close-modal="openModal(false)"
        @submit-form="submitForm"></location-modal-form>
      
  </div>
</template>

<script>

import cripNotice from 'crip-vue-notice'
import locationModalForm from './LocationModalForm.vue'
import Pagination from './../helper_component/Pagination.vue'

export default {
    props:['baseUrl'],
    components:{
        'location-modal-form':locationModalForm,
        'pagination': Pagination
    },
    data(){
        return{
            showModal:false,
            location: {
                description: ''
            },
            listOfLocations: {
                from:1,
                to:0,
                current_page: 1,
                total: 0,
                per_page: 2
            },
            offset:4,
            formErrors:{},
            index: -1,
            isUpdate: false
        }
    },
    created(){
        // this.getLocations();
    },
    mounted(){
        this.getLocations();
    },
    methods:{
        notification(param){
            this.$notice[param.notice]({
                title: param.title,
                description: param.description,
                onClose(){
                },
            });
        },
        clearInputs(){
            this.location = {
                description: ''
            };
            this.formErrors = {};
        },
        openModal(status){
            this.clearInputs();
            this.showModal = status;
            if(!status){
                this.index = -1;
                this.isUpdate = false;
            }
        },
        getLocations(){
            axios.get(this.baseUrl+'/location/getLocations?page='+this.listOfLocations.current_page).then(response => {
                console.log(response.data);
                this.listOfLocations = response.data;
            });
        },
        submitForm(location){

            if(this.index >= 0){
                var getLocation = this.listOfLocations.data[this.index];
                axios.put(this.baseUrl+"/location/"+getLocation.id, location).then(response => {
                    let args = {
                        notice: 'success',
                        title: 'Successfully Updated',
                        description: location.description+"'s Successfully Updated!"
                    };
                    this.notification(args);
                    this.getLocations();
                    this.index = -1;
                    this.clearInputs();
                    this.openModal(false);
                }).catch(error => {
                    var errors = error.response.data.errors;
                    this.formErrors = errors;
                });

            }else{
                axios.post(this.baseUrl+"/location", location)
                .then(response => {
                    let args = {
                        notice: 'success',
                        title: 'Successfully Added',
                        description: location.description+"'s successfully added!"
                    };
                    this.notification(args);
                    this.clearInputs();
                    this.getLocations();
                }).catch(error => {
                    var errors = error.response.data.errors
                    this.formErrors = errors;
                });
            }

            


        },
        showLocation(location, index){
            this.openModal(true);
            this.clearInputs();
            this.location.description = location.description;
            this.index = index;
            if(index >= 0){
                this.isUpdate = true;
            }
        },
        deleteLocation(location){

        }
    }
}
</script>

<style>

</style>
