<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
        <form method="post" autocomplete="off" @submit.prevent="validar">
            <div class="form-group">
                <label for="" class="control-label">Nombre del proveedor</label>
                <input type="text" class="form-control" name="nombre" v-validate="'required'" v-model="nombre">
                <error-form :attribute_name="'nombre'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Nit del proveedor</label>
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
                <button type="submit" class="btn btn-primary float-left">Guardar</button>
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
                nombre:'',
                nit:'',
                telefono:'',
                direccion:'',
            }
        },
        mounted() {
            this.config_error()
        },
        created(){
            
        },
        methods:{
            validar(){
                this.$validator.validateAll().then((result) =>{
                        if(result){
                          this.guardar();
                        }             
                  });
            },
            guardar(){
                let data = {
                    'nombre':this.nombre,
                    'nit':this.nit,
                    'telefono':this.telefono,
                    'direccion':this.direccion
                }
                this.loading = true
                axios.post(abs_path + '/proveedores',data)
                    .then(r => {
                        this.loading = false
                        Toastr.success(r.data.data,'Mensaje')
                        window.location.href = abs_path + '/proveedores'
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
                      required:'El nombre del proveedor es requerido'
                  },
                  nit:{
                      required:'El nit del proveedor es requerido'
                  },
                  telefono:{
                      required:'El teléfono del proveedor es requerido',
                      numeric:'El teléfono es un número',
                  },
                  direccion:{
                      required:'La dirección del proveedor es requerida',
                  },
                }
               }

              self.$validator.localize('es',dict);
          },
        }
    }
</script>
