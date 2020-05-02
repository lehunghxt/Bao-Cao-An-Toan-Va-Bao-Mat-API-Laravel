<template>
	<div class="row contain">
    <div v-if="loading" class="loading spinner-border  text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
		<form v-if="!loading" action="" method="post" class="form col-md-4">
        <h2 class="text-center">Sữa Loại Tin</h2>
        <div class="form-group">
            <input type="text" v-model="cat_name" class="form-control" placeholder="Loại tin" required="required">
            <span style="color:red" v-if="error">{{ error.cat_name[0] }}</span>
        </div>
        <div class="form-group">
            <input type="checkbox" v-model="status" class="">
            Hiển thị
        </div>
        <div class="form-group">
            <button @click="onSubmit" type="submit" class="btn btn-primary btn-block">Cập Nhập</button>
        </div>
    </form>
	</div>
</template>
<script>
    export default{
        data () {
        return {
            loading   : true,
            id        : this.$route.params.id,
            info      : null,
            cat_name  : null,
            status    : null,
            error : null,
        }
      },
      async created () {
        axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('token')
        axios.get('http://127.0.0.1:8000/api/view-cateID/'+this.id)
             .then(response => {
              if(response.data.findError){
                Swal.fire({
                      position: 'top-end',
                      type: 'error',
                      title: 'Loại tin không tồn tại !',
                      showConfirmButton: false,
                      timer: 2000,
                      width : 200,
                    })
                this.$router.push({ path: '/category' })
              }else{
                this.info = response.data.data,
                this.cat_name = this.info.cat_name,
                this.status = this.info.status
              }
              this.loading = false
            })
      },
      methods : {
        onSubmit(e) {
           e.preventDefault();
           axios.post('http://127.0.0.1:8000/api/edit-cate/'+this.id,{
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
                  title: 'Đã sửa loại tin !',
                  showConfirmButton: false,
                  timer: 2000,
                  width : 200,
                }),
                this.$router.push({ path: '/category' })
              }
            })
            .catch(error => {
                this.error = error.response.data.errors
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