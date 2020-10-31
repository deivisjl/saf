<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
        <form method="post" autocomplete="off" @submit.prevent="validar">
            <div class="form-group">
                <label for="" class="control-label">Nombre del usuario</label>
                <input type="text" class="form-control" name="nombres" v-validate="'required'" v-model="nombres">
                <error-form :attribute_name="'nombres'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Apellidos del usuario</label>
                <input type="text" class="form-control" name="apellidos" v-validate="'required'" v-model="apellidos">
                <error-form :attribute_name="'apellidos'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Correo electrónico</label>
                <input type="text" class="form-control" name="correo" v-validate="'required'" v-model="correo">
                <error-form :attribute_name="'correo'" :errors_form="errors"></error-form>
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
                id:'',
                nombres:'',
                apellidos:'',
                correo:'',
            }
        },
        props:{
            registro:{}
        },
        mounted() {
            this.config_error()
            this.id = this.registro.id,
            this.nombres = this.registro.nombres,
            this.apellidos = this.registro.apellidos,
            this.correo = this.registro.email
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
                    'nombres':this.nombres,
                    'apellidos':this.apellidos,
                    'email':this.correo,
                }

                this.loading = true
                axios.put(abs_path + '/usuarios/' + this.id, data)
                    .then(r => {
                        this.loading = false
                        Toastr.success(r.data.data,'Mensaje')
                        window.location.href = abs_path + '/usuarios'
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
                  nombres:{
                      required:'El nombre del usuario es requerido'
                  },
                  apellidos:{
                      required:'El apellido del usuario es requerido'
                  },
                  correo:{
                      required:'El correo es requerido'
                  },
                  password:{
                      requird:'La contraseña es requerida'
                  },
                  password_confirmation:{
                      required:'La confirmación de la contraseña es requerida'
                  }
                }
               }

              self.$validator.localize('es',dict);
          },
        }
    }
</script>
