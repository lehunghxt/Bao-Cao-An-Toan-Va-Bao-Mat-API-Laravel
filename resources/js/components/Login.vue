<template>
	<div class="row contain">
		<form action="" method="post" class="form col-md-4">
        <h2 class="text-center">Log in</h2>
        <div class="form-group">
            <input v-model="email" type="text" class="form-control" placeholder="Username" required="required">
            <span style="color:red" v-if="error">
                <p v-for="email in error.email">{{ email }}</p>
            </span>
        </div>
        <div class="form-group">
            <input v-model="password" type="password" class="form-control" placeholder="Password" required="required">
            <span style="color:red" v-if="error">
                <p v-for="password in error.password">{{ password }}</p>
            </span>
        </div>
        <div class="form-group">
            <button @click="onSubmit" type="submit" class="btn btn-primary btn-block">Log in</button>
            <span style="color:red" v-if="errorLogin">{{ errorLogin }}</span>
        </div>
    </form>
	</div>
</template>
<script>
    export default{
        data () {
        return {
          email                    : null,
          password                 : null,
          message                  : null,
          error                    : null,
          errorLogin               : null,
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
           axios.post('http://127.0.0.1:8000/api/login',{
                email    : this.email,
                password : this.password,
           },
           {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
              if(response.data.error){
                this.error = response.data.message
              }else if (response.data.failLogin){
                this.errorLogin = response.data.errLogin
              }else{
                const token = response.data.access_token
                const user = response.data.user
                localStorage.setItem('token', token)
                localStorage.setItem('user', JSON.stringify(user))
                localStorage.setItem('authenticated',true)
                axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('token')
                if(user.rule == 1){
                    this.$emit("isAdmin", true)
                }
                this.$emit("authenticated", true)
                Swal.fire({
                  position: 'top-end',
                  type: 'success',
                  title: 'Đăng nhập thành công.',
                  showConfirmButton: false,
                  timer: 1500
                })
                this.$router.push({ path: '/' })
                
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