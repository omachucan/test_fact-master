<template>
    <div>
        <header class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Resúmenes</span></li>
            </ol>
            <div v-show="hasPermissionTo('tenant.summaries.index')" class="right-wrapper pull-right">
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </header>
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de resúmenes</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center">Fecha Emisión</th>
                            <th class="text-center">Fecha Referencia</th>
                            <th>Identificador</th>
                            <th>Estado</th>
                            <th>Ticket</th>
                            <th class="text-center">Descargas</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(row, index) in records">
                            <td>{{ index + 1 }}</td>
                            <td class="text-center">{{ row.date_of_issue }}</td>
                            <td class="text-center">{{ row.date_of_reference }}</td>
                            <td>{{ row.identifier }}</td>
                            <td>{{ row.state_type_description }}</td>
                            <td>{{ row.ticket }}</td>
                            <td class="text-center">
                                <button v-show="hasPermissionTo('tenant.summaries.report')" type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="clickDownload(row.download_xml)"
                                        v-if="row.has_xml">XML</button>
                                <button v-show="hasPermissionTo('tenant.summaries.report')" type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="clickDownload(row.download_pdf)"
                                        v-if="row.has_pdf">PDF</button>
                                <button v-show="hasPermissionTo('tenant.summaries.report')" type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                        @click.prevent="clickDownload(row.download_cdr)"
                                        v-if="row.has_cdr">CDR</button>
                            </td>
                            <td class="text-right">
                                <button v-show="hasPermissionTo('tenant.summaries.enviar-sunat')" type="button" class="btn waves-effect waves-light btn-xs btn-warning"
                                        @click.prevent="clickTicket(row.id)"
                                        dusk="consult-ticket"
                                        v-if="row.btn_ticket">Enviar a SUNAT/OSE</button>
                                <button v-show="hasPermissionTo('tenant.summaries.destroy')" type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                        @click.prevent="clickDelete(row.id)" v-if="row.btn_delete"
                                        >Eliminar</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <summary-form :showDialog.sync="showDialog"
                        :external="false"></summary-form>
        </div>
    </div>

</template>

<script>

    import SummaryForm from './form.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [deletable],
        components: {SummaryForm},
        data () {
            return {
                resource: 'summaries',
                showDialog: false,
                records: [],
            }
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
            this.getData()
        },
        methods: {
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data
                    })
            },
            clickCreate() {
                this.showDialog = true
            },
            clickTicket(id) {
                this.$http.get(`/${this.resource}/status/${id}`)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.getData()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        this.$message.error(error.response.data.message)
                    })
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            clickDownload(download) {
                window.open(download, '_blank');
            },
        }
    }
</script>
