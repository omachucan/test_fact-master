<template>
    <div>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12 ">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 pb-2">
                        <div class="d-flex">
                            <div style="width:100px">
                                Filtrar por:
                            </div>
                            <el-select v-model="search.column"  placeholder="Select" @change="changeClearInput">
                                <el-option v-for="(label, key) in columns" :key="key" :value="key" :label="label"></el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 pb-2">
                        <template v-if="search.column=='date_of_issue'">
                            <el-date-picker
                                v-model="search.value"
                                type="date"
                                style="width: 100%;"
                                placeholder="Buscar"
                                value-format="yyyy-MM-dd"
                                @change="getRecords2">
                            </el-date-picker>
                        </template>
                        <template v-else>
                            <el-input placeholder="Buscar"
                                v-model="search.value"
                                style="width: 100%;"
                                prefix-icon="el-icon-search"
                                @input="getRecords2">
                            </el-input>
                        </template>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                        <slot name="heading"></slot>
                        </thead>
                        <tbody>
                        <slot v-for="(row, index) in records" :row="row" :index="customIndex(index)" name="tbody"></slot>
                        </tbody>
                    </table>
                    <slot name="totals" :totals="totals"></slot>
                    <div>
                        <el-pagination
                            @current-change="getRecords2"
                            layout="total, prev, pager, next"
                            :total="pagination.total"
                            :current-page.sync="pagination.current_page"
                            :page-size="pagination.per_page">
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import moment from 'moment'
    import queryString from 'query-string'

    export default {
        props: {
            resource: String,
        },
        data () {
            return {
                search: {
                    column: null,
                    value: null
                },
                columns: [],
                records: [],
                totals: [],
                pagination: {}
            }
        },
        computed: {
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getRecords()
                this.getTotals()
            })
        },
        async mounted () {
            let column_resource = _.split(this.resource, '/')
            await this.$http.get(`/${_.head(column_resource)}/columns`).then((response) => {
                this.columns = response.data
                this.search.column = _.head(Object.keys(this.columns))
            });
            await this.getRecords()
            await this.getTotals()
        },
        methods: {
            customIndex(index) {
                return (this.pagination.per_page * (this.pagination.current_page - 1)) + index + 1
            },
            getRecords() {
                return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`).then((response) => {
                    this.records = response.data.data
                    this.pagination = response.data.meta
                    this.pagination.per_page = parseInt(response.data.meta.per_page)
                });
            },
            getTotals(){
                return this.$http.get(`/${this.resource}/totals?${this.getQueryParameters()}`).then((response) => {
                    this.totals = response.data.data
                });
            },
            getQueryParameters() {
                return queryString.stringify({
                    page: this.pagination.current_page,
                    limit: this.limit,
                    ...this.search
                })
            },
            getRecords2(){
                this.getRecords()
                this.getTotals()
            },
            changeClearInput(){
                this.search.value = ''
                this.getRecords()
                this.getTotals()
            }
        }
    }
</script>