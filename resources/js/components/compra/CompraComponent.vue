<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
        <form method="POST" autocomplete="false" data-vv-scope="proveedor">
            <div class="row">
                <div class="col-md-4">
                    <label for="" class="col-form-label">Proveedor</label>
                    <select name="proveedor" id="proveedor" class="custom-select" v-validate="'required'" v-model="modelo.proveedor">
                        <template v-for="item in proveedores_disponibles">
                            <option :value="item.id">{{ item.nombre }}</option>
                        </template>
                    </select>
                    <error-form :attribute_name="'proveedor.proveedor'" :errors_form="errors"></error-form>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="col-form-label">No. de factura</label>
                        <input type="text" class="form-control" name="factura" v-model="modelo.no_factura" v-validate="'required'">
                        <error-form :attribute_name="'proveedor.factura'" :errors_form="errors"></error-form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="col-form-label">Fecha de emisión</label>
                        <input type="date" class="form-control" v-model="modelo.fecha" v-validate="'required'" name="fecha" >
                        <error-form :attribute_name="'proveedor.fecha'" :errors_form="errors"></error-form>
                    </div>
                </div>
            </div>
        </form>
        <!-- segunda fila -->
        <form method="POST" autocomplete="off" data-vv-scope="productos">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="col-form-label">Producto</label>
                        <select name="producto" id="producto" class="custom-select" v-validate="'required'" v-model="modelo.producto">
                                <template v-for="item in proveedores_disponibles">
                                    <option :value="item">{{ item.nombre }}</option>
                                </template>
                            </select>
                            <error-form :attribute_name="'productos.producto'" :errors_form="errors"></error-form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="col-form-label">Precio</label>
                        <input type="text" class="form-control" name="precio" v-model="modelo.precio" v-validate="'required|decimal'">
                        <error-form :attribute_name="'productos.precio'" :errors_form="errors"></error-form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="col-form-label">Cantidad</label>
                        <input type="text" class="form-control" name="cantidad" v-model="modelo.cantidad" v-validate="'required|numeric'">
                        <error-form :attribute_name="'productos.cantidad'" :errors_form="errors"></error-form>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="control-label">&nbsp;</label>
                            <button class="btn btn-primary btn-lg btn-block" @click.prevent="agregar('productos')">Agregar</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- detalle -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th width="15%">Precio</th>
                            <th width="15%">Cantidad</th>
                            <th width="15%">Subtotal</th>
                            <th width="15%"></th>
                        </tr>
                    </thead>
                    <tbody v-if="lista.length < 1">
                        <tr class="text-center">
                            <td colspan="6">No hay productos agregados</td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr v-for="(item,index) in lista">
                            <td>{{ index+ 1 }}</td>
                            <td>{{ item.producto.nombre }}</td>
                            <td>Q. {{ item.precio }}</td>
                            <td>{{ item.cantidad }}</td>
                            <td>Q.  {{ item.subtotal }}</td>
                            <td style="text-align:center;"><button class="btn btn-danger btn-sm" @click="quitar(index)"><i class="fas fa-trash-alt"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Total -->
        <div class="row">
            <div class="col-md-12">
                <h3 class="float-right">Total Q. <span v-text="formatPrice(modelo.total)"></span></h3>
            </div>
        </div>
        <!-- Forma de pago -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group float-right">
                    <label for="" class="control-label">Forma de pago</label>
                    <select name="forma" id="forma" class="custom-select">
                        <template v-for="item in formas_pago_disponibles">
                            <option :value="item">{{ item.nombre }}</option>
                        </template>
                    </select>
                </div>
            </div>
        </div>
        <!-- Guardar -->
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary btn-block btn-lg" @click.prevent="validar('proveedor')">Guardar</button>
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
                estados_disponibles:[],
                formas_pago_disponibles:[],
                proveedores_disponibles:[],

                modelo:{
                    proveedor:'',
                    no_factura:'',
                    fecha:'',
                    producto:'',
                    precio:'',
                    cantidad:'',                    
                    total:0,
                },

                lista:[],
            }
        },
        mounted() {
            this.config_error()

            this.estados_disponibles = this.estados
            this.formas_pago_disponibles = this.formas_pago
            this.proveedores_disponibles = this.proveedores
        },
        props:{
            estados:{},
            formas_pago:{},
            proveedores:{}
        },        
        created(){
            
        },
        methods:{
            quitar(index)
            {
                this.modelo.total = parseFloat(this.modelo.total) - parseFloat(this.lista[index].subtotal )
                this.lista.splice(index,1)
            },
            validar(scope){
                this.$validator.validateAll(scope).then((result) =>{
                        if(result){
                          //this.guardar();
                        }             
                  });
            },
            agregar(scope)
            {
                this.$validator.validateAll(scope).then((result) => { 
                            if(result)
                            {
                                this.agregar_lista()
                            }
					});
            },
            agregar_lista()
            {
                var subtotal = parseFloat(this.modelo.precio * this.modelo.cantidad).toFixed(2)
                this.modelo.total = parseFloat(this.modelo.total) + parseFloat(subtotal)

                let data = {
                    'producto':this.modelo.producto,
                    'precio':parseFloat(this.modelo.precio).toFixed(2),
                    'cantidad':this.modelo.cantidad,
                    'subtotal': parseFloat(subtotal).toFixed(2)
                }

                 this.lista.push(data)
                 this.modelo.precio = '';
                 this.modelo.cantidad = '';
                 this.$validator.reset();
            },
            guardar(){
                let data = {
                    'nombre':this.nombre
                }
                this.loading = true
                axios.post(abs_path + '/categorias',data)
                    .then(r => {
                        this.loading = false
                        Toastr.success(r.data.data,'Mensaje')
                        window.location.href = abs_path + '/categorias'
                    })
                    .catch(error => {
                        this.loading = false
                        Toastr.error(error.response.data.error,'Mensaje')
                    })
            },
            
            formatPrice(value) {
                return  parseFloat(value).toFixed(2);             
            },
            config_error(){
            let self = this
               let dict = {
                custom:{
                  nombre:{
                      required:'El nombre de la categoría es requerido'
                  },
                }
               }

              self.$validator.localize('es',dict);
          },
        }
    }
</script>
