<template>
    <div>
        <div class="block-loading-graficas" v-if="loading"></div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="control-label">Fecha desde</label>
                    <input type="date" class="form-control" v-model="valorFechaInicio">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="control-label">Fecha hasta</label>
                    <input type="date" class="form-control" v-model="valorFechaFin">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary btn-block" @click.prevent="mostrar()">Mostrar</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <apexchart type="pie" width="450px" :options="chartOptions" :series="chartSeries"></apexchart>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'

export default {
        data(){
            return {
                loading:false,
                
                valorFechaInicio:null,
                
                valorFechaFin:null,

                chartSeries:[],

                etiquetas:[],
            }
        },
        mounted() {
            
        },
        created(){
            this.setear_fechas()
            this.mostrar()
        },
        computed:{
            chartOptions(){
                let option = {}

                option = {
                    labels:this.etiquetas,
                    legend: {
                        show: false
                    },
                }

                return option
            }
        },
        methods:{
            setear_fechas()
            {
                this.valorFechaInicio = moment().subtract(6, 'months').format('YYYY-MM-DD');

                this.valorFechaFin = moment().add(1,'days').format('YYYY-MM-DD')
            },
            mostrar()
            {
                this.loading = true

                let datos = {
                    'desde': this.valorFechaInicio,
                    'hasta':this.valorFechaFin
                }
                
                axios.post('ventas-por-categoria', datos)
                    .then((r) =>{

                        this.chartSeries = r.data.data.series

                        this.etiquetas = r.data.data.etiquetas
                    })
                    .catch((error) => {
                        if(error.response && error.response.status == 423)
                        {
                            Toastr.error(error.response.data.error,'Mensaje');
                        }
                        else
                        {
                            Toastr.error(error,'Mensaje');
                        }
                    })
                    .finally(()=>{
                        this.loading = false
                    })

            },
        }
    }
</script>
