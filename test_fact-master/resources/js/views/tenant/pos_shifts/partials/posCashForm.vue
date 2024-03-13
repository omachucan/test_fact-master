<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" top="4vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Operacion *</label>
                            <el-switch
                                style="display: block"
                                v-model="form.type"
                                active-color="#13ce66"
                                inactive-color="#ff4949"
                                active-text="Ingresar"
                                inactive-text="Retirar" required>
                            </el-switch>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Valor *</label>
                            <el-input-number class="text-right" controls-position="right" placeholder="0.00" steep="0.01" v-model="form.value" required>
                            </el-input-number>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Cuenta *</label>
                            <el-select v-model="form.account_id" required>
                            <el-option v-for="option in accounts" :key="option.id" :value="option.id" :label="option.name"></el-option>
                        </el-select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Observaciones</label>
                            <el-input v-model="form.observations" type="textarea" autosize></el-input>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right pt-2">
                    <el-button @click.prevent="close()">Cerrar</el-button>
                    <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
                </div>
            </div>
        </form>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'accounts', 'pos_shift_id'],
        data() {
            return {
                titleDialog: 'Retirar/Ingresar Efectivo',
                resource: 'pos-shifts',
                errors: {},
                form: {},
                loading_submit: false
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    type: false,
                    value: null,
                    observations: null,
                    account_id: 1,
                    pos_shift_id: null
                }
            },
            resetForm() {
                this.initForm()
            },
            submit() {
                this.loading_submit = true

                if(this.form.value < 0){
                    this.$message.error("El valor debe ser mayor a cero")
                }
                else{
                    this.form.pos_shift_id = this.pos_shift_id
                    this.$http.post(`/${this.resource}/cash_store`, this.form)
                        .then(response => {
                            if (response.data.success) {
                                this.$message.success(response.data.message)
                                this.$eventHub.$emit('reloadData')
                                this.close()
                            } else {
                                this.$message.error(response.data.message)
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 422) {
                                this.errors = error.response.data
                            } else {
                                console.log(error)
                            }
                        })
                        .then(() => {
                            this.loading_submit = false
                        })
                }
            },
            close() {
                this.resetForm()
                this.$emit('update:showDialog', false)
            },
        }
    }
</script>
