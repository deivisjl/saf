<template>
    <div>
        <div class="block-loading" v-if="loading"></div>
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
    </div>
</template>

<script>
import moment from 'moment'

export default {
        data(){
            return {
                loading:false,
                
                menuFechaInicio:false,
                valorFechaInicio:null,

                menuFechaFin:false,
                valorFechaFin:null,
            }
        },
        mounted() {
            
        },
        created(){
            this.setear_fechas()
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
                console.log(datos)

                this.loading = false

            },
        }
    }
</script>
