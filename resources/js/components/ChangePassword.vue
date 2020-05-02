<template>
	<div class="row contain">
		<form action="" method="post" class="form col-md-4">
        <h2 class="text-center">Thay đổi mật khẩu</h2>
        <div class="form-group">
          <label for="sel1">Mật khẩu cũ</label>
            <input v-model="curr_pass" type="password" class="form-control" required="required">
            <span style="color:red" v-if="error">
                <p v-for="curr_pass in error.curr_pass">{{ curr_pass }}</p>
            </span>
            <span style="color:red" v-if="errorPass">{{ errorPass }}</span>
        </div>
        <div class="form-group">
          <label for="sel1">Mật khẩu mới</label>
            <input v-model="new_pass" type="password" class="form-control" required="required">
            <span style="color:red" v-if="error">
                <p v-for="new_pass in error.new_pass">{{ new_pass }}</p>
            </span>
        </div>
        <div class="form-group">
          <label for="sel1">Xác nhận mật khẩu</label>
            <input v-model="conf_pass" type="password" class="form-control" required="required">
            <span style="color:red" v-if="error">
                <p v-for="conf_pass in error.conf_pass">{{ conf_pass }}</p>
            </span>
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
          curr_pass    : null,
          new_pass     : null,
          conf_pass    : null,
          message      : null,
          error        : null,
          errorPass    : null,
        }
      },
      methods : {
        onSubmit(e) {
           e.preventDefault();
            axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('token')
           axios.post('http://127.0.0.1:8000/api/changePass',{
                curr_pass    : this.curr_pass,
                new_pass     : this.new_pass,
                conf_pass    : this.conf_pass,
           },
           {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
              if(response.data.error){
                this.error = response.data.message
              }else if(response.data.errorPass){
                this.errorPass = response.data.message
              }else{
                Swal.fire({
                  position: 'top-end',
                  type: 'success',
                  title: 'Đổi mật khẩu thành công.',
                  showConfirmButton: false,
                  timer: 2000
                }),
                this.$router.push({ path: '/' })
              }
            })
            .catch(error => {})
        }
      }
    }
</script>
<style>
	.form{
		margin: 0 auto;
	}
</style>