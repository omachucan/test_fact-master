<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.identity_document_type_id}">
                            <label class="control-label">Tipo Doc. Identidad <span class="text-danger">*</span></label>
                            <el-select v-model="form.identity_document_type_id" filterable  popper-class="el-select-identity_document_type" dusk="identity_document_type_id" >
                                <el-option v-for="option in identity_document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.identity_document_type_id" v-text="errors.identity_document_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.number}">
                            <label class="control-label">Número <span class="text-danger">*</span></label>
                            <el-input v-model="form.number" :maxlength="maxLength" :minlength="maxLength" dusk="number">
                                <template v-if="form.identity_document_type_id === '6' || form.identity_document_type_id === '1'">
                                    <el-button type="primary" slot="append" :loading="loading_search" icon="el-icon-search" @click.prevent="searchCustomer">
                                        <template v-if="form.identity_document_type_id === '6'">
                                            SUNAT
                                        </template>
                                        <template v-if="form.identity_document_type_id === '1'">
                                            RENIEC
                                        </template>
                                    </el-button>
                                </template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre <span class="text-danger">*</span></label>
                            <el-input v-model="form.name" dusk="name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.trade_name}">
                            <label class="control-label">Nombre comercial</label>
                            <el-input v-model="form.trade_name" dusk="trade_name"></el-input>
                            <small class="form-control-feedback" v-if="errors.trade_name" v-text="errors.trade_name[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.country_id}">
                            <label class="control-label">País</label>
                            <el-select v-model="form.country_id" filterable dusk="country_id">
                                <el-option v-for="option in countries" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.country_id" v-text="errors.country_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.department_id}">
                            <label class="control-label">Departamento</label>
                            <el-select v-model="form.department_id" filterable @change="filterProvince" popper-class="el-select-departments" dusk="department_id">
                                <el-option v-for="option in all_departments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.department_id" v-text="errors.department_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.province_id}">
                            <label class="control-label">Provincia</label>
                            <el-select v-model="form.province_id" filterable @change="filterDistrict" popper-class="el-select-provinces" dusk="province_id">
                                <el-option v-for="option in provinces" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.province_id" v-text="errors.province_id[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.province_id}">
                            <label class="control-label">Distrito</label>
                            <el-select v-model="form.district_id" filterable popper-class="el-select-districts" dusk="district_id">
                                <el-option v-for="option in districts" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.district_id" v-text="errors.district_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.address}">
                            <label class="control-label">Dirección</label>
                            <el-input v-model="form.address" dusk="address"></el-input>
                            <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.telephone}">
                            <label class="control-label">Teléfono</label>
                            <el-input v-model="form.telephone" dusk="telephone"></el-input>
                            <small class="form-control-feedback" v-if="errors.telephone" v-text="errors.telephone[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label class="control-label">Correo electrónico</label>
                            <el-input v-model="form.email" dusk="email"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    import {serviceNumber} from '../../../mixins/functions'

    export default {
        mixins: [serviceNumber],
        props: ['showDialog', 'type', 'recordId', 'external', 'document_type_id'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'persons',
                errors: {},
                form: {},
                countries: [],
                all_departments: [],
                all_provinces: [],
                all_districts: [],
                provinces: [],
                districts: [],
                identity_document_types: []
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.countries = response.data.countries
                    this.all_departments = response.data.departments
                    this.all_provinces = response.data.provinces
                    this.all_districts = response.data.districts
                    this.identity_document_types = response.data.identity_document_types
                })
        },
        computed: {
            maxLength: function () {
                if (this.form.identity_document_type_id === '6') {
                    return 11
                }
                if (this.form.identity_document_type_id === '1') {
                    return 8
                }
            }
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    type: this.type,
                    identity_document_type_id: '6',
                    number: null,
                    name: null,
                    trade_name: null,
                    country_id: 'PE',
                    department_id: null,
                    province_id: null,
                    district_id: null,
                    address: null,
                    telephone: null,
                    email: null
                }
            },
            create() {
                if(this.external) {
                    if(this.document_type_id === '01') {
                        this.form.identity_document_type_id = '6'
                    }
                    if(this.document_type_id === '03') {
                        this.form.identity_document_type_id = '1'
                    }
                }
                if(this.type === 'customers') {
                    this.titleDialog = (this.recordId)? 'Editar Cliente':'Nuevo Cliente'
                }
                if(this.type === 'suppliers') {
                    this.titleDialog = (this.recordId)? 'Editar Proveedor':'Nuevo Proveedor'
                }
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                            this.filterProvinces()
                            this.filterDistricts()
                        })
                }
            },
            submit() {
                this.loading_submit = true

                if (this.form.identity_document_type_id == 6) {
                    length = 11
                }else if(this.form.identity_document_type_id == 4 || this.form.identity_document_type_id == 7  ){
                    length = 9
                }else{
                    length = 8
                }
            
                if(this.form.number.length != length && this.form.identity_document_type_id != 0){
                    this.$message.error("El número debe tener "+length+" dígitos")
                    this.loading_submit = false
                }
                else{
                    this.$http.post(`/${this.resource}`, this.form)
                        .then(response => {
                            if (response.data.success) {
                                this.$message.success(response.data.message)
                                if (this.external) {
                                    this.$eventHub.$emit('reloadDataPersons', response.data.id)
                                } else {
                                    this.$eventHub.$emit('reloadData')
                                }
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
                this.$emit('update:showDialog', false)
                this.initForm()
            },
            searchCustomer() {
                this.searchServiceNumberByType()
            }
        }
    }
</script>