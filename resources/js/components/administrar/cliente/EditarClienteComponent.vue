<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
        <form method="post" autocomplete="off" @submit.prevent="validar">
            <div class="form-group">
                <label for="" class="control-label">Nombres del cliente</label>
                <input type="text" class="form-control" name="nombre" v-validate="'required'" v-model="modelo.nombre">
                <error-form :attribute_name="'nombre'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Apellidos del cliente</label>
                <input type="text" class="form-control" name="apellido" v-validate="'required'" v-model="modelo.apellido">
                <error-form :attribute_name="'apellido'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Nit del cliente</label>
                <input type="text" class="form-control" name="nit" v-validate="'required'" v-model="modelo.nit">
                <error-form :attribute_name="'nit'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" v-validate="'required|numeric'" v-model="modelo.telefono">
                <error-form :attribute_name="'telefono'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" v-validate="'required'" v-model="modelo.direccion">
                <error-form :attribute_name="'direccion'" :errors_form="errors"></error-form>
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
                    apellido:'',
                    nit:'',
                    telefono:'',
                    direccion:'',
                }
            }
        },
        props:{
            cliente:{}
        },
        mounted() {
            this.config_error()
        },
        created(){
            if(this.cliente)
            {
                this.modelo.nombre = this.cliente.nombres
                this.modelo.apellido = this.cliente.apellidos
                this.modelo.id = this.cliente.id
                this.modelo.nit = this.cliente.nit
                this.modelo.telefono = this.cliente.telefono
                this.modelo.direccion = this.cliente.direccion
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
                    'nombres':this.modelo.nombre,
                    'apellidos':this.modelo.apellido,
                    'nit':this.modelo.nit,
                    'telefono':this.modelo.telefono,
                    'direccion':this.modelo.direccion
                }
                this.loading = true
                axios.put(abs_path + '/clientes/' + this.modelo.id, data)
                    .then(r => {
                        this.loading = false
                        Toastr.success(r.data.data,'Mensaje')
                        window.location.href = abs_path + '/clientes'
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
