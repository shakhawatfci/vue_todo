<template>
	<div class="wrap">
	  <div class="table-responsive">
	  	<table class="table table-bordered table-striped">

	  		<tr>
	  			<th>Name</th>
	  			<th>Title</th>
	  			<th>Image</th>
	  			<th>Edit</th>
	  			<th>Delete</th>
	  		</tr>

	  		<tr class="tr" v-for="(value,index) in todos" :key="index">

	  			<td>{{ value.name }}</td>
	  			<td>{{ value.title }}</td>
	  			<td><img :src="url+'/images/'+value.image" style="height:90px"></td>
	  			<td><a href="">edit</a></td>
	  			<td><a href="">delete</a></td>
	  			
	  		</tr>
	  		
	  	</table>
	  </div>
	</div>
</template>

<script>

import {EventBus} from '../vue-assets';

export default {

	data(){
      
      return{
        
        todos : [],
         url : '',

      }

	},

	created(){

        this.getData();

        this.url = base_url;
	},

	methods : {
        
        getData(){

        axios.get(base_url+"/todoList")
        .then(response => {

        	this.todos = response.data;
        }).catch(err => {

        	console.log(err);
        })

        }
		
	}  
}

	
</script>

