<template>
    <div class="box ">
        <div class="box-body no-padding">
            <div class="el-form-item  col-xs-12" v-if="warehouses.length > 0">
                <small>Almacén:</small>
                <br>
                <div class="el-form-item__content">
                    <el-select v-model="warehouse" name="warehouse" filterable class="border-left rounded-left border-info" dusk="warehouse_td">
                        <el-option v-for="option in warehouses" :key="option.id" :value="option.id" :label="option.description"></el-option>
                    </el-select>
                </div>
            </div>
            <div class="el-form-item  col-xs-12" v-if="items.length > 0">
                <small>Productos:</small>
                <br>
                <div class="el-form-item__content">
                    <el-select v-model="item" name="item" filterable class="border-left rounded-left border-info" popper-class="el-select-customers" dusk="item_td">
                        <el-option v-for="option in items" :key="option.id" :value="option.id" :label="option.description"></el-option>
                    </el-select>
                </div>
            </div>
            <div class="el-form-item  col-xs-12">
                <small></small>
                <br>
                <div class="el-form-item__content">
                    <button class="btn btn-custom" type="submit"><i class="fa fa-search"></i> Buscar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'warehouse_td': {
                required: false,
                default: ''
            },
            'warehouses': {
                required: true,
                default: ''
            },
            'item_td': {
                required: false,
                default: ''
            },
            'items': {
                required: true,
                default: ''
            }
        },
        data() {
            return {
                warehouse: null,
                item: ''
            }
        },
        created() {
            this.warehouse = (this.warehouse_td != '') ? this.warehouses.find(row => row.id == this.warehouse_td).id : null;
            this.item = (this.item_td != '') ? this.items.find(row => row.id == this.item_td).id : this.items[0].id;
        }
    }
</script>
