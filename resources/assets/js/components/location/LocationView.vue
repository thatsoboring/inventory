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
        :showModal="showModal"></location-modal-form>
      
  </div>
</template>

<script>

import locationModalForm from './LocationModalForm.vue'
export default {
    components:{
        'location-modal-form':locationModalForm
    },
    data(){
        return{
            showModal:false      
        }
    },
    methods:{
        openModal(status){
            this.showModal = status;
        }
    }
}
</script>

<style>

</style>
