<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="close" top="7vh">
        <form autocomplete="off" @submit.prevent="sendForm">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"> Saldo Inicial </label>
                            <el-input-number class="text-right" controls-position="right" placeholder="0.00"
                                      :prop="'form.open_amount'"
                                      :rules="{required: true, message: 'description is required', trigger: 'blur'}"
                                      steep="0.01" v-model="form.open_amount" required>
                            </el-input-number>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"> Responsable</label>
                            <el-input class="text-right" type="text"
                                      steep="0.1" v-model="user.name" readonly>
                            </el-input>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Local</label>
                            <el-input class="text-right" type="text"
                                      steep="0.1" v-model="establishment.description" readonly>
                            </el-input>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button class="add" type="primary" native-type="submit">Aperturar</el-button>
            </div>
        </form>

    </el-dialog>
</template>

<script>


    export default {
        props: ['showDialog', 'pos_station_id'],
        data() {
            return {
                titleDialog: 'Abrir turno',
                resource: 'pos',
                user: {name: ''},
                establishment: {},
                errors: {},
                form: {},
            }
        },
        created() {
            this.$http.get(`/${this.resource}/tables`).then(response => {
                this.initForm();

                this.user = response.data.user;
                this.establishment = response.data.user.establishment;
            });
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    pos_station_id: this.pos_station_id,
                    user_id: '',
                    establishment_id: '',
                    start_date: moment().format('YYYY-MM-DD H:i'),
                    open_amount: '',
                    close_amount: 0,
                    sales_count: 0,
                    status: "open"
                }
            },
            create() {
                this.$http.get(`/${this.resource}/tables`).then(response => {
                    this.initForm();

                    this.user = response.data.user;
                    this.establishment = response.data.user.establishment;

                    this.form.user_id = response.data.user.id;
                    this.form.establishment_id = response.data.user.establishment.id;
                });
            },
            close() {
                this.$emit('update:showDialog', false)
            },
            sendForm() {
                this.$emit('OpenPos', this.form)
            }
        }
    }

</script>
