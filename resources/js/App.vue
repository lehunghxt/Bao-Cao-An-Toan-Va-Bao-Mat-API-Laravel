<template>
<div id="page-content-wrapper">
	<nav class="stick navbar navbar-expand-lg navbar-light bg-light border-bottom">
		<button class="btn btn-primary" id="menu-toggle">Admin Blog</button>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ml-auto mt-2 mt-lg-0">
				<li class="nav-item active">
					<router-link v-if="authenticated" to="/" class="nav-link"></span>Trang Chủ</router-link>
				</li>
					<li class="nav-item">
					<router-link v-if="authenticated" to="/posts" class="nav-link">Tin Tức</router-link>
				</li>
				<li class="nav-item">
					<router-link v-if="authenticated && isAdmin" to="/category" class="nav-link">Loại Tin</router-link>
				</li>
				<li class="nav-item">
					<router-link v-if="authenticated && isAdmin" to="/user" class="nav-link">Thành Viên</router-link>
				</li>
				<li class="nav-item">
					<router-link v-if="!authenticated" to="/login" class="nav-link">Đăng Nhập</router-link>
				</li>
				<li class="nav-item">
					<router-link v-if="!authenticated" to="/register" class="nav-link">Đăng Ký</router-link>
				</li>
				<li class="nav-item">
					<router-link v-if="authenticated" to="/login" class="nav-link" v-on:click.native="logout()">Đăng Xuất</router-link>
				</li>
				
			</ul>
		</div>
	</nav>
	<!-- Page Content -->
	
	<div class="container-fluid">
		<router-view @isAdmin="setAdmin" @authenticated="setAuthenticated" />
	</div>
	<!-- /#page-content-wrapper -->
</div>
</template>
<script>
    export default {
        data() {
            return {
                authenticated: false,
                isAdmin 	 : false,
            }
        },
        mounted() {
         	if(localStorage.getItem('authenticated') == 'true'){
         		this.authenticated = true
         		if(JSON.parse(localStorage.getItem('user')).rule == 1){
         			this.isAdmin = true
         		}
         	}else if(localStorage.getItem('authenticated') == 'false') {
                this.$router.push({ path: '/login' })
            }
        },
        methods: {
            setAuthenticated(status) {
                this.authenticated = status;
            },
            setAdmin(status) {
                this.isAdmin = status;
            },
            logout() {
            	localStorage.setItem('authenticated',false)
            	localStorage.removeItem('token')
            	localStorage.removeItem('user')
            	this.authenticated = false
            	this.isAdmin	   = false
            	Swal.fire({
                  position: 'top-end',
                  type: 'success',
                  title: 'Đã đăng xuất khỏi thiết bị.',
                  showConfirmButton: false,
                  timer: 2000
                })
              this.$router.push({ path: '/login' })
            }
        }
    }
</script>

