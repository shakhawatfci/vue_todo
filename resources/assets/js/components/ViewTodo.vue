<template>
	<div class="wrap">
		<div class="table-responsive">
			<table class="table table-bordered table-striped" v-cloak>

				<tr>
					<th>Name</th>
					<th>Title</th>
					<th>Image</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>

				<tr class="tr" v-for="(value,index) in todos" :key="index" style="border-bottom:1px solid #ccc;">

					<td>{{ value.name }}</td>
					<td>{{ value.title }}</td>
					<td><img :src="url+'/images/'+value.image" style="height:90px"></td>
					<td><a href="" class="btn btn-success" @click.prevent="editTodo(value.id)">edit</a></td>
					<td><a href="" class="btn btn-danger" @click.prevent="deleteTodo(value.id)">delete</a></td>

				</tr>

			</table>
			<button class="btn btn-success" @click="goBack">Back To previous</button>
		</div>

		<update-todo></update-todo>
	</div>
</template>

<script>

import {EventBus} from '../vue-assets';

import UpdateTodo from './UpdateTodo.vue'

export default {

	components : {
     
     'update-todo' : UpdateTodo
     
	},

	data(){

		return{

			todos : [],
			url : '',

		}

	},

	created(){

		var _this = this;

		this.getData();

		this.url = base_url;

		EventBus.$on('todo-created', function () {
			window.history.pushState({}, null, location.pathname);
			_this.getData();
		});
	},

	methods : {

		getData(){

			axios.get(base_url+"/todoList")
			.then(response => {

				this.todos = response.data;
			}).catch(err => {

				console.log(err);
			})

		},

	    editTodo(id){
	    	
        EventBus.$emit('edit-buton-clicked', id);

	    },


        deleteTodo(id){
           
           if(confirm('Are You Sure ?')){

           axios.delete(base_url+"todo/delete/"+id)
           .then(response => {
            
            if(response.data.status == 'success'){
               
               this.showMassage(response.data);

               EventBus.$emit('todo-created',response.data);

            }
            else{
              
              this.showMassage(response.data);

            }

           }).catch(err => {

               console.log(err);
           })
           }
  

        },

		showMassage(data) {
			if (data.status == 'success') {
				toastr.success(data.message, 'Success Alert', {timeOut: 5000});
			} else {
				toastr.error(data.message, 'Error Alert', {timeOut: 5000});
			}
		},

		goBack(){

			window.history.back();

		}
		
	}  
}


</script>

