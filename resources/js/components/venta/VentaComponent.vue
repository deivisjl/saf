<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
        <form method="POST" autocomplete="false" data-vv-scope="cliente">
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="" class="col-form-label">Nombre del cliente</label>
                        <select name="cliente" id="cliente" class="custom-select" v-validate="'required'" v-model="modelo.cliente">
                            <template v-for="item in clientes_disponibles">
                                <option :value="item.id">{{ item.nombres }} {{ item.apellidos }}</option>
                            </template>
                        </select>
                        <error-form :attribute_name="'cliente.cliente'" :errors_form="errors"></error-form>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="col-form-label">&nbsp;</label>
                            <button class="btn btn-success btn-lg btn-block" @click.prevent="mostrar_modal()"><i class="fas fa-plus"></i> Registrar cliente</button>
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
                                <template v-for="item in productos_disponibles">
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
            <div class="col-md-4 offset-md-4">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Forma de pago</label>
                        <div class="col-sm-6">
                            <select name="forma" id="forma" class="custom-select" v-validate="'required'" v-model="modelo.forma_pago">
                                <template v-for="item in formas_pago_disponibles">
                                    <option :value="item">{{ item.nombre }}</option>
                                </template>
                            </select>
                        </div>
                </div>
            </div>
            <div class="col-md-4">
                <h3 class="float-right">Total Q. <span v-text="formatPrice(modelo.total)"></span></h3>
            </div>
        </div>
        <!-- Guardar -->
        <div class="row" v-if="lista.length > 0">
            <div class="col-md-12">
                <button class="btn btn-primary btn-block btn-lg" @click.prevent="validar('cliente')">Guardar</button>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal" tabindex="-1" data-backdrop="static" id="nuevoRegistro">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <modal-cliente-component v-if="form_modal"></modal-cliente-component>
                </div>
                </div>
            </div>
        </div>
        <!--  -->
    </div>
</template>

<script>
    export default 
    {
        data(){
            return {
                loading:false,
                
                formas_pago_disponibles:[],
                clientes_disponibles:[],
                productos_disponibles:[],

                form_modal:false,

                modelo:{
                    cliente:'',
                    forma_pago:'',
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

            this.formas_pago_disponibles = this.formas_pago
            this.clientes_disponibles = this.clientes
            this.productos_disponibles = this.productos
        },
        props:{
            formas_pago:{},
            clientes:{},
            productos:{}
        },        
        created(){
             $('#nuevoRegistro').on('hidden.bs.modal', function (e) {
                    this.form_modal = false
                })
            events.$on('cerrar_modal', this.event_cerrar_modal)
        },

        beforeDestroy()
        {
            events.$off('cerrar_modal',this.event_cerrar_modal)
        },
        methods:{
            event_cerrar_modal(data){
                this.form_modal = false
                $('#nuevoRegistro').modal('hide')
            },
            mostrar_modal()
            {
                this.form_modal = true
                $('#nuevoRegistro').modal('toggle')
            },
            quitar(index)
            {
                this.modelo.total = parseFloat(this.modelo.total) - parseFloat(this.lista[index].subtotal )
                this.lista.splice(index,1)
            },
            validar(scope){
                this.$validator.validateAll(scope).then((result) =>{
                        if(result){
                          if(this.modelo.forma_pago == '')
                          {
                              Toastr.error('Debe seleccionar una forma de pago','Mensaje');
                          }
                          else
                          {
                              this.guardar();
                          }
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
                 this.modelo.producto = '';
                 this.$validator.reset();
            },
            guardar(){
                let data = {
                    'productos':this.lista,
                    'forma_pago':this.modelo.forma_pago.id,
                    'cliente':this.modelo.cliente,
                    'total':this.modelo.total
                }
                this.loading = true
                axios.post(abs_path + '/ventas',data)
                    .then(r => {
                        this.lista = []
                        this.modelo.forma_pago = ''
                        this.modelo.cliente = ''
                        this.modelo.fecha = ''
                        this.modelo.no_factura = ''
                        this.modelo.total = 0.00
                        this.$validator.reset();
                        
                        Toastr.success(r.data.data,'Mensaje')
                    })
                    .catch(error => {
                        Toastr.error(error.response.data.error,'Mensaje')
                    })
                    .finally(()=>{
                        this.loading = false
                    })
            },
            
            formatPrice(value) {
                return  parseFloat(value).toFixed(2);             
            },
            config_error(){
            let self = this
               let dict = {
                custom:{
                  fecha:{
                      required:'La fecha es requerida',
                      date:'La fecha debe tener un formato válido'
                  },
                }
               }

              self.$validator.localize('es',dict);
          },
        }
    }
</script>
