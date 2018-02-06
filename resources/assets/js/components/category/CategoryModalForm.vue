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
                            <h4 class="modal-title" v-show="!isUpdate">Add New Category</h4>
                            <h4 class="modal-title" v-show="isUpdate">Update Category</h4>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="addCategory"  method="POST" class="form-horizontal">
                                <div class="form-group" v-bind:class="{'has-error': formErrors.code }">
                                    <label for="category_code" class="col-md-4 control-label">Code:</label>
                                    <div class="col-md-6">
                                        <input type="text" name="category_code" id="category_code" class="form-control" v-model="category.code">
                                        <span class="help-block" v-if="formErrors.code">
                                            <strong>{{ formErrors.code[0] }}</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group" v-bind:class="{'has-error': formErrors.name }">
                                    <label for="category_name" class="col-md-4 control-label">Name:</label>
                                    <div class="col-md-6">
                                        <input type="text" name="category_name" id="category_name" class="form-control" v-model="category.name">
                                        <span class="help-block" v-if="formErrors.name">
                                            <strong>{{ formErrors.name[0] }}</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" v-show="!isUpdate">Add Category</button>
                                    <button type="submit" class="btn btn-success" v-show="isUpdate">Update Category</button>
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


<script>
    export default{
        props:['showModal', 'formErrors', 'category', 'isUpdate'],
        methods:{
            addCategory(){
                this.$emit('submit-category', this.category);
            },
            closeModal(){
                this.$emit('close-modal');
            }
        }
    }
</script>