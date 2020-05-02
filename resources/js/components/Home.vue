<template>
<div class="home">
	<div class="container bootstrap snippet">
		<div class="panel-body inf-content">
			<div class="row">
				<div class="col-md-4">
					<img alt="" style="width:600px;" title="" class="img-circle img-thumbnail isTooltip" src="https://img.icons8.com/bubbles/344/admin-settings-male.png" data-original-title="Usuario">
					<ul title="Ratings" class="list-inline ratings text-center">
						<li><a href="#"><span class="glyphicon glyphicon-star"></span></a></li>
						<li><a href="#"><span class="glyphicon glyphicon-star"></span></a></li>
						<li><a href="#"><span class="glyphicon glyphicon-star"></span></a></li>
						<li><a href="#"><span class="glyphicon glyphicon-star"></span></a></li>
						<li><a href="#"><span class="glyphicon glyphicon-star"></span></a></li>
					</ul>
				</div>
				<div class="col-md-6">
					<strong>Information</strong><br>
					<div v-if="info" class="table-responsive">
						<table class="table table-condensed table-responsive table-user-information">
							<tbody>
								<tr>
									<td>
										<strong>
										<span class="glyphicon glyphicon-asterisk text-primary"></span>
										Tên
										</strong>
									</td>
									<td class="text-info">
										{{ info.name }}
									</td>
								</tr>
								<tr>
									<td>
										<strong>
										<span class="glyphicon glyphicon-user  text-primary"></span>
										Email
										</strong>
									</td>
									<td class="text-info">
										{{ info.email }}
									</td>
								</tr>
								<tr>
									<td>
										<strong>
										<span class="glyphicon glyphicon-user  text-primary"></span>
										
										</strong>
									</td>
									<td class="text-primary">
										<router-link to="/changepass" class="nav-link btn btn-success">Đổi Mật Khẩu</router-link>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</template>
<script>
    export default{
        data () {
        return {
          info     		: null,
          message       : null,
          error         : null,
        }
      },
      mounted () {
		let user = JSON.parse(localStorage.getItem('user'))
		this.info = user
      },
      methods : {
   
      }
    }
</script>
<style>
.home{
	margin-top: 50px;
}
.inf-content{
	border:1px solid #DDDDDD;
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	border-radius:10px;
	box-shadow: 7px 7px 7px rgba(0, 0, 0, 0.3);
}
</style>