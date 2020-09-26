<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
        <form method="post" autocomplete="off" @submit.prevent="validar">
            <div class="form-group">
                <label for="" class="control-label">Categoría del producto</label>
                <select name="categoria" id="categoria" class="custom-select" v-model="modelo.categoria">
                    <template v-for="item in listado_categorias">
                        <option :value="item.id">{{ item.nombre }}</option>
                    </template>
                </select>
                <error-form :attribute_name="'categoria'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Nombre del producto</label>
                <input type="text" class="form-control" name="nombre" v-validate="'required'" v-model="modelo.nombre">
                <error-form :attribute_name="'nombre'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Stock minímo</label>
                <input type="text" class="form-control" name="stock_minimo" v-validate="'required|numeric'" v-model="modelo.stock_minimo">
                <error-form :attribute_name="'stock_minimo'" :errors_form="errors"></error-form>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Stock máximo</label>
                <input type="text" class="form-control" name="stock_maximo" v-validate="'required|numeric'" v-model="modelo.stock_maximo">
                <error-form :attribute_name="'stock_maximo'" :errors_form="errors"></error-form>
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
                listado_categorias:[],
                modelo:{
                    id:'',
                    categoria:'',
                    nombre:'',
                    stock_minimo:'',
                    stock_maximo:'',
                }
            }
        },
        props:{
            categorias:{},
            registro:{},
        },
        mounted() {
            this.config_error()
            this.listado_categorias = this.categorias
        },
        created(){
            if(this.registro)
            {
                this.modelo.id = this.registro.id
                this.modelo.nombre = this.registro.nombre
                this.modelo.categoria = this.registro.categoria_id
                this.modelo.stock_minimo = this.registro.stock_minimo
                this.modelo.stock_maximo = this.registro.stock_maximo
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
                    'nombre':this.modelo.nombre,
                    'categoria':this.modelo.categoria,
                    'stock_minimo':this.modelo.stock_minimo,
                    'stock_maximo':this.modelo.stock_maximo,
                }
                this.loading = true
                axios.put(abs_path + '/productos/' + this.modelo.id, data)
                    .then(r => {
                        this.loading = false
                        Toastr.success(r.data.data,'Mensaje')
                        window.location.href = abs_path + '/productos'
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
                        required:'El nombre del producto es requerido'
                    },
                    categoria:{
                        required:'La categoría del producto es requerido'
                    },
                    stock_minimo:{
                        required:'El stock mínimo es requerido',
                        numeric:'El stock mínimo es un número'
                    },
                    stock_maximo:{
                        required:'El stock máximo es requerido',
                        numeric:'El stock máximo es un número'
                    },
                }
               }

              self.$validator.localize('es',dict);
          },
        }
    }
</script>
