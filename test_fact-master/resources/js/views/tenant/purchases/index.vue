<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Compras</span></li>
            </ol>
            <div v-show="hasPermissionTo('tenant.purchases.store')" class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Proveedor</th>
                        <th>Número</th>
                        <th>Estado</th>
                        <th class="text-center">Moneda</th>
                        <!-- <th class="text-right">T.Exportación</th> -->
                        <!-- <th class="text-right">T.Gratuita</th> -->
                        <!-- <th class="text-right">T.Inafecta</th> -->
                        <!-- <th class="text-right">T.Exonerado</th> -->
                        <th class="text-right">T.Gravado</th>
                        <th class="text-right">T.Igv</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Pagado</th>
                        <th class="text-right">Por pagar</th>
                        <th class="text-center">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.supplier_name }}<br/><small v-text="row.supplier_number"></small></td>
                        <td>{{ row.number }}<br/>
                            <small v-text="row.document_type_description"></small><br/> 
                        </td>
                        <td>{{ row.state_type_description }}</td>
                        <td class="text-center">{{ row.currency_type_id }}</td>
                        <!-- <td class="text-right">{{ row.total_exportation }}</td>
                        <td class="text-right">{{ row.total_free }}</td>
                        <td class="text-right">{{ row.total_unaffected }}</td>
                        <td class="text-right">{{ row.total_exonerated }}</td> -->
                        <td class="text-right">{{ row.total_taxed }}</td>
                        <td class="text-right">{{ row.total_igv }}</td>
                        <td class="text-right">{{ row.total }}</td>
                        <td class="text-right">{{ row.total_paid }}</td>
                        <td class="text-right">{{ row.total_to_pay }}</td>
                        <td class="text-center">
                            <el-tooltip class="item" effect="dark" content="Agregar pago" placement="top-end">
                                <button type="button" class="btn btn-xs" @click.prevent="clickPay(row.id)" v-if="row.total_to_pay > 0"><i class="fa fa-money-bill-wave i-icon text-warning"></i></button>
                                <button type="button" class="btn btn-xs" v-else="" disabled><i class="fa fa-money-bill-wave i-icon text-disabled"></i></button>
                            </el-tooltip>
                            <el-tooltip v-show="hasPermissionTo('tenant.purchases.update')" class="item" effect="dark" content="Editar" placement="top-end">
                                <a :href="`/${resource}/edit/${row.id}`" class="btn btn-xs" ><i class="fa fa-file-signature i-icon text-danger"></i></a>
                            </el-tooltip>
                        </td>
                    </tr>
                    <div class="row col-md-12 justify-content-center" slot-scope="{ totals }" slot="totals">
                        <div class="col-md-3">
                            <h5><strong>Total compras en soles ({{ totals.totalPEN.quantity}}) </strong>S/. {{ totals.totalPEN.total }}</h5>
                        </div>
                        <div class="col-md-3">
                            <h5><strong>Total compras en dólares ({{ totals.totalUSD.quantity}}) </strong>$ {{ totals.totalUSD.total }}</h5>
                        </div>
                    </div>
                </data-table>
            </div>
            <documents-pay :showDialog.sync="showDialogPay" :recordId="recordId" :resource="resource"></documents-pay>
        </div>
    </div>
</template>

<script>
    import DataTable from '../../../components/DataTable.vue'
    import DocumentsPay from '../documents/partials/pay.vue'

    export default {
        components: {DataTable, DocumentsPay},
        data() {
            return {
                showDialogVoided: false,
                showDialogPay: false,
                resource: 'purchases',
                recordId: null,
                showDialogOptions: false
            }
        },
        created() {
        },
        methods: {
            clickPay(recordId = null) {
                this.recordId = recordId
                this.showDialogPay = true
            },
            clickVoided(recordId = null) {
                this.recordId = recordId
                this.showDialogVoided = true
            }, 
            clickDownload(download) {
                window.open(download, '_blank');
            },  
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
        }
    }
</script>