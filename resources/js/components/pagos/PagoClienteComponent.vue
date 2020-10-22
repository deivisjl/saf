<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
        <div class="row">
            <div class="col-md-12">
                <table style="width: 100%">
                    <tr class="text-center">
                        <th style="width: 10px; font-size: 20px">Saldo Q. {{ modelo.saldo }}</th>
                    </tr>
                </table>
            </div>
            <div class="col-md-12">
                <form autocomplete="off" @submit.prevent="validar()">
                    <div class="form-group">
                        <label for="" class="control-label">Cantidad a cobrar</label>
                        <input type="text" class="form-control" name="abono" v-validate="'required|decimal'" v-model="modelo.monto">
                        <error-form :attribute_name="'abono'" :errors_form="errors"></error-form>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary float-left" type="submit">Guardar</button>
                        <a class="btn btn-default float-right" @click.prevent="regresar()">Regresar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default 
    {
        data(){
            return {
                loading:false,
                modelo:{
                    id:'',
                    monto:'',
                    saldo:'',
                }
            }
        },
        props:{
            factura:{}
        },
        mounted() {
            this.config_error()
        },
        created(){
            if(this.factura)
            {
                this.modelo.id = this.factura.id
                this.modelo.saldo = this.factura.saldo
            }
        },
        methods:{
            validar(){
                this.$validator.validateAll().then((result) =>{
                        if(result){
                            this.guardar();
                        }             
                  });
            },
            regresar()
            {
                window.location.href = abs_path + '/pago-clientes'
            },
            guardar(){
                let data = {
                    'id':this.modelo.id,
                    'monto':this.modelo.monto
                }
                this.loading = true
                axios.post(abs_path + '/pago-clientes', data)
                    .then(r => {
                        Toastr.success(r.data.data,'Mensaje')
                        window.location.href = abs_path + '/pago-clientes'
                    })
                    .catch(error => {
                        Toastr.error(error.response.data.error,'Mensaje')
                    })
                    .finally(()=>{
                        this.loading = false
                    })
            },
            cancelar(){
                window.history.back()
            },
            config_error(){
            let self = this
               let dict = {
                custom:{
                  abono:{
                      required:'El monto es requerido',
                  },
                }
               }

              self.$validator.localize('es',dict);
          },
        }
    }
</script>
