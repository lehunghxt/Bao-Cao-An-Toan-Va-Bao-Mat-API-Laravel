<template>
<div class="row contain">
    <form enctype="multipart/form-data" action="" method="post" class="form col-md-4">
        <h2 class="text-center">Thêm Tin</h2>
        <div class="form-group">
            <input type="text" v-model="post_name" class="form-control" placeholder="Tiêu Đề" required="required">
            <span style="color:red" v-if="error">
                <p v-for="post_name in error.post_name">{{ post_name }}</p>
            </span>
        </div>
        <div class="form-group">
            <label for="sel1">Chọn Loại Tin:</label>
            <select class="form-control" id="sel1" v-model="cat_id">
                <option disabled value="">Please select one</option>
                <option v-for="inf in info" :value="inf.id">{{ inf.cat_name }}</option>
            </select>
            <span style="color:red" v-if="error">
                <p v-for="cat_id in error.cat_id">{{ cat_id }}</p>
            </span>
        </div>
        <div class="form-group">
            <label for="exampleFormControlFile1">Hình Ảnh: </label>
            <input type="file" v-on:change="onFileChange" class="form-control-file" id="">
            <span style="color:red" v-if="error">
                <p v-for="image in error.image">{{ image }}</p>
            </span>
        </div>
        <div class="form-group green-border-focus">
            <label for="content">Nội dung:</label>
            <textarea class="form-control" v-model="post_content" id="content" rows="3"></textarea>
            <span style="color:red" v-if="error">
                <p v-for="post_content in error.post_content">{{ post_content }}</p>
            </span>
        </div>
         <div class="form-group">
            <input type="checkbox" v-model="status"  value="1" class="">
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
            info           : null,
            cat_id         : null,
            post_name      : null,
            image          : null,
            post_content   : null,
            status         : null,
            message        : null,
            error           : null,
        }
      },
      mounted () {
        axios.defaults.headers.common['Authorization'] = 'Bearer '+ localStorage.getItem('token')
        axios.get('http://127.0.0.1:8000/api/view-cate')
             .then(response => (this.info = response.data.data))
      },
      methods : {
        onFileChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        onSubmit(e) {
           var userId = JSON.parse(localStorage.getItem('user'))
           e.preventDefault();
           axios.post('http://127.0.0.1:8000/api/add-post',{
                
                post_name    : this.post_name,
                post_content : this.post_content,
                cat_id       : this.cat_id,
                user_id      : userId.id,
                image        : this.image,
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
                  title: 'Thêm bài đăng thành công.',
                  showConfirmButton: false,
                  timer: 2000
                }),
                this.$router.push({ path: 'posts' })
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