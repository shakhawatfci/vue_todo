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

				<tr  class="tr" v-for="(value,index) in todos.data"  style="border-bottom:1px solid #ccc;">

					<td>{{ value.name }}</td>
					<td>{{ value.title }}</td>
					<td><img :src="url+'images/'+value.image" style="height:90px"></td>
					<td><a href="" class="btn btn-success" @click.prevent="editTodo(value.id)">edit</a></td>
					<td><a href="" class="btn btn-danger" @click.prevent="deleteTodo(value.id)">delete</a></td>

				</tr>

			</table>
			<button class="btn btn-success" @click="goBack">Back To previous</button>
		</div>

		     <div class="row">
            <div class="text-center col-md-12" v-if="todos.last_page > 1">
                <ul class="pagination">
                    <li :class="[ ((todos.current_page == 1) ? 'disabled' : '') ]">
                         <a :href="'?page='+todos.current_page" @click.prevent="pageClicked(todos.current_page-1)" aria-label="Previous" v-if="todos.current_page != 1">
                             <span aria-hidden="true">«</span>
                         </a>
                        <a v-else>
                            <span  aria-hidden="true">«</span>
                        </a>
                    </li>
                    <li v-for="pageNo in range(paginateLoop, numberOfPage)"
                        :class="[ ((todos.current_page == pageNo) ? 'active' : '') ]">
                        <a :href="'?page='+pageNo" @click.prevent="pageClicked(pageNo)">{{ pageNo }}</a>
                    </li>
                    <li :class="[ ((todos.current_page == todos.last_page) ? 'disabled' : '') ]" >
                        <a  :href="'?page='+todos.current_page" @click.prevent="pageClicked(todos.current_page+1)" aria-label="Next" v-if="todos.current_page != todos.last_page">
                            <span aria-hidden="true">»</span>
                        </a>
                        <a v-else>
                            <span  aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </div>
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

		getData(pageNo=1){



			axios.get(base_url+"/todoList?page="+pageNo)
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

		range(start, count) {
        return Array.apply(0, Array(count))
            .map(function (element, index){
                return index + start;
            });
   		 },

   		  pageClicked(pageNo){
                var vm = this;
                vm.getData(pageNo);
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
		
	},

	computed: {
            paginateLoop(){
                let todos = this.todos;

                if(todos.last_page > 11){
                    if((todos.last_page - 5) <= todos.current_page){
                        return todos.last_page - 10;
                    }

                    if(todos.current_page > 6){
                        return todos.current_page - 5;
                    }
                }
                return 1;
            },

            numberOfPage(){
                if(this.todos.last_page < 11){
                    return this.todos.last_page;
                }else{
                    return 11;
                }
            }
        }  
}


</script>

