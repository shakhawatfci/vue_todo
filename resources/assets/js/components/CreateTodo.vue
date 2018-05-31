<template>
	<div class="wrap">
		        <div class="modal fade" id="create-todo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel"> Create Todo</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors">
                            <ul>
                                <li v-for="error in errors">{{ error[0] }}</li>
                            </ul>
                        </div>
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="storeTodo()">
                            <div class="form-group">
                                <label for="lable">Name<span class="requiredField">*</span></label>
                                <input type="text" class="form-control" v-model="todo.name"  placeholder="Enter Your Name">
                            </div>

                            <div class="form-group">
                                <label for="lable">Title<span class="requiredField">*</span></label>
                                <input type="text" class="form-control" v-model="todo.title" placeholder="Enter Title"/>
                            </div> 

                            <div class="form-group" v-if="todo.image">
                                                            <img :src="todo.image" class="img-responsive" height="70" width="90">
                            </div>  

                            <div class="form-group">
                                <label for="lable">Image<span class="requiredField">*</span></label>
                                <input type="file" class="form-control" v-on:change="previewImage" placeholder="Enter Title" />
                            </div>



                            <button type="submit" class="btn btn-primary btn-block"><i class="glyphicon glyphicon-ok"></i> Save</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>

import {EventBus} from '../vue-assets';

export default{

	data(){

		return{

			todo : {

				title : '',
				name  : '',
				image : ''
			},

			errors : null,
		}
	},
	methods:{

		// image fortion 

	        previewImage: function(event) {
            // Reference to the DOM input element
            var input = event.target;
            // Ensure that you have a file before attempting to read it
            if (input.files && input.files[0]) {
                // create a new FileReader to read this image and convert to base64 format
                var reader = new FileReader();
                // Define a callback function to run, when FileReader finishes its job
                reader.onload = (e) => {
                    // Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
                    // Read image as base64 and set to imageData
                    this.todo.image = e.target.result;
                }
                // Start the reader job - read file as a data url (base64 format)
                reader.readAsDataURL(input.files[0]);
            }
        },

		   storeTodo() {

			axios.post(base_url+'/todo',this.todo)
			.then(response => {
             
             $('#create-todo').modal('hide');

			  this.todo = {'title':'','name':'','image':''};
			  this.errors = null;

			  this.showMessage(response.data);

              EventBus.$emit('todo-created',response.data);

			}).catch(error => {

				if(error.response){
                   
                   this.errors = error.response.data.errors;
				}
			})
		},

		showMessage(data){
          if(data.status == 'success'){

          	toastr.success(data.message,'Success Alert',{timeOut:500});

          }else{

          	toastr.error(data.message,'Error Alert',{timeOut:500});
          }
		}
	},
    mounted(){
            var vm = this;
            $('#create-todo').on('hidden.bs.modal', function(){
                vm.errors = null;
                vm.todo = {'title':'','name':'','image':''};
            });
        }
}

	
</script>