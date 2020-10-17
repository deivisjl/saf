<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
        <form method="post" autocomplete="off" @submit.prevent="validar">
            <div class="form-group">
                <label for="" class="control-label">Nombres del cliente</label>
                <input type="text" class="form-control" name="nombre" v-validate="'required'" v-model="nombre">
                <error-form :attribute_name="'nombre'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Apellidos del cliente</label>
                <input type="text" class="form-control" name="apellido" v-validate="'required'" v-model="apellido">
                <error-form :attribute_name="'apellido'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Nit del cliente</label>
                <input type="text" class="form-control" name="nit" v-validate="'required'" v-model="nit">
                <error-form :attribute_name="'nit'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" v-validate="'required|numeric'" v-model="telefono">
                <error-form :attribute_name="'telefono'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" v-validate="'required'" v-model="direccion">
                <error-form :attribute_name="'direccion'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary float-right">Guardar</button>
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
                nombre:'',
                apellido:'',
                nit:'',
                telefono:'',
                direccion:'',
            }
        },
        mounted() {
            this.config_error()
            this.vaciar_modelo()
        },
        created(){
            this.vaciar_modelo()
        },
        methods:{
            vaciar_modelo()
            {
                this.nombre = ''
                this.apellido = ''
                this.nit = ''
                this.telefono = ''
                this.direccion = ''
            },
            validar(){
                this.$validator.validateAll().then((result) =>{
                        if(result){
                          this.guardar();
                        }             
                  });
            },
            guardar(){
                let data = {
                    'nombres':this.nombre,
                    'apellidos':this.apellido,
                    'nit':this.nit,
                    'telefono':this.telefono,
                    'direccion':this.direccion
                }
                this.loading = true
                axios.post(abs_path + '/clientes',data)
                    .then(r => {
                        this.loading = false
                        Toastr.success(r.data.data,'Mensaje')
                        this.vaciar_modelo()
                        events.$emit("cerrar_modal",true)
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
                      required:'El nombre del cliente es requerido'
                  },
                  apellido:{
                      required:'El apellido del cliente es requerido'
                  },
                  nit:{
                      required:'El nit del cliente es requerido'
                  },
                  telefono:{
                      required:'El teléfono del cliente es requerido',
                      numeric:'El teléfono es un número',
                  },
                  direccion:{
                      required:'La dirección del cliente es requerida',
                  },
                }
               }

              self.$validator.localize('es',dict);
          },
        }
    }
</script>
