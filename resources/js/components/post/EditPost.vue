<template>
<div class="row contain">
    <div v-if="loading" class="loading spinner-border  text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
    <form v-if="!loading" enctype="multipart/form-data" action="" method="post" class="form col-md-4">
        <h2 class="text-center">Thêm Tin</h2>
        <div class="form-group">
            <input type="text" v-model="post_name" class="form-control" placeholder="Tiêu Đề" required="required">
            <span style="color:red" v-if="error">
                <p v-if="error.post_name">{{ error.post_name[0] }}</p>
            </span>
        </div>
        <div class="form-group">
            <label for="sel1">Chọn Loại Tin:</label>
            <select class="form-control" id="sel1" v-model="cat_id">
                <option disabled value="">Please select one</option>
                <option v-for="inf in info" :value="inf.id">{{ inf.cat_name }}</option>
            </select>
            <span style="color:red" v-if="error">
                <p v-if="error.cat_id">{{ error.cat_id[0] }}</p>
            </span>
        </div>
        <div class="form-group">
            <label for="exampleFormControlFile1">Hình Ảnh: </label>
            <input type="file" v-on:change="imageSeleted" class="form-control-file" id=""><br><br>
            <img style="width: 100px; height:auto;" :src="'/post/' + img"/>
            <span style="color:red" v-if="error">
               <p v-if="error.image">{{ error.image[0] }}</p>
            </span>
        </div>
        <div class="form-group green-border-focus">
            <label for="content">Nội dung:</label>
            <textarea class="form-control" v-model="post_content" id="content" rows="3"></textarea>
            <span style="color:red" v-if="error">
                <p v-if="error.post_content">{{ error.post_content[0] }}</p>
            </span>
        </div>
         <div class="form-group">
            <input type="checkbox" v-model="status" class="">
            Hiển thị
        </div>
        <div class="form-group">
            <button @click="onSubmit" type="submit" class="btn btn-primary btn-block">Update</button>
        </div>
    </form>
</div>
</template>
<script>
    export default{
        data () {
        return {
            loading        : true,
            id             : this.$route.params.id,
            info           : null,
            cat_id         : null,
            post_name      : null,
            img            : null,
            image          : null,
            post_content   : null,
            status         : null,
            message        : null,
            error          : null,
        }
      },
      async created () {
         axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('token')
        await axios.get('http://127.0.0.1:8000/api/view-cate')
             .then(response => (this.info = response.data.data))
        await  axios.get('http://127.0.0.1:8000/api/view-postID/'+this.id)
             .then(response => {
              if(response.data.findError){
                Swal.fire({
                      position: 'top-end',
                      type: 'error',
                      title: 'Bài viết không tồn tại !',
                      showConfirmButton: false,
                      timer: 2000,
                      width : 200,
                    })
                this.$router.push({ path: '/posts' })
              }else{
                this.cat_id       = response.data.data.cat_id,
                this.post_name    = response.data.data.post_name,
                this.img          = response.data.data.post_image,
                this.post_content = response.data.data.post_content,
                this.status       = response.data.data.status
              }
              this.loading = false
            })
      },
      methods : {
        imageSeleted(e) {
           var fileReader = new FileReader();
            fileReader.readAsDataURL(e.target.files[0])
            fileReader.onload = (e) => {
                this.image = e.target.result
            }
        },
        onSubmit(e) {
           var userId = JSON.parse(localStorage.getItem('user'))
           e.preventDefault();
           axios.post('http://127.0.0.1:8000/api/edit-post/'+this.id,{
                post_name    : this.post_name,
                post_content : this.post_content,
                cat_id       : this.cat_id,
                user_id      : userId.id,
                image        : this.image,
                img          : this.img,
                status       : this.status
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
                    this.$router.push({ path: '/posts' })
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
.loading{
  margin: 0 auto;
  margin-top : 100px;
}

</style>