<template>
<div>
	<h1>Thành viên</h1>
	<br>
	<router-link to="addUser" class="btn btn-success btn-sm pull-left">Thêm Thành Viên</router-link>
	<br><br>
	<div v-if="loading" class="loading spinner-border  text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
	<table v-if="!loading" class="table">
		<thead class="thead-dark">
			<tr>
				<th class="text-center" scope="col">ID</th>
				<th class="text-center" scope="col">Têm</th>
				<th class="text-center" scope="col">Email</th>
				<th class="text-center" scope="col">Rule</th>
				<th class="text-center" scope="col">Trạng Thái</th>
				<th class="text-center" scope="col">Ngày Tham Gia</th>
				<th class="text-center" scope="col">Action</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="inf in info">
				<td class="text-center">{{ inf.id }}</td>
				<td class="text-center">{{ inf.name }}</td>
				<td class="text-center">{{ inf.email }}</td>
				<td class="text-center">
					<p v-if="inf.rule == 1">Admin</p>
					<p v-if="inf.rule == 2">User</p>
				</td>
				<td class="text-center">
					<a style="color:#0584ab;" href="">
						<i v-if="inf.status == 1" class="material-icons">check</i>
						<i v-else class="material-icons">close</i>
					</a>
				</td>
				<td class="text-center">{{ inf.created_at }}</td>
				<td class="text-center">
					<button @click="editUser(inf.id)" class="btn btn-sm btn-info"><i style="color:white;" class="material-icons">launch</i></button>
					<button @click="deleteUser(inf.id)" class="btn btn-sm btn-danger"><i style="color:white;" class="material-icons">delete</i></button>
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
	  	async loadUser(){
		  	// let temp =  JSON.parse(localStorage.getItem('user'))
	  		axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('token')
	    	axios.get('http://127.0.0.1:8000/api/view-user').then(response => {
	    		this.info = response.data.data
	    		this.loading = false
	    	})
	  	},
	  	editUser(id){
	  		this.$router.push({path:'/editUser/'+id});
	  	},
	  	deleteUser(id){
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
			  	axios.delete('http://127.0.0.1:8000/api/delete-user/'+id)
		  		.then(response => {
		  			Swal.fire(
				      'Deleted!',
				      'Your file has been deleted.',
				      'success'
				    )
		  			this.loadUser();
		  		})
			  }
			})

	  	}
	  },
	  created() {
	  	this.loadUser();
	  }
	}
</script>