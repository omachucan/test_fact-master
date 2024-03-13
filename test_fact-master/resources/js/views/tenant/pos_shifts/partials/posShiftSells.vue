<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" top="4vh">
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Monto</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-right">Base</td>
                                    <td>{{total_base}}</td>
                                </tr>
                                <tr v-for="(row, index) in sells" :key="index">
                                    <td class="text-right"> {{row.description}} <span v-if="row.description == 'Venta'">: {{row.series}}-{{row.number}}</span> </td>
                                    <td><span v-if="row.type == 0">-</span>{{ row.total}}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">Total</td>
                                    <td>{{total}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="form-actions text-right pt-2">
            <el-button @click.prevent="close()">Cerrar</el-button>
        </div>

    </el-dialog>
</template>

<script>


    export default {
        props: ['showDialog', 'sells', 'total', 'total_base'],
        data() {
            return {
                titleDialog: 'Detalle de Operaciones',
                resource: 'pos',
            }
        },
        created() {
        },
        methods: {
            close() {
                this.$emit('update:showDialog', false)
            }
        }
    }
</script>
