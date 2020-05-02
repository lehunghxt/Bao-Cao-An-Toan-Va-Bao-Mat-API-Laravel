<template>
	<div class="row contain">
		<form action="" method="post" class="form col-md-4">
            <h2 class="text-center">Đăng Ký</h2>       
            <div class="form-group">
                <label for="content">Tên:</label>
                <input v-model="name" type="text" class="form-control" placeholder="Tên" required="required">
                <span style="color:red" v-if="error">
                    <p v-for="name in error.name">{{ name }}</p>
                </span>
            </div>
            <div class="form-group">
                <label for="content">Email:</label>
                <input v-model="email" type="text" class="form-control" placeholder="Email" required="required">
                <span style="color:red" v-if="error">
                    <p v-for="email in error.email">{{ email }}</p>
                </span>
            </div>
            <div class="form-group">
                <label for="content">Mật khẩu:</label>
                <input v-model="password" type="password" class="form-control" placeholder="" required="required">
                <span style="color:red" v-if="error && error.password">
                    <!-- <p v-for="password in error.password">{{ password }}</p> -->
                    <p>{{ error.password[0] }}</p>
                </span>
            </div>
            <div class="form-group">
                <label for="content">Xác nhận mật khẩu:</label>
                <input v-model="password_confirmation" type="password" class="form-control" placeholder="" required="required">
                <span style="color:red" v-if="error && error.password">
                    <!-- <p v-for="password in error.password">{{ password }}</p> -->
                    <p>{{ error.password[0] }}</p>
                </span>
            </div>
            <div class="form-group">
                <button @click="onSubmit" type="submit" class="btn btn-primary btn-block">Đăng Ký</button>
            </div>
        </form>
	</div>
</template>
<script>
    export default{
        data () {
        return {
          name                     : null,
          email                    : null,
          password                 : null,
          password_confirmation    : null,
          message                  : null,
          error                    : null,
        }
      },
     mounted () {
        if(JSON.parse(localStorage.getItem('user'))){
          this.$router.push({ path: '/' })
        }
      },
      methods : {
        onSubmit(e) {
           e.preventDefault();
           axios.post('http://127.0.0.1:8000/api/signup',{
                name                        : this.name,
                email                       : this.email,
                password                    : this.password,
                password_confirmation       : this.password_confirmation,
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
                  title: 'Đăng ký thành công!',
                  text: 'Hãy đăng nhập!',
                  showConfirmButton: false,
                  timer: 2000
                }),
                this.$router.push({ path: 'login' })
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