<template>
    <div class = 'row '>
        <div class ='col-5'>
            <header>
                <br/>
                <h4 class="text-left">card edit</h4>
            </header>
            <form v-on:submit.prevent="" id="use_form">
                <button @click="addFileUpload()">Add File</button>
                <div v-for="(file, index) in files" :key="index">
                    <input type="file" @change="onFileChange($event, index)">
                    <input type="text" v-model="file.description" placeholder="Description">
                    <button @click="previewFile($event,index)">preview</button>

                </div>
                <br/>
                <label v-bind:class="getClass" > {{result}}</label>
                <br/>
                <button type="submit"  @click="submit_form" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <object class='col' v-bind:data="pdflink" type="application/pdf" width="100%" height="500px"/>

        <!--        <object class='col' data="/img/getbill-7.pdf" type="application/pdf" width="100%" height="500px"/>-->
    </div>
</template>


<script>
export default {
    props:{url:String},
    data() {
        return {
            files: [{file: null, description:'academic_card',link:''}],
            success:true,
            result:'',
            pdflink:'/img/getbill-7.pdf',
        }
    },
    computed:{
        getClass: function () {
            return {
                'text-success': this.success,
                'text-danger': !this.success
            }
        }
    },
    methods: {
        addFileUpload() {
            this.files.push({ file: null, description: '', link:'' });
        },
        onFileChange(event, index) {
            this.files[index].file = event.target.files[0];
            this.files[index].link = '';
        },
        previewFile(event,index) {
            const file = this.files[index];
            if (''!= file.link) return (this.pdflink=file.link);
            if(null == file.file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.pdflink = file.link = e.target.result;
            };
            reader.readAsDataURL(file.file);
        },
        submit_form() {
            if (null === this.files[0].file)
                return this.result= 'there isn\'t any file';
            let params = new FormData();
            params.append('_method','PUT')
            this.files.forEach((file, index) => {
                params.append(`files[${index}]`, file.file);
                params.append(`description[${index}]`, file.description);
            });
            let form = this;
            axios.post(this.url, params
            ).then(function (responseJson) {
                let json = responseJson['data'];
                form.success = json['success'];
                form.result = json['message'];
                console.log(form.result);
            }).catch(function (errors) {
                form.success = false;
                console.log(errors.response.data.errors)
                form.result = "Request failed:";
                for (let error in errors.response.data.errors) {
                    form.result = form.result + ' ' + error + ' => ' + errors.response.data.errors[error];
                    console.log(errors.response.data.errors[error])
                }
            });
        }
    }
}
</script>
