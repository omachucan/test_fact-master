<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0>">Editar Compra</h3>
        </div>
        <div class="tab-content">
            <div class="invoice">
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                       <div class="row">
                         <div class="col-lg-3">
                            <div class="form-group" :class="{'has-danger': errors.document_type_id}">
                                <label class="control-label">Tipo de comprobante</label>
                                <el-select v-model="form.document_type_id" @change="changeDocumentType">
                                    <el-option v-for="option in document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.document_type_id" v-text="errors.document_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.series}">
                                <label class="control-label">Serie <span class="text-danger">*</span></label>
                                <el-input v-model="form.series" :maxlength="4"></el-input>

                                <small class="form-control-feedback" v-if="errors.series" v-text="errors.series[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.number}">
                                <label class="control-label">Número <span class="text-danger">*</span></label>
                                <el-input v-model="form.number"></el-input>
                                <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                                <label class="control-label">Moneda</label>
                                <el-select v-model="form.currency_type_id" @change="changeCurrencyType">
                                    <el-option v-for="option in currency_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                <label class="control-label">Fecha de emisión</label>
                                <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group" :class="{'has-danger': errors.supplier_id}">
                                <label class="control-label">
                                    Proveedor
                                    <a href="#" @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                                </label>
                                <el-select v-model="form.supplier_id" filterable>
                                    <el-option v-for="option in suppliers" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.supplier_id" v-text="errors.supplier_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.exchange_rate_sale}">
                                <label class="control-label">Tipo de cambio</label>
                                <el-input v-model="form.exchange_rate_sale"></el-input>
                                <small class="form-control-feedback" v-if="errors.exchange_rate_sale" v-text="errors.exchange_rate_sale[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.establishment_id}">
                                <label class="control-label">Establecimiento</label>
                                <el-select v-model="form.establishment_id">
                                    <el-option v-for="option in establishments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.establishment_id" v-text="errors.establishment_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 d-flex align-items-end pt-2">
                            <div class="form-group">
                                <button type="button" class="btn waves-effect waves-light btn-primary" @click.prevent="showDialogAddItem = true">+ Agregar Producto</button>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="form.items.length > 0">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Descripción</th>
                                            <th class="text-center">Unidad</th>
                                            <th class="text-right">Cantidad</th>
                                            <th class="text-right">Precio Unitario</th>
                                            <th class="text-right">Descuento</th>
                                            <th class="text-right">Cargo</th>
                                            <th class="text-right">Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in form.items">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ row.item.description }}</td>
                                            <td class="text-center">{{ row.item.unit_type_id }}</td>
                                            <td class="text-right">{{ row.quantity }}</td>
                                            <td class="text-right">{{ currency_type.symbol }} {{ row.unit_price }}</td>
                                            <td class="text-right">{{ currency_type.symbol }} {{ row.total_discount }}</td>
                                            <td class="text-right">{{ currency_type.symbol }} {{ row.total_charge }}</td>
                                            <td class="text-right">{{ currency_type.symbol }} {{ row.total }}</td>
                                            <td class="text-right">
                                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickRemoveItem(index)">x</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-right" v-if="form.total_exportation > 0">OP.EXPORTACIÓN: {{ currency_type.symbol }} {{ form.total_exportation }}</p>
                            <p class="text-right" v-if="form.total_free > 0">OP.GRATUITAS: {{ currency_type.symbol }} {{ form.total_free }}</p>
                            <p class="text-right" v-if="form.total_unaffected > 0">OP.INAFECTAS: {{ currency_type.symbol }} {{ form.total_unaffected }}</p>
                            <p class="text-right" v-if="form.total_exonerated > 0">OP.EXONERADAS: {{ currency_type.symbol }} {{ form.total_exonerated }}</p>
                            <p class="text-right" v-if="form.total_taxed > 0">OP.GRAVADA: {{ currency_type.symbol }} {{ form.total_taxed }}</p>
                            <p class="text-right" v-if="form.total_igv > 0">IGV: {{ currency_type.symbol }} {{ form.total_igv }}</p>
                            <h3 class="text-right" v-if="form.total > 0"><b>TOTAL A PAGAR: </b>{{ currency_type.symbol }} {{ form.total }}</h3>
                        </div>
                    </div>
                    </div>
                    <div class="form-actions text-right mt-4">
                        <el-button @click.prevent="close()">Cancelar</el-button>
                        <el-button class="submit" type="primary" native-type="submit" :loading="loading_submit" v-if="form.items.length > 0">Generar</el-button>
                    </div>
                </form>
            </div>
        </div>

        <purchase-form-item :showDialog.sync="showDialogAddItem"
                           :currency-type-id-active="form.currency_type_id"
                           :exchange-rate-sale="form.exchange_rate_sale"
                           @add="addRow"></purchase-form-item>

        <person-form :showDialog.sync="showDialogNewPerson"
                       type="suppliers"
                       :external="true"></person-form>

        <purchase-options :showDialog.sync="showDialogOptions"
                          :recordId="purchaseNewId"
                          :showClose="false"></purchase-options>
    </div>
