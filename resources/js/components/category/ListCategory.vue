<template>
<div>
	<h1>Danh Sách Loại Tin</h1>
	<br>
	<router-link to="addCategory" class="btn btn-success btn-sm pull-left">Thêm Loại Tin</router-link>
	<br><br>
	<div v-if="loading" class="loading spinner-border  text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
	<table v-if="!loading" class="table col-md-7">
		<thead class="thead-dark">
			<tr>
				<th class="text-center" scope="col">ID</th>
				<th class="text-center" scope="col">Thể Loại Tin</th>
				<th class="text-center" scope="col">Trạng Thái</th>
				<th class="text-center" scope="col">Action</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="inf in info">
				<td class="text-center">{{ inf.id }}</td>
				<td class="text-center">{{ inf.cat_name }}</td>
				<td class="text-center">
					<a style="color:#0584ab;" href="">
						<i v-if="inf.status == 1" class="material-icons">visibility</i>
						<i v-else class="material-icons">visibility_off</i>
					</a>
				</td>
				<td class="text-center">
					<button @click="editCate(inf.id)" class="btn btn-sm btn-info"><i style="color:white;" class="material-icons">launch</i></button>
					<button @click="deleteCate(inf.id)" class="btn btn-sm btn-danger"><i style="color:white;" class="material-icons">delete</i></button>
				</td>
			</tr>
		</tbody>
	</table>
</div>
</template>

<script>
	export default{
		data () {
	    return {
	      loading : true,
	      info 	  : null,
	      message : null,
	    }
	  },
	  mounted () {
	    
	  },
	  methods : {
	  	async loadCate(){
	  	  axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('token')
		  axios.get('http://127.0.0.1:8000/api/view-cate')
	           .then(response => {
	           		this.info = response.data.data
	           		this.loading = false
	           })
	  	},
	  	editCate(id){
	  		this.$router.push({path:'/editCategory/'+id})
	  	},
	  	deleteCate(id){
	  		Swal.fire({
			  title: 'Are you sure?',
			  text: "You won't be able to revert this!",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
			  if (result.value) {
			  	axios.delete('http://127.0.0.1:8000/api/delete-cate/'+id)
		  		.then(response => {
		  			Swal.fire(
				      'Deleted!',
				      'Your file has been deleted.',
				      'success'
				    )
		  			this.loadCate();
		  		})
			  }
			})

	  	}
	  },
	  created() {
	  	this.loadCate();
	  }
	}
</script>
<style>
	.table, .loading{
		margin: 0 auto;
	}
</style>