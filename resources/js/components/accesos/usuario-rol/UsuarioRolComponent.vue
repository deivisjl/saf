<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
        <form method="post" autocomplete="off" @submit.prevent="editar">
            <div class="form-group" v-for="item in roles">
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
                id:'',
                opc:[],
                roles:[],
                habilitados:[]
            }
        },
        props:{
            usuario:{}
        },
        mounted() {
            this.llenar_datos()
        },
        created(){
            if(this.usuario)
            {
                this.id = this.usuario.id
            }
        },
        methods:{
            llenar_datos(){
                this.loading = true

                Promise.all([this.obtener_roles(), this.obtener_roles_usuario()])
                        .then(()=>{
                            this.loading = false
                            this.verificar_check()
                        })
                        .catch((error)=>{
                            this.loading = false
                            Toastr.error('Ocurrió un error: ' + error,'Error')
                        });
            },
            editar(){
                let data = {
                    'id':this.id,
                    'roles':this.opc
                }

                this.loading = true
                axios.put(abs_path + '/usuarios-roles/' + this.id, data)
                    .then(r => {
                        this.loading = false
                        Toastr.success(r.data.data,'Mensaje')
                        window.location.href = abs_path + '/usuarios-roles'
                    })
                    .catch(error => {
                        this.loading = false
                        Toastr.error(error.response.data.error,'Mensaje')
                    })
            },
            obtener_roles()
            {
                return new Promise((resolve, reject)=>{
                    axios.get(abs_path + '/roles-obtener')
                    .then(r => {
                        this.roles = r.data.data
                        resolve()
                    })
                    .catch(error => {
                        reject(error)
                    })  
                });  
            },
            obtener_roles_usuario()
            {
                return new Promise((resolve,reject)=>{
                    axios.get(abs_path + '/usuario-rol/'+this.id)
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

                let items = this.roles
                let habilitado = this.habilitados

                for (var index = 0; index < items.length; index++) 
                {
                    for(var indice = 0; indice < habilitado.length; indice++)
                    {
                        if(habilitado[indice].role_id === items[index].id)
                        {
                            this.opc.push(items[index].id);
                        }
                    }
                }
            },
            cancelar(){
                window.history.back()
            },
        }
    }
</script>
