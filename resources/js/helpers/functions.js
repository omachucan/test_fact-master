let validate_exchange_rate = true
function setExchangerate( in_validate_exchange_rate )
{
    validate_exchange_rate = in_validate_exchange_rate
}
function calculateRowItem(row_old, currency_type_id_new, exchange_rate_sale) {

    let currency_type_id_old = row_old.item.currency_type_id
    let unit_price = parseFloat(row_old.item.unit_price)
    if(validate_exchange_rate)
    {
        if (currency_type_id_old === 'PEN' && currency_type_id_old !== currency_type_id_new)
        {
            unit_price = _.round(unit_price / exchange_rate_sale, 2)
        }

        if (currency_type_id_new === 'PEN' && currency_type_id_old !== currency_type_id_new)
        {
            unit_price = _.round(unit_price * exchange_rate_sale, 2)
        }
    }


    let row = {
        item_id: row_old.item.id,
        // item_description: row_old.item.description,
        item: row_old.item,
        currency_type_id: currency_type_id_new,
        quantity: row_old.quantity,
        unit_value: 0,
        affectation_igv_type_id: row_old.affectation_igv_type_id,
        affectation_igv_type: row_old.affectation_igv_type,
        total_base_igv: 0,
        percentage_igv: 18,
        total_igv: 0,
        system_isc_type_id: null,
        total_base_isc: 0,
        percentage_isc: 0,
        total_isc: 0,
        total_base_other_taxes: 0,
        percentage_other_taxes: 0,
        total_other_taxes: 0,
        total_plastic_bag_taxes: 0,
        total_taxes: 0,
        price_type_id: '01',
        unit_price: unit_price,
        total_value: 0,
        total_discount: 0,
        total_charge: 0,
        total: 0,
        attributes: row_old.attributes,
        charges: row_old.charges,
        discounts: row_old.discounts,
        // included_igv: row_old.included_igv,
        // item_price_list: row_old.item_price_list
    };

    let percentage_igv = 18

    let unit_value = row.unit_price

    row.unit_price = (row.item.included_igv)?unit_value:unit_value*(1+percentage_igv/100)

    if (row.affectation_igv_type_id === '10') {
        unit_value = row.unit_price / (1 + percentage_igv / 100)
    }

    row.unit_value = _.round(unit_value, 2)

    let total_value_partial = (unit_value * row.quantity)

    /* Discounts */
    let discount_base = 0
    let discount_no_base = 0
    row.discounts.forEach((discount, index) => {
        discount.percentage = parseFloat(discount.percentage)
        discount.factor = discount.percentage / 100
        discount.base = _.round(total_value_partial, 2)
        discount.amount = _.round(discount.base * discount.factor, 2)
        if (discount.discount_type.base) {
            discount_base += discount.amount
        } else {
            discount_no_base += discount.amount
        }
        row.discounts.splice(index, discount)
    })

    /* Charges */
    let charge_base = 0
    let charge_no_base = 0
    row.charges.forEach((charge, index) => {
        charge.percentage = parseFloat(charge.percentage)
        charge.factor = charge.percentage / 100
        charge.base = _.round(total_value_partial, 2)
        charge.amount = _.round(charge.base * charge.factor, 2)
        if (charge.charge_type.base) {
            charge_base += charge.amount
        } else {
            charge_no_base += charge.amount
        }
        row.charges.splice(index, charge)
    })

    let total_isc = 0
    let total_other_taxes = 0

    let total_discount = discount_base + discount_no_base
    let total_charge = charge_base + charge_no_base
    let total_value = total_value_partial - total_discount + total_charge
    let total_base_igv = total_value_partial - discount_base + total_isc

    let total_igv = 0
    let total_taxes = 0

    if (row.affectation_igv_type_id === '10') {
        total_igv = total_base_igv * percentage_igv / 100
    }
    if (row.affectation_igv_type_id === '20') { //Exonerated
        total_igv = 0
    }
    if (row.affectation_igv_type_id === '30') { //Unaffected
        total_igv = 0
    }

    if (row.affectation_igv_type_id === '15' || row.affectation_igv_type_id === '14') { //bonus

        let total_value_partial = unit_value * row.quantity
        total_taxes = total_value - total_value_partial
        total_igv = total_value* (percentage_igv / 100)
        total_base_igv = total_value_partial

        total_value -= row.total_value
    }

    total_taxes = total_igv + total_isc + total_other_taxes
    let total = total_value + total_taxes

    row.total_charge = total_charge
    row.total_discount = total_discount
    row.total_value = total_value
    row.total_base_igv = total_base_igv
    row.total_igv = total_igv
    row.total_taxes = total_taxes
    row.total = total

    if (row.affectation_igv_type.free) {
        row.price_type_id = '02'
        row.unit_value = 0
        row.total = 0
    }

    let amount_plastic_bag_taxes = amount_plastic()

    //impuesto bolsa
    if(row_old.has_plastic_bag_taxes){
        row.total_plastic_bag_taxes = _.round(row.quantity * amount_plastic_bag_taxes, 1)
    }

    return row
}

function formaterDecimal(stock){

    var split = stock.split(".", 2);

    if(split[1] == '0000'){
        stock = Math.trunc(stock)
    }

    return stock
}

function formaterNumber(value, decimal = 2) {

    if (value == undefined || value == "" || value == null) {
        return 0;
    }
    else {
        return parseFloat(value).toFixed(decimal).replace(/\d(?=(\d{3})+\.)/g, '$&');
    }
}

function amount_plastic(){
    var date = new Date();
    var anio = date.getFullYear();

    let value;

    switch (anio) {
        case 2019:
            value = 0.1
            break;
        case 2020:
            value = 0.2
            break;
        case 2021:
            value = 0.3
            break;
        default:
            value = 0.1
    }

    return value
}

export {calculateRowItem, formaterDecimal, formaterNumber, setExchangerate}
