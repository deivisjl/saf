<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
        <form method="post" autocomplete="off" @submit.prevent="validar">
            <div class="form-group">
                <label for="" class="control-label">Nombre del rol</label>
                <input type="text" class="form-control" name="nombre" v-validate="'required'" v-model="nombre">
                <error-form :attribute_name="'nombre'" :errors_form="errors"></error-form>
            </div>
            <div class="dropdown-divider"></div>
            <div class="form-group" v-for="item in permisos">
                    <input type="checkbox" :id="item.id" :value="item.id" v-model="opc" checked="false">
                    <label for="checkboxPrimary">
                        {{ item.name }}
                    </label>
                </input>
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
                opc:[],
                permisos:[],
            }
        },
        mounted() {
            this.config_error()
            this.obtener_permisos()
        },
        created(){
            
        },
        methods:{
            validar(){
                this.$validator.validateAll().then((result) =>{
                        if(result){
                            if(this.opc.length > 0)
                            {
                                this.guardar();
                            }
                            else
                            {
                                Toastr.warning('Debe seleccionar al menos un permiso','Mensaje')    
                            }
                            
                        }             
                  });
            },
            guardar(){
                let data = {
                    'nombre':this.nombre,
                    'permisos':this.opc
                }

                this.loading = true
                axios.post(abs_path + '/roles',data)
                    .then(r => {
                        this.loading = false
                        Toastr.success(r.data.data,'Mensaje')
                        window.location.href = abs_path + '/roles'
                    })
                    .catch(error => {
                        this.loading = false
                        Toastr.error(error.response.data.error,'Mensaje')
                    })
            },
            obtener_permisos()
            {
                this.loading = true
                axios.get(abs_path + '/permisos-obtener')
                    .then(r => {
                        this.loading = false
                        this.permisos = r.data.data
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
                      required:'El nombre del rol es requerido'
                  },
                }
               }

              self.$validator.localize('es',dict);
          },
        }
    }
</script>
