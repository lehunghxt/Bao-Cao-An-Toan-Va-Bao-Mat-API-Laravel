<template>
<div>
	<h1>Bài viết</h1>
	<br>
	<router-link to="addPost" class="btn btn-success btn-sm pull-left">Thêm Bài Viết</router-link>
	<br><br>
	<div v-if="loading" class="loading spinner-border  text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
	<table v-if="!emptyPost || !loading" class="table">
		<thead class="thead-dark">
			<tr>
				<th class="text-center" scope="col">ID</th>
				<th class="text-center" scope="col">Tên Bài Viết</th>
				<th class="text-center" scope="col">Nội Dung</th>
				<th class="text-center" scope="col">Hình Ảnh</th>
				<th class="text-center" scope="col">Tác Giả</th>
				<th class="text-center" scope="col">Thể Loại</th>
				<th class="text-center" scope="col">Trạng Thái</th>
				<th class="text-center" scope="col">Action</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="inf in info">
				<td class="text-center">{{ inf.id }}</td>
				<td class="text-center">{{ inf.post_name }}</td>
				<td class="text-center">{{ inf.post_content }}</td>
				<td class="text-center">
					<img style="width: 50px; height:auto;" :src="'/post/' + inf.post_image"/>
				</td>
				<td class="text-center">{{ inf.name }}</td>
				<td class="text-center">{{ inf.cat_name }}</td>
				<td class="text-center">
					<a style="color:#0584ab;" href="">
						<i v-if="inf.status == 1" class="material-icons">visibility</i>
						<i v-else class="material-icons">visibility_off</i>
					</a>
				</td>
				<td class="text-center">
					<button @click="editPost(inf.id)" class="btn btn-sm btn-info"><i style="color:white;" class="material-icons">launch</i></button>
					<button @click="deletePost(inf.id)" class="btn btn-sm btn-danger"><i style="color:white;" class="material-icons">delete</i></button>
				</td>
			</tr>
		</tbody>
	</table>
	<h3 v-if="emptyPost">{{ message }}</h3>
</div>
</template>
<script>
	export default{
		data () {
	    return {
	      loading : true,
	      info: null,
	      emptyPost : false,
	      message : null,
	    }
	  },
	  mounted () {
	  	console.log(localStorage.getItem('token'))
	  },
	   methods : {
	  	async loadPost(){
	  	  axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('token')
	      axios.get('http://127.0.0.1:8000/api/view-post')
	      	   .then(response => {
		      	if(response.data.emptyPost){
		      		this.emptyPost = true
		      		this.message = response.data.message
		      	}else{
		      		this.info = response.data.data
		      	}
		      	this.loading = false
	      })
	  	},
	  	async editPost(id){
	  		this.$router.push({path:'/editPost/'+id})
	  	},
	  	deletePost(id){
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
			  	axios.delete('http://127.0.0.1:8000/api/delete-post/'+id)
		  			.then(response => {
		  			Swal.fire(
				      'Deleted!',
				      'Your file has been deleted.',
				      'success'
				    )
		  			this.loadPost();
		  		})
			  }
			})

	  	}
	  },
	  created() {
	  	this.loadPost();
	  }
	}
</script>
<style>
	.loading{
		margin: 0 auto;
	}
</style>																																						