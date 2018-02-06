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
                                <h4 class="modal-title" v-if="!isUpdate">Add New Item</h4>
                                <h4 class="modal-title" v-if="isUpdate">Update Item</h4>
                            </div>
                            <div class="modal-body">
                                <form method="POST" class="form-horizontal" @submit.prevent="submitItem">

                                    <div class="form-group" v-bind:class="{'has-error': formErrors.category_id }">
                                        <label for="category" class="col-md-4 control-label">Category:</label>
                                        <div class="col-md-6">
                                            <select name="category" id="category" class="form-control" v-model="item.category_id">
                                                <option value="">Select Category</option>
                                                <option v-for="category in categories" v-bind:value="category.id">{{ category.name }}</option>
                                            </select>
                                            <span class="help-block" v-if="formErrors.category_id">
                                                <strong>{{ formErrors.category_id[0] }}</strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group" v-bind:class="{'has-error': formErrors.code }">
                                        <label for="code" class="col-md-4 control-label">Code:</label>
                                        <div class="col-md-6">
                                            <input type="text" name="code" id="code" class="form-control" v-model="item.code">
                                            <span class="help-block" v-if="formErrors.code">
                                                <strong>{{ formErrors.code[0] }}</strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group" v-bind:class="{'has-error': formErrors.name }">
                                        <label for="category_name" class="col-md-4 control-label">Name:</label>
                                        <div class="col-md-6">
                                            <input type="text" name="category_name" id="category_name" class="form-control" v-model="item.name">
                                            <span class="help-block" v-if="formErrors.name">
                                                <strong>{{ formErrors.name[0] }}</strong>
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
export default {
    props:['showModal', 'formErrors', 'isUpdate', 'item', 'categories'],
    methods:{
        submitItem(){
            this.$emit('submit-item', this.item);
        },
        closeModal(){
            this.$emit('close-modal');
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
