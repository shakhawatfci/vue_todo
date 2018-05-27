<template>
	<div class="wrap">
		        <div class="modal fade" id="create-todo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel"> Create User</h4>
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

                            <div class="form-group">
                                <label for="lable">Image<span class="requiredField">*</span></label>
                                <input type="file" class="form-control" v-on:change="onFileChange" placeholder="Enter Title" />
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

		 onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.todo.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },

		   storeTodo() {

			axios.post(base_url+'/todo',this.todo)
			.then(response => {
             
             $('#create-todo').modal('hide');

			  this.todo = {'title':'','name':'','image':''};
			  this.errors = null;

			  this.showMessage(response.data);

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
}

	
</script>