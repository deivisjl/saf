<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
        <form method="post" autocomplete="off" @submit.prevent="validar">
            <div class="form-group">
                <label for="" class="control-label">Nombre del estado</label>
                <input type="text" class="form-control" name="nombre" v-validate="'required'" v-model="modelo.nombre">
                <error-form :attribute_name="'nombre'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success float-left">Editar</button>
                <button type="button" class="btn btn-outlined-default float-right" @click.prevent="cancelar">Cancelar</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default 
    {
        data(){
            return {
                loading:false,
                modelo:{
                    nombre:'',
                    id:''
                }
            }
        },
        props:{
            forma:{}
        },
        mounted() {
            this.config_error()
        },
        created(){
            if(this.forma)
            {
                this.modelo.nombre = this.forma.nombre
                this.modelo.id = this.forma.id
            }
        },
        methods:{
            validar(){
                this.$validator.validateAll().then((result) =>{
                        if(result){
                          this.editar();
                        }             
                  });
            },
            editar(){
                let data = {
                    'nombre':this.modelo.nombre
                }
                this.loading = true
                axios.put(abs_path + '/forma-pago/' + this.modelo.id, data)
                    .then(r => {
                        this.loading = false
                        Toastr.success(r.data.data,'Mensaje')
                        window.location.href = abs_path + '/forma-pago'
                    })
                    .catch(error => {
                        this.loading = false
                        Toastr.error(error.response.data.error,'Mensaje')
                    })
            },
            cancelar(){
                window.history.back()
            },
            config_error(){
            let self = this
               let dict = {
                custom:{
                  nombre:{
                      required:'El nombre del estado es requerido'
                  },
                }
               }

              self.$validator.localize('es',dict);
          },
        }
    }
</script>
