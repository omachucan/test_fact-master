<template>
    <div>
        <header class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Anulaciones</span></li>
            </ol>
        </header>
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de anulaciones</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">F.Emisión</th>
                        <th class="text-center">F.E.Comprobante</th>
                        <th>Identificador</th>
                        <th>Ticket</th>
                        <th>Estado</th>
                        <th class="text-center">Descargas</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody" :class="{'text-danger': (row.state_type_id === '05'), 'text-warning': (row.state_type_id === '03')}">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td class="text-center">{{ row.date_of_reference }}</td>
                        <td>{{ row.identifier }}</td>
                        <td>{{ row.ticket }}</td>
                        <td>{{ row.state_type_description }}</td>
                        <td class="text-center">
                            <button v-show="hasPermissionTo('tenant.voided.report')" type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.download_xml)"
                                    v-if="row.has_xml">XML</button>
                            <button v-show="hasPermissionTo('tenant.voided.report')" type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.download_pdf)"
                                    v-if="row.has_pdf">PDF</button>
                            <button v-show="hasPermissionTo('tenant.voided.report')" type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.download_cdr)"
                                    v-if="row.has_cdr">CDR</button>
                        </td>
                        <td class="text-right">
                            <button v-show="hasPermissionTo('tenant.voided.enviar-sunat')" type="button" class="btn waves-effect waves-light btn-xs btn-warning"
                                    @click.prevent="clickTicket(row.type, row.id)"
                                    dusk="consult-voided"
                                    v-if="row.btn_ticket">Enviar a SUNAT/OSE</button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>
    </div>

</template>

<script>

    import DataTable from '../../../components/DataTable.vue'

    export default {
        components: {DataTable},
        data () {
            return {
                resource: 'voided',
                showDialog: false,
                records: [],
            }
        },
        created() {
        },
        methods: {
            clickTicket(type, id) {
                this.$http.get(`/${type}/status/${id}`)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        this.$message.error(error.response.data.message)
                    })
            },
            clickDownload(download) {
                window.open(download, '_blank');
            },
        }
    }
</script>
