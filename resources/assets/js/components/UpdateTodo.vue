<template>
	    <div class="modal fade" id="update-todo" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel"> Update User</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors">
                        <ul>
                            <li v-for="error in errors">{{ error[0] }}</li>
                        </ul>
                    </div>
                       <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="UpdateTodo(todo.id)">
                            <div class="form-group">
                                <label for="lable">Name<span class="requiredField">*</span></label>
                                <input type="text" class="form-control" v-model="todo.name"  placeholder="Enter Your Name">
                            </div>

                            <div class="form-group">
                                <label for="lable">Title<span class="requiredField">*</span></label>
                                <input type="text" class="form-control" v-model="todo.title" placeholder="Enter Title"/>
                            </div> 

                            <div class="form-group" v-if="!changeImage">
                                                            <img :src="url+'/images/'+todo.image" class="img-responsive" height="70" width="90">
                            </div>    

                            <div class="form-group" v-else>
                                                            <img :src="todo.image" class="img-responsive" height="70" width="90">
                            </div>



                            <div class="form-group">
                                <label for="lable">Image<span class="requiredField">*</span></label>
                                <input type="file" class="form-control" v-on:change="previewImage" placeholder="Enter Title" />
                            </div>



                            <button type="submit" class="btn btn-primary btn-block"><i class="glyphicon glyphicon-ok"></i>Update</button>

                        </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
	
	import { EventBus } from '../vue-assets'

	export default{
        name: 'update-todo',
        data(){
           
           return {

           	 todo:{
              id : '', 
              name : '',
              title : '',
              image : '',
              imageStatus : 0,

           	 },
           	 errors: null,

           	 changeImage : false, 

           	 url : base_url,
           }

        },

          created(){
            let vm = this;
        	EventBus.$on('edit-buton-clicked',function(id){
              
              $('#update-todo').modal('show');
              vm.todo.id = id;
              axios.get(base_url+'/todo/edit/'+id)
              .then(res => {
              	vm.todo = {'id':res.data.id,'name':res.data.name,'title':res.data.title,'image':res.data.image}
              })

        	})

           $('#update-todo').on('hidden.bs.modal', function(){
                vm.errors = null;
                vm.todo = {'id':'','name':'','title':'','image':'','imageStatus':0};

                // EventBus.$emit('todo-created',vm.todo);
            });
            },

        methods : {


      


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
                    this.changeImage = true;
                    this.todo.image = e.target.result;
                    this.todo.imageStatus = 1;

                }
                // Start the reader job - read file as a data url (base64 format)
                reader.readAsDataURL(input.files[0]);
            }
        },

        	UpdateTodo(id){
             
               axios.put(base_url+'/todo/update/'+this.todo.id,this.todo)
               .then(res => {
                 
                 if(res.status == 'success'){
                 
                  	this.showMassage(res.data);
                    
                    $('#update-todo').modal('hide');

                    EventBus.$emit('todo-created',1);

                 }

               }).catch(error => {

				if(error.response){
                   
                   this.errors = error.response.data.errors;
				}
			})

        	},
        	   showMassage(data){
                if(data.status == 'success'){
                    toastr.success(data.message, 'Success Alert', {timeOut: 5000});
                }else{
                    toastr.error(data.message, 'Error Alert', {timeOut: 5000});
                }
            }
        },


	}


</script>