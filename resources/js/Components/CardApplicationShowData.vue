<template>
    <div class="col-xm-12 col-sm-6 col-md-7 col-lg-8">
        <header>
            <br/>
            <h4 class="text-left">Application :  {{applicationId}}</h4>
        </header>
<!--        {{startingData()}}-->
        <p > file ; {{selectFile}}</p>
        <select v-model="selectFile">
            <option disabled value="">Please select one</option>
            <option v-for = "file in files" :value="file.id"> files ; {{file.id}}></option>
        </select>
        <object class='col' v-if="selectFile" v-bind:data="urlDoc + '/' + selectFile" type="application/pdf" width="100%" height="500px"/>
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
            selectFile: '',
            files: [],
            //urlDoc: "/img/getbill-7.pdf"
        }
    },
    computed:{
        urlDoc() {
            return this.url + '/' + this.applicationId + '/document';
        }
    },
    methods: {
        startingData() {
            let thiss = this;
            console.log('startingData');
            console.log(this.applicationId );
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
            console.log(this.files);

        },
    },
    watch:{
        applicationId(newValue){
            this.startingData();
        }
    },
    created() {
        this.startingData();
    }
}
</script>
