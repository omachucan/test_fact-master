<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Guias de remisión</span></li>
            </ol>
            <div v-show="hasPermissionTo('tenant.dispatches.store')" class="right-wrapper pull-right">
                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i
                        class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Número</th>
                        <th class="text-center">Fecha Envío</th>
                        <th class="text-center">Estado SUNAT</th>
                        <th class="text-center">Descargas</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody" :class="{'text-danger': (row.state_type_id === '11')}">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }} <br/>
                            <small>{{ row.customer_number }}</small>
                        </td>
                        <td>{{ row.number }}</td>
                        <td class="text-center">{{ row.date_of_shipping }}</td>
                        <td class="text-center">
                            <el-popover v-if="row.sunat_information != null && row.sunat_information.code != null"
                                placement="right"
                                width="300"
                                trigger="hover">
                                <table class="table table-sm table-striped" style="font-size: 10px">
                                    <tbody>
                                        <tr>
                                            <td>Código</td>
                                            <td>{{row.sunat_information.code}}</td>
                                        </tr>
                                        <tr>
                                            <td>Descripción</td>
                                            <td>{{row.sunat_information.description}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                               <span slot="reference" class="badge bg-secondary text-white" :class="{
                                    'bg-danger': (row.state_type_id === '11'),
                                    'bg-warning': (row.state_type_id === '13'),
                                    'bg-secondary': (row.state_type_id === '01'),
                                    'bg-info': (row.state_type_id === '03'),
                                    'bg-success': (row.state_type_id === '05'),
                                    'bg-secondary': (row.state_type_id === '07'),
                                    'bg-dark': (row.state_type_id === '09')
                                }">{{ row.state_type_description }}</span>
                            </el-popover>
                        </td>
                        <td class="text-center">
                            <button v-show="hasPermissionTo('tenant.dispatches.report')" type="button" :disabled="row.has_xml*1!==1"
                                    class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.download_external_xml)">XML
                            </button>
                            <button v-show="hasPermissionTo('tenant.dispatches.report')" type="button" :disabled="row.has_pdf*1!==1"
                                    class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.download_external_pdf)">PDF
                            </button>
                            <button v-show="hasPermissionTo('tenant.dispatches.report')" type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    v-if="row.has_cdr * 1 >= 1" :disabled="row.has_cdr*1!==1"
                                    @click.prevent="clickDownload(row.download_external_cdr)">CDR
                            </button>
                            <button v-show="hasPermissionTo('tenant.dispatches.resend')" v-else type="button" class="btn waves-effect waves-light btn-xs btn-success"
                                    @click.prevent="sendDocument(row)">Reenviar Documento
                            </button>
                            <button v-show="hasPermissionTo('tenant.dispatches.actualizar_pdf')" type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="updateFile(row)">Actualizar PDF
                            </button>
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
        data() {
            return {
                resource: 'dispatches',
                recordId: null,
            }
        },
        created() {
        },
        methods: {
            updateFile(row){
                 this.$http.get(`/update/files/dispatch/${row.id}`).then((response) => {
                 
                 this.$message.success(response.data.message)
                 
                 }).catch(error => {
                      
                    this.$message.error(error.response.data.message);
                    
                })
            },
            clickDownload(download) {
                window.open(download, '_blank');
            },

            /**
             * en caso de error reenvia el documento
             * @param id
             */
            sendDocument(row) {
                return this.$http.get(`/${this.resource}/resend/${row.id}`).then((response) => {
                    if (response.data.success) {
                        let code = response.data.code;
                        
                        console.log("code "+ code)
                        if(code == 0){
                            this.$message.success(response.data.message)
                        }
                        else if(code < 2000){
                            this.$message.warning(response.data.message)
                        }
                        else if(code < 4000){
                            this.$message.error(response.data.message)
                        }
                        else{
                            this.$message.warning(response.data.message)
                        }
                        
                        this.$eventHub.$emit('reloadData')
                    } else {
                        this.$message.error(response.data.message)
                    }
                });
            }
        }
    }
</script>
