<template>
    <div>
        <div class="row">
            <div class="col-md-3 offset-md-2">
                <ul class="list-group">
                    <li class="list-group-item active text-center"><span style="font-size:18px">Reportes</span></li>
                    <template v-for="item in items">
                        <li class="list-group-item" style="cursor: pointer" @click="mostrar(item.no)"><i :class="item.icono"></i> {{ item.titulo }}</li>
                    </template>
                </ul>
            </div>
            <div class="col-md-5">
                <reporte-de-compras v-if="reporte_compras"></reporte-de-compras>
                <reporte-de-ventas v-if="reporte_ventas"></reporte-de-ventas>
                <reporte-de-inventario v-if="reporte_inventario"></reporte-de-inventario>
                <reporte-cuentas-cobrar v-if="reporte_cuentas_cobrar"></reporte-cuentas-cobrar>
                <reporte-cuentas-pagar v-if="reporte_cuentas_pagar"></reporte-cuentas-pagar>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import ReporteDeCompras from './reporte-de-compra'
import ReporteDeVentas from './reporte-de-ventas'
import ReporteDeInventario from './reporte-de-inventario'
import ReporteCuentasCobrar from './reporte-cuentas-cobrar'
import ReporteCuentasPagar from './reporte-cuentas-pagar'

export default {
        data(){
            return {
                loading:false,
                
                items: [
                    { icono: 'fas fa-download', titulo: 'Reporte de compras', no: 1},
                    { icono: 'fas fa-download', titulo: 'Reporte de ventas', no: 2},
                    { icono: 'fas fa-download', titulo: 'Reporte de inventario', no: 3 },                    
                    { icono: 'fas fa-download', titulo: 'Reporte de cuentas por cobrar', no: 4 },
                    { icono: 'fas fa-download', titulo: 'Reporte de cuentas por pagar', no: 5 },
                ],

                reporte_compras:true,
                reporte_ventas:false,
                reporte_inventario:false,
                reporte_cuentas_cobrar:false,
                reporte_cuentas_pagar:false,
            }
        },
        components:{
            ReporteDeCompras,
            ReporteDeVentas,
            ReporteDeInventario,
            ReporteCuentasCobrar,
            ReporteCuentasPagar,
        },
        mounted() {
            
        },
        created(){
            
        },
        computed:{
            
        },
        methods:{
            mostrar(no)
            {
                switch(no)
                {
                    case 1:
                        this.reporte_compras = true
                        this.reporte_ventas = false
                        this.reporte_inventario = false
                        this.reporte_cuentas_cobrar = false
                        this.reporte_cuentas_pagar = false
                    break
                    case 2:
                        this.reporte_compras = false
                        this.reporte_ventas = true
                        this.reporte_inventario = false
                        this.reporte_cuentas_cobrar = false
                        this.reporte_cuentas_pagar = false
                    break
                    case 3:
                        this.reporte_compras = false
                        this.reporte_ventas = false
                        this.reporte_inventario = true
                        this.reporte_cuentas_cobrar = false
                        this.reporte_cuentas_pagar = false
                    break
                    case 4:
                        this.reporte_compras = false
                        this.reporte_ventas = false
                        this.reporte_inventario = false
                        this.reporte_cuentas_cobrar = true
                        this.reporte_cuentas_pagar = false
                    break
                    case 5:
                        this.reporte_compras = false
                        this.reporte_ventas = false
                        this.reporte_inventario = false
                        this.reporte_cuentas_cobrar = false
                        this.reporte_cuentas_pagar = true
                    break
                }
            },
        }
    }
</script>
