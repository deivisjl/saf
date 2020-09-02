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
                nombre:'',
                id:'',
                opc:[],
                permisos:[],
                habilitados:[]
            }
        },
        props:{
            rol:{}
        },
        mounted() {
            this.config_error()
            this.llenar_datos()
        },
        created(){
            if(this.rol)
            {
                this.nombre = this.rol.name
                this.id = this.rol.id
            }
        },
        methods:{
            llenar_datos(){
                this.loading = true

                Promise.all([this.obtener_permisos(), this.obtener_rol_permisos()])
                        .then(()=>{
                            this.loading = false
                            this.verificar_check()
                        })
                        .catch((error)=>{
                            this.loading = false
                            Toastr.error('Ocurrió un error: ' + error,'Error')
                        });
            },
            validar(){
                this.$validator.validateAll().then((result) =>{
                        if(result){
                            if(this.opc.length > 0)
                            {
                                this.editar();
                            }
                            else
                            {
                                Toastr.warning('Debe seleccionar al menos un permiso','Mensaje')    
                            }
                            
                        }             
                  });
            },
            editar(){
                let data = {
                    'nombre':this.nombre,
                    'permisos':this.opc
                }

                this.loading = true
                axios.put(abs_path + '/roles/' + this.id, data)
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
                return new Promise((resolve, reject)=>{
                    axios.get(abs_path + '/permisos-obtener')
                    .then(r => {
                        this.permisos = r.data.data
                        resolve()
                    })
                    .catch(error => {
                        reject(error)
                    })  
                });  
            },
            obtener_rol_permisos()
            {
                return new Promise((resolve,reject)=>{
                    axios.get(abs_path + '/roles-permisos/'+this.id)
                    .then(r => {
                        this.habilitados = r.data.data
                        resolve()
                    })
                    .catch(error => {
                        reject(error)
                    })
                });
            },
            verificar_check(){

                let items = this.permisos
                let habilitado = this.habilitados

                for (var index = 0; index < items.length; index++) 
                {
                    for(var indice = 0; indice < habilitado.length; indice++)
                    {
                        if(habilitado[indice].permission_id === items[index].id)
                        {
                            this.opc.push(items[index].id);
                        }
                    }
                }
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
