<template>
    <div class = 'row '>
        <div class ='col-5'>
            <header>
                <br/>
                <h4 class="text-left">card edit</h4>
            </header>
            <form v-on:submit.prevent="" id="card_application_form">
                <button @click="addFileUpload()">Add File</button>
                <div v-for="(file, index) in files" :key="index">
                    <input v-if="!file.id " type="file" accept="application/pdf" @change="onFileChange($event, index)" v-bind:disabled="!file.description">
                    <input type="text" v-model="file.description" placeholder="Description" v-bind:disabled="0 != file.id" >
                    <label v-if="file.message">{{ file.message }}</label>
                    <button @click="previewFile($event,index)">preview {{file.id}}</button>

                </div>
                <br/>
                <label v-bind:class="getClass" > {{result}}</label>
                <br/>
                <button type="submit"  @click="submit_form" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <object class='col' v-bind:data="docLink" type="application/pdf" width="100%" height="500px"/>

        <!--        <object class='col' data="/img/getbill-7.pdf" type="application/pdf" width="100%" height="500px"/>-->
    </div>
</template>


<script>
export default {
    props:{url:String,
        // urlDoc:String,
        docFiles:Array },
    data() {
        return {
            // files: [{file: null, description:'academic_card',link:'',id:0,message:null,success:null}],
            success:true,
            result:'',
            docLink:'/img/getbill-7.pdf',
            files:[],
            urlDoc:this.url+'/document'
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
        startingData(){
            this.docFiles.forEach((file, index) => {
                this.addFileUpload(null,file.id,file.description,this.urlDoc+'/'+file.id);
            });
            if (0 == this.files.length)
                this.addFileUpload();
            console.log(this.files);

        },
        addFileUpload(file= null,id=0,description= '',link='',message='',success=null) {
            this.files.push({
                file: file,id:id, description: description, link:link,message:message,success:success
                });
        },
        onFileChange(event, index) {
            let file = this.files[index];
            file.file = event.target.files[0];
            if (!file.file || !file.description)
                return file.message = 'there isn\'t any file or description';
            this.fileUpload(file);
        },
        fileUpload(file){
            let params = new FormData();
            //params.append('_method','PUT')
            params.append(`file`, file.file);
            params.append(`description`, file.description);
            if (0==file.id)
                axios.post(this.urlDoc, params
                ).then(function (responseJson) {
                    let json = responseJson['data'];
                    file.id = json['id'];
                    file.success = json['success'];
                    file.message = json['message'];
                    //file.message='the file is not exist or is ureadable please upload a new one';
                    console.log(file.message);
                }).catch(function (errors) {
                    file.success = false;
                    console.log(errors.response.data.errors)
                    file.message = "Request failed:";
                    for (let error in errors.response.data.errors) {
                        console.log(errors.response.data.errors[error])
                    }
                });
            file.link = '';
        },
        previewFile(event,index) {
            const file = this.files[index];
            if (''!= file.link) return (this.docLink=file.link);
            if(null == file.file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.docLink = file.link = e.target.result;
            };
            reader.readAsDataURL(file.file);
        },
        submit_form() {
            this.files.forEach((file, index) => {
                this.fileUpload(file);
            });
            let params = new FormData();
            params.append('_method','PUT');
            let form = this;
            axios.post(form.url, params
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
    },
    created() {
        this.startingData();
    }
}
</script>
