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
              <div class="panel-body"></div>
          </div>
      </div>

      <location-modal-form
        :showModal="showModal"
        :formErrors="formErrors"
        :location="location"
        @close-modal="openModal(false)"
        @submit-form="submitForm"></location-modal-form>
      
  </div>
</template>

<script>

import cripNotice from 'crip-vue-notice'
import locationModalForm from './LocationModalForm.vue'
export default {
    props:['baseUrl'],
    components:{
        'location-modal-form':locationModalForm
    },
    data(){
        return{
            showModal:false,
            location: {
                description: ''
            },
            formErrors:{}
        }
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
            this.showModal = status;
        },
        submitForm(location){
            axios.post(this.baseUrl+"/location", location)
            .then(response => {
                let args = {
                    notice: 'success',
                    title: 'Successfully Added',
                    description: location.description+"'s successfully added!"
                };
                this.notification(args);
                this.clearInputs();
            }).catch(error => {
                var errors = error.response.data.errors
                this.formErrors = errors;
            });
        }
    }
}
</script>

<style>

</style>
