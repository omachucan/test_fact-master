<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Listado de establecimientos</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Descripción</th>
                        <th class="text-right">Código</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in records">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.description }}</td>
                        <td class="text-right">{{ row.code }}</td>
                        <td class="text-right">
                            <button v-show="hasPermissionTo('tenant.establishments.update')" type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                            <button v-show="hasPermissionTo('tenant.establishments.destroy')" type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>
                            <button v-show="hasPermissionTo('tenant.establishments.series')" type="button" class="btn waves-effect waves-light btn-xs btn-warning"
                              @click.prevent="clickSeries(row.id)">Series</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="row" v-show="hasPermissionTo('tenant.establishments.store')">
                <div class="col">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                </div>
            </div>
        </div>
        <establishments-form :showDialog.sync="showDialog"
                            :recordId="recordId"></establishments-form>
        <establishment-series :showDialog.sync="showDialogSeries"
                            :establishmentId="recordId"></establishment-series>
    </div>
</template>

<script>

    import EstablishmentsForm from './form1.vue'
    import {deletable} from '../../../mixins/deletable'
    import EstablishmentSeries from './partials/series.vue'

    export default {
        mixins: [deletable],
        components: {EstablishmentsForm,EstablishmentSeries},
        data() {
            return {
                showDialog: false,
                resource: 'establishments',
                recordId: null,
                records: [],
                showDialogSeries: false,
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
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickSeries(recordId = null) {
                this.recordId = recordId
                this.showDialogSeries = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
