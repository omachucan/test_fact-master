<template>
   <div class="row m-2">
        <div class="col-md-6">
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <span>Turnos</span>
                    <div v-if="pos_shift_id">
                        <button class="btn waves-effect waves-light btn-xs btn-danger float-right"
                        @click.prevent="posClose()">Cerrar turno</button>
                        <button class="btn waves-effect waves-light btn-xs btn-success float-right mr-2"
                        @click.prevent="createPosCash()">Gestión de dinero</button>
                    </div>
                    <button v-else class="btn waves-effect waves-light btn-xs btn-warning pull-right"
                    @click.prevent="showNewRegisterDialog = !showNewRegisterDialog">Abrir turno</button>
                </div>
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th>Saldo</th>
                        <th>Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody">
                        <td>{{ index }}</td>
                        <td>{{ row.user.name }}</td>
                        <td>{{ row.status }}</td>
                        <td>{{ row.start_date }}</td>
                        <td>{{ row.closed_date }}</td>
                        <td>S/. {{ row.balance }}</td>
                        <td><button type="button" class="btn waves-effect waves-light btn-xs btn-info"  @click.prevent="posDetails(row.id)">Resumen</button></td>
                    </tr>
                </data-table>
            </el-card>
       </div>
       <div class="col-md-6">
           <div class="card card-without-radius">
               <el-card class="box-card">
                    <div class="d-flex justify-content-center" v-if="loadingDetail">
                        <div class="spinner-border m-5" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div slot="header" class="clearfix">
                        <span>Resumen de Turno:</span> <span v-if="showPosDetails">{{pos_shift.start_date}}</span>
                    </div>
                    <div v-show="showPosDetails && !loadingDetail">
                       <div class="row border-bottom">
                            <div class="col-md-4 border-right">
                               <strong>Inicio de turno: </strong><br>
                               <span>{{pos_shift.start_date}}</span>
                           </div>
                           <div class="col-md-4 border-right">
                               <strong>Fin de turno: </strong><br>
                               <span>{{pos_shift.closed_date?pos_shift.closed_date:'- - -'}}</span>
                           </div>
                           <div class="col-md-4">
                               <strong>Base Inicial: </strong><br>
                               <span>S/. {{pos_shift.open_amount}}</span>
                           </div>
                       </div>
                       <br>
                       <div class="row">
                           <div class="col-md-12">
                            <h5>
                                <strong>Detalles</strong>
                                <!-- <a class="btn waves-effect waves-light btn-xs btn-danger" :href="`${resource}/report/pdf/${row.id}`" title="Descargar Reporte">
                                    <i class="far fa-file-pdf"></i> Reporte
                                </a> -->
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info pull-right" @click.prevent="posSells(pos_shift.id)">Detalle</button>
                            </h5>
                            <table class="table table-sm">
                                <tr>
                                    <td>Base</td>
                                    <td class="text-right">{{pos_shift.open_amount}}</td>
                                </tr>
                                <tr v-for="(row, index) in payments" :key="index">
                                    <td>{{row.payment_method_description}}</td>
                                    <td class="text-right">{{row.total}}</td>
                                </tr>
                                <tr>
                                    <td>Ingreso en Efectivo</td>
                                    <td class="text-right">{{total_cash_income}}</td>
                                </tr>
                                <tr class="text-danger">
                                    <td>Retiro en Efectivo</td>
                                    <td class="text-right">{{total_cash_withdrawal}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td class="text-right"><strong>{{total}}</strong></td>
                                </tr>
                            </table>
                           </div>
                       </div>
                    </div>
                </el-card>
           </div>
       </div>
       <new-pos-register :show-dialog.sync="showNewRegisterDialog" @OpenPos="OpenPos" :pos_station_id="pos_station_id"></new-pos-register>
       <pos-cash-form :show-dialog.sync="showPosCashDialog" :accounts="accounts" :pos_shift_id="pos_shift_id"></pos-cash-form>
       <pos-shift-details v-if="showPosDetails" :show-dialog.sync="showPosSellsDialog" :sells.sync="sells" :total.sync="total" :total_base.sync="pos_shift.open_amount"></pos-shift-details>
   </div>
</template>
<script>
    import NewPosRegister from './partials/newRegister'
    import PosShiftDetails from './partials/posShiftSells'
    import PosCashForm from './partials/posCashForm'
    import DataTable from '../../../components/DataTable.vue'

    export default {
        props: ['pos_station_id'],
        components: {
            NewPosRegister,
            PosShiftDetails,
            PosCashForm,
            DataTable
        },
        data() {
            return {
                showDialog: false,
                showNewRegisterDialog: false,
                showPosDetails: false,
                showPosSellsDialog: false,
                showPosCashDialog: false,
                loadingDetail: false,
                pos_shift_id: false,
                pos_shift: {},
                sells: {},
                total: 0,
                total_cash_income: 0,
                total_cash_withdrawal: 0,
                payments: [],
                accounts: [],
                resource: 'pos-shifts',
                posDetailId:null,
            }
        },
        created() {
            this.$eventHub.$on('reloadData', () => {

                if(this.posDetailId){
                    this.posDetails(this.posDetailId)
                }

            })
            this.initForm()
            this.init();
        },
        methods: {
            initForm(){
                this.pos_shift = {
                    start_date: moment().format('d/m/Y H:i')
                }
            },
            async init() {
                await this.$http.get(`/${this.resource}/tables`)
                    .then(response => {
                        this.pos_shift_id = response.data.pos_shift_id
                        this.accounts = response.data.accounts
                    })
            },
            OpenPos(form) {
                this.$http.post(`/${this.resource}`, form)
                    .then(response => {
                        document.location.href = `/${this.resource}`
                });
            },
            posDetails(id) {
                this.posDetailId = id
                this.loadingDetail = true
                this.$http.get(`/${this.resource}/record/${id}`)
                    .then(response => {
                    this.pos_shift = response.data.pos_shift
                    this.payments = response.data.payments
                    this.total_cash_income = (response.data.cash_income.length > 0)?response.data.cash_income[0].total:0
                    this.total_cash_withdrawal = (response.data.cash_withdrawal.length > 0)?response.data.cash_withdrawal[0].total:0
                    this.showPosDetails = true
                    this.calculateTotal()
                    this.loadingDetail = false
                })
            },
            createPosCash(id) {
                this.showPosCashDialog = true;
            },
            posSells(id) {
                this.$http.get(`/${this.resource}/sells/${id}`)
                    .then(response => {
                    this.sells = response.data.sells;
                    this.showPosSellsDialog = true;
                })
            },
            posClose() {
                this.$confirm('¿Desea Realizar el Cierre de Turno?', {
                    confirmButtonText: 'Cerrar',
                    cancelButtonText: 'Cancelar',
                    type: 'danger'
                }).then(() => {
                    this.$http.post(`/${this.resource}/close`)
                    this.$message({
                        type: 'success',
                        message: 'Se realizo el cierre correctamente'
                    });
                    document.location.href = `/${this.resource}`;

                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Cierre Cancelado'
                    });
                });
            },
            calculateTotal(){
                let total = parseFloat(this.pos_shift.open_amount) + parseFloat(this.pos_shift.close_amount)
                this.total = total.toFixed(2)
            }
        }
    }
</script>
