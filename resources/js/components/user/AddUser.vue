<template>
	<div class="row contain">
		<form action="" method="post" class="form col-md-4">
        <h2 class="text-center">Thêm Thành Viên</h2>
        <div class="form-group">
            <label for="content">Tên:</label>
            <input type="text" v-model="user_name" class="form-control" placeholder="Name" required="required">
            <span style="color:red" v-if="error">
                <p v-for="name in error.name">{{ name }}</p>
            </span>
        </div>
        <div class="form-group">
            <label for="content">Email:</label>
            <input type="email" v-model="user_email" class="form-control" placeholder="Email" required="required">
            <span style="color:red" v-if="error">
                <p v-for="email in error.email">{{ email }}</p>
            </span>
        </div>
        <div class="form-group">
            <label for="content">Mật Khẩu:</label>
            <input type="password" v-model="user_password" class="form-control" placeholder="" required="required">
            <span style="color:red" v-if="error">
                <p v-for="password in error.password">{{ password }}</p>
            </span>
        </div>
        <div class="form-group">
            <label for="sel1">Rule:</label>
            <select class="form-control" id="sel1" v-model="user_rule">
                <option disabled value="">Please select one</option>
                <option value="1">Admin</option>
                <option value="2">User</option>
            </select>
            <span style="color:red" v-if="error">
                <p v-for="rule in error.rule">{{ rule }}</p>
            </span>
        </div>
        <div class="form-group">
            <input type="checkbox" v-model="status" class="">
            Hoạt Động
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
          user_name     : null,
          user_email    : null,
          user_password : null,
          user_rule     : null,
          status        : null,
          message       : null,
          error         : null,
        }
      },
      mounted () {
        
      },
      methods : {
        onSubmit(e) {
           e.preventDefault();
           axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('token')
           axios.post('http://127.0.0.1:8000/api/add-user',{
                name       : this.user_name,
                email      : this.user_email,
                password   : this.user_password,
                rule       : this.user_rule,
                status     : this.status,
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
                  title: 'Đã thêm thành viên 1!',
                  showConfirmButton: false,
                  timer: 2000
                }),
                this.$router.push({ path: 'user' })
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