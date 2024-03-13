<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Terminales POS</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button v-show="hasPermissionTo('tenant.pos-stations.store')" type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de Terminales POS</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Establecimiento</th>
                        <th>Almacén</th>
                        <th>Descripción</th>
                        <th class="text-center">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" slot="tbody">
                        <td>{{ index }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.establishment_description }}</td>
                        <td>{{ row.warehouse_description }}</td>
                        <td>{{ row.description }}</td>
                        <td class="text-center">
                            <button v-show="hasPermissionTo('tenant.pos.store')" type="button" class="btn btn waves-effect waves-light btn-xs btn-success" @click.prevent="clickInitPos(row.id)">POS</button>
                            <button v-show="hasPermissionTo('tenant.pos-stations.update')" type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                            <button v-show="hasPermissionTo('tenant.pos-stations.destroy')" type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                        </td>
                    </tr>
                </data-table>
            </div>
            <items-form :showDialog.sync="showDialog" :recordId="recordId"></items-form>
        </div>
    </div>
</template>
<script>

    import ItemsForm from './form.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [deletable],
        components: {ItemsForm, DataTable},
        data() {
            return {
                showDialog: false,
                resource: 'pos-stations',
                recordId: null,
                form: {},
            }
        },
        created() {
            this.form = {
                pos_station_id: null
            }
        },
        methods: {
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickInitPos(recordId = null) {
                this.form.pos_station_id = recordId
                this.$http.post(`/pos/init`, this.form)
                    .then(response => {
                        if (response) {
                            window.open('/pos', '_blank');
                        } else {
                            this.$message.error('Error al iniciar POS')
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
                    .then(() => {
                        this.loading_submit = false
                })
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
