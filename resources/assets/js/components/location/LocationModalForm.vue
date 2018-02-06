<template>
    <div v-if="showModal">
        <transition name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" @click="closeModal">
                                    <span aria-hidden="true">&times;</span>
                                </button>   
                                <h4 class="modal-title" v-if="!isUpdate">Add New Location</h4>
                                <h4 class="modal-title" v-if="isUpdate">Update Location</h4>
                            </div>
                            <div class="modal-body">
                                <form method="POST" class="form-horizontal" @submit.prevent="submitItem">

                                    <div class="form-group" v-bind:class="{'has-error': formErrors.description }">
                                        <label for="description" class="col-md-4 control-label">Location:</label>
                                        <div class="col-md-6">
                                            <input type="text" name="description" id="description" class="form-control" v-model="location.description">
                                            <span class="help-block" v-if="formErrors.code">
                                                <strong>{{ formErrors.description[0] }}</strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" v-show="!isUpdate">Add Item</button>
                                        <button type="submit" class="btn btn-success" v-show="isUpdate">Update Item</button>
                                        <button type="button" class="btn btn-default" @click="closeModal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default{
        props:['showModal', 'isUpdate', 'formErrors'],
        methods:{
            closeModal(){

            }
        }
    }
</script>

<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  /* display: table-cell; */
  margin-top:120px;
  vertical-align: middle;
}
</style>