</template>

<script>
    import PurchaseFormItem from './partials/item.vue'
    import PersonForm from '../persons/form.vue'
    import PurchaseOptions from './partials/options.vue'
    import {functions, exchangeRate} from '../../../mixins/functions'
    import {calculateRowItem, formaterNumber} from '../../../helpers/functions'

    export default {
        props: ['purchase_id'],
        components: {PurchaseFormItem, PersonForm, PurchaseOptions},
        mixins: [functions, exchangeRate],
        data() {
            return {
                resource: 'purchases',
                showDialogAddItem: false,
                showDialogNewPerson: false,
                showDialogOptions: false,
                loading_submit: false,
                loading_form: false,
                errors: {},
                form: {},
                aux_supplier_id:null,
                document_types: [],
                currency_types: [],
                discount_types: [],
                charges_types: [],
                all_suppliers: [],
                suppliers: [],
                company: null,
                operation_types: [],
                establishments: [],
                establishment: null,
                all_series: [],
                series: [],
                currency_type: {},
                purchaseNewId: null
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables2/${this.purchase_id}`)
                .then(response => {
                    this.document_types = response.data.document_types_invoice
                    this.currency_types = response.data.currency_types
                    this.establishments = response.data.establishments
                    this.all_suppliers = response.data.suppliers
                    this.discount_types = response.data.discount_types
                    this.charges_types = response.data.charges_types

                    this.form.supplier_id = response.data.purchase.supplier_id
                    this.form.series = response.data.purchase.series
                    this.form.number = response.data.purchase.number
                    this.form.currency_type_id = response.data.purchase.currency_type_id
                    this.form.date_of_issue = response.data.purchase.date_of_issue
                    this.form.exchange_rate_sale = response.data.purchase.exchange_rate_sale
                    this.form.establishment_id = response.data.purchase.establishment_id
                    this.form.document_type_id = response.data.purchase.document_type_id

                    this.changeDocumentType()
                })

            await this.$http.get(`/${this.resource}/item/tables2/${this.purchase_id}`)
            .then(response => {
                this.form.items = response.data.items
                this.form.total_exportation = response.data.purchase.total_exportation
                this.all_affectation_igv_types = response.data.affectation_igv_types
                this.system_isc_types = response.data.system_isc_types
                this.discount_types = response.data.discount_types
                this.charge_types = response.data.charge_types
                this.attribute_types = response.data.attribute_types
            })
            
            this.loading_form = true
            this.form.purchase_id = this.purchase_id
            this.calculateTotal()
        },
        methods: {
            filterSuppliers() {
                this.suppliers = this.all_suppliers 
            },
            initForm() {
                this.errors = {}
                this.form = {
                    establishment_id: null,
                    document_type_id: null,
                    series: null,
                    number: null,
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    supplier_id: null,
                    currency_type_id: null,
                    purchase_order: null,
                    exchange_rate_sale: 0,
                    total_prepayment: 0,
                    total_charge: 0,
                    total_discount: 0,
                    total_exportation: 0,
                    total_free: 0,
                    total_taxed: 0,
                    total_unaffected: 0,
                    total_exonerated: 0,
                    total_igv: 0,
                    total_base_isc: 0,
                    total_isc: 0,
                    total_base_other_taxes: 0,
                    total_other_taxes: 0,
                    total_taxes: 0,
                    total_value: 0,
                    total: 0,
                    operation_type_id: 1,
                    date_of_due: moment().format('YYYY-MM-DD'),
                    items: [],
                    charges: [],
                    discounts: [],
                    attributes: [],
                    guides: [],
                }
            },
            resetForm() {
                this.initForm()
                this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null
                this.form.establishment_id = this.establishment.id
                //this.form.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null

                this.changeDateOfIssue()
                this.changeDocumentType()
                this.changeCurrencyType()
            },
            changeDateOfIssue() {
                this.form.date_of_due = this.form.date_of_issue
                this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                    this.form.exchange_rate_sale = response
                })
            },
            changeDocumentType() {
                this.filterSuppliers()
            },
            addRow(row) {
                this.form.items.push(row)
                this.calculateTotal()
            },
            clickRemoveItem(index) {
                this.form.items.splice(index, 1)
                this.calculateTotal()
            },
            changeCurrencyType() {
                this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
                let items = []
                this.form.items.forEach((row) => {
                    items.push(calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
                });
                this.form.items = items
                this.calculateTotal()
            },
            
            addRow(row) {
                this.form.items.push(row)
                this.calculateTotal()
            },
            clickRemoveItem(index) {
                this.form.items.splice(index, 1)
                this.calculateTotal()
            },
            changeCurrencyType() {
                this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
                let items = []
                this.form.items.forEach((row) => {
                    items.push(calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
                });
                this.form.items = items
                this.calculateTotal()
            },
            calculateTotal() {
                let total_discount = 0
                let total_charge = 0
                let total_exportation = 0
                let total_taxed = 0
                let total_exonerated = 0
                let total_unaffected = 0
                let total_free = 0
                let total_igv = 0
                let total_value = 0
                let total = 0
                this.form.items.forEach((row) => {
                    total_discount += parseFloat(row.total_discount)
                    total_charge += parseFloat(row.total_charge)

                    if (row.affectation_igv_type_id === '10') {
                        total_taxed += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '20') {
                        total_exonerated += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '30') {
                        total_unaffected += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '40') {
                        total_exportation += parseFloat(row.total_value)
                    }
                    if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) < 0) {
                        total_free += parseFloat(row.total_value)
                    }

                    total_value += parseFloat(row.total_value)
                    total_igv += parseFloat(row.total_igv)
                    total += parseFloat(row.total)
                });

                this.form.total_exportation = formaterNumber(total_exportation)
                this.form.total_taxed = formaterNumber(total_taxed)
                this.form.total_exonerated = formaterNumber(total_exonerated)
                this.form.total_unaffected = formaterNumber(total_unaffected)
                this.form.total_free = formaterNumber(total_free)
                this.form.total_igv = formaterNumber(total_igv)
                this.form.total_value = formaterNumber(total_value)
                this.form.total_taxes = formaterNumber(total_igv)
                this.form.total = formaterNumber(total)
            },
            submit() {
                this.loading_submit = true
                //console.log(JSON.stringify(this.form))
                this.$http.post(`/${this.resource}/update/${this.purchase_id}`, this.form).then(response => {
                    
                    if (response.data.success) {
                        //this.resetForm();
                        this.close()
                        //this.documentNewId = response.data.data.id;
                        //this.showDialogOptions = true;
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    }
                    else {
                        this.$message.error(error.response.data.message);
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            },
            close() {
                location.href = '/purchases'
            },
            reloadDataSuppliers(supplier_id) {
                this.$http.get(`/${this.resource}/table/suppliers`).then((response) => {
                    this.aux_supplier_id = supplier_id
                    this.all_suppliers = response.data
                    this.filterSuppliers()
                })
            },
        }
    }
</script>