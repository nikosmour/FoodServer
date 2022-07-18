<template>
    <div>
        <header>
            <br/>
            <h4 class="text-left">Πώληση κουπονιών</h4>
        </header>
        <form  v-on:submit.prevent="coupons">
<!--            <div class="form-group mx-auto " style="max-width:20em">-->
<!--                <div class="col-sm md-form">-->
<!--                    <label > Αριθμός κάρτας</label>-->
<!--                    <input  v-bind:class="getClass/*, {'form-control':true}*/ " type="number" v-model.number='form_data.academic_id'  placeholder="Καταχωρίστε τον αριθμό της κάρτας"  min="0" autofocus required/>&lt;!&ndash;name="academic_id"&ndash;&gt;-->
<!--                </div>-->
<!--            </div>-->
            <div class="form-group form-row mx-auto  " style="max-width:30em">
                <div class="col-sm md-form" v-for="(value, category) in form_data"  :key="'form_data_'+category">
                    <label >{{category}}</label>
                    <input  class="form-control"  v-model.trim.number='form_data[category]' type="number" min="0"/>
                </div>
            </div>
            <label v-bind:class="getClass" v-if="show"> {{result}}</label>
            <div class="mx-auto" style="max-width:5em">
                <button type="submit" class="btn btn-default" >Επόμενο</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props:{url:String},
        data:function(){
            return{
                form_data: {
                    academic_id: '',
                    BREAKFAST: 0,
                    LUNCH: 0,
                    DINNER: 0,
                },
                success:true,
                result:'',
                show:false
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
        methods:{
            hideResult(){
                setTimeout(()=>this.show = false, 20000)
            },

            coupons() {
                if (0 === this.academic_id )
                    return;

                // if (0 ==this.breakfast  && 0 == this.lunch  && 0  == this.dinner)
                //     return;

                let thiss = this;
                if (confirm("Αγορά κουπονιών απο τον χρήστη με ακαδημαϊκή ταυτότητα: " + this.form_data.academic_id +
                    " Πρωινά: " + this.form_data.BREAKFAST + " Γευμα: " + this.form_data.LUNCH + " Δείπνο: " + this.form_data.DINNER))
                    axios.post(this.url, this.form_data
                    ).then(function (responseJson) {
                        let json = responseJson['data'];
                        thiss.success = json['sold'];
                        if (json['sold']) {
                            thiss.result = "Επιτυχής πώληση";
                            thiss.$emit('newPurchase', thiss.form_data);
                        } else
                            thiss.result = json;
                    }).catch(function (errors) {
                        thiss.success = false;
                        console.log(errors)
                        thiss.result = "Request failed:";
                        for( let error in errors.response.data.errors) {
                            thiss.result = thiss.result +' '+ error + ' => ' + errors.response.data.errors[error];
                            console.log(errors.response.data.errors[error])
                        }
                    });
                this.show=true;

                this.hideResult()
            }

        }
    }
</script>
