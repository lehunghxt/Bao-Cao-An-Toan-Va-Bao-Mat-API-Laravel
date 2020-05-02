<template>
	<div class="row contain">
		<form action="" method="post" class="form col-md-4">
        <h2 class="text-center">Thêm Loại Tin</h2>
        <div class="form-group">
            <input type="text" v-model="cat_name" class="form-control" placeholder="Loại tin" required="required">
            <span style="color:red" v-if="error">{{ error.cat_name[0] }}</span>
        </div>
        <div class="form-group">
            <input type="checkbox" v-model="status" class="">
            Hiển thị
        </div>
        <div class="form-group">
            <button @click="onSubmit" type="submit" class="btn btn-primary btn-block">Thêm</button>
        </div>
    </form>
	</div>
</template>
<script>
    export default{
        data () {
        return {
          id : this.$route.params.id,
          cat_name : null,
          status    : null,
          message : null,
          error : null,
        }
      },
      mounted () {
        
      },
      methods : {
        onSubmit(e) {
           e.preventDefault();
           axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('token')
           axios.post('http://127.0.0.1:8000/api/add-cate',{
                cat_name    : this.cat_name,
                status       : this.status,
           },
           {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
              if(response.data.error){
                this.error = response.data.message
              }else{
                 Swal.fire({
                  position: 'top-end',
                  type: 'success',
                  title: 'Your work has been saved',
                  showConfirmButton: false,
                  timer: 2000
                }),
                this.$router.push({ path: 'category' })
              }
            })
            .catch(error => {
            })
        }
      }
    }
</script>
<style>
	.form{
		margin: 0 auto;
	}
</style>