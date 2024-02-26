<template>
    <div class="col-xm-12 col-sm-6 col-md-7 col-lg-8">
        <header>
            <br/>
            <h4 class="text-left">Application : {{ applicationId }}</h4>
        </header>
        <h5>files</h5>
        <select v-model="selectFile">
            <!--            <option disabled value="">Please select one</option>-->
            <option v-for="file in files" :value="file"> files ; {{ file.id }}></option>
        </select>
        <select v-model="selectFile.status" v-on:select="updateStatus(selectFile.status)">
            <option disabled value="">Please select one</option>
            <option v-for="status in ['submitted','accepted','rejected','incomplete']" :value="status"> {{ status }}
            </option>
        </select>
        <object v-if="selectFile" class='col' height="500px" type="application/pdf"
                v-bind:data="urlDoc + '/' + selectFile.id" width="100%"/>
    </div>

</template>

<script>
export default {


    props: {
        url: String,
        applicationId: Number
    },
    data() {
        return {
            success: true,
            result: '',
            currentStatus: null,
            selectFile: '',
            files: [],
            //urlDoc: "/img/getbill-7.pdf"
        }
    },
    computed: {
        urlDoc() {
            return this.url + '/' + this.applicationId + '/document';
        }
    },
    methods: {
        startingData() {
            let thiss = this;
            console.log('startingData');
            console.log(this.applicationId);
            console.log(this.urlDoc);

            axios.get(this.urlDoc
            ).then(function (responseJson) {
                let json = responseJson['data'];
                thiss.files = json;
            }).catch(function (errors) {
                console.log(errors.response.data.errors)

                /*for (let error in errors.response.data.errors) {
                    form.result = form.result + ' ' + error + ' => ' + errors.response.data.errors[error];
                    console.log(errors.response.data.errors[error])
                }*/
            });

        },
        updateStatus(file) {
            let params = new FormData();
            params.append('_method','PUT')
            // params.append(`id`, file.id);
            params.append(`status`, file.status);
            console.log(params);
            return axios.post(this.urlDoc + '/' + file.id, params
            ).then(function (responseJson) {
                let json = responseJson['data'];
               // file.success = json['success'];
               //  file.message = json['message'];
                //file.message='the file is not exist or is ureadable please upload a new one';
                return json==1;
            }).catch(function (errors) {
                file.success = false;
                console.log(errors.response.data.errors)
                file.message = "Request failed:";
                for (let error in errors.response.data.errors) {
                    console.log(errors.response.data.errors[error])
                }
                return false
            });
        }
    },
    watch: {
        applicationId(newValue) {
            this.startingData();
        },
        selectFile(newValue, oldValue) {
            if (oldValue)
                if (oldValue.status != this.currentStatus)
                    if (!this.updateStatus(oldValue))
                        oldValue.status=this.currentStatus;
            this.currentStatus = newValue.status;
        }
    },
    created() {
        this.startingData();
    }
}
</script>
