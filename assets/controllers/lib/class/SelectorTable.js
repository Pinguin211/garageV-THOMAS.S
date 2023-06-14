import $ from "jquery";
import {secureJqueryNodePrint} from "../func/Security";

/**
 * Creer une table avec les element sélectionnable par une checkbox
 */
export class SelectorTable {

    constructor(arr, tbody_class = '', table_class = '',  tr_id = 'SelectorRow_') {
        this.arr = arr
        this.ids = []
        this.tbody_class = tbody_class
        this.table_class = table_class
        this.tr_id = tr_id
        this.elem = undefined
        this.thead = undefined
        this.sup_cols = []
        this.values_keys = []
    }

    setThead(arr_names, thead_class = '')
    {
        const tr = $('<tr>')
        tr.append($('<th>'))
        let i = 0
        arr_names.forEach(function (value) {
            tr.append($(`<th>${value}</th>`))
            i += 1
        })
        const thead = $(`<thead class="${thead_class}">`)
        thead.append(tr)
        this.thead = thead
        return thead
    }

    /**
     * Ajoute une colonne à la table selon la fonction donné
     * @param func - La fonction prend comme premier parametre un obj de ${this.arr} et doit retourné une node jquery
     */
    addCol(func) {
        this.sup_cols.push(func)
    }


    getElem(checkbox_id_name_key = 'id') {
        let ids = []
        const tbody = $(`<tbody class="${this.tbody_class}">`)

        Object.entries(this.arr).forEach(([, value]) => {
            const id = value[checkbox_id_name_key].toString()
            ids.push(id)

            const tr = $(`<tr id="${this.tr_id + id}">`)
            const td_input = $('<td>')
            td_input.append('<input type="checkbox">')
            tr.append(td_input)

            this.values_keys.forEach(function (key) {
                console.log(key)
                tr.append(secureJqueryNodePrint('<td>', value[key]))
            })
            this.sup_cols.forEach(function (func) {
                const v = func(value)
                const td = $('<td>')
                td.append(v)
                tr.append(td)
            })
            tbody.append(tr)
        })
        const table = $(`<table class="${this.table_class}">`)
        if (this.thead !== undefined)
            table.append(this.thead)
        table.append(tbody)

        this.elem = table
        this.ids = ids

        return table
    }

    /**
     * Renvoie la list des keys des element actuelement selectionné
     */
    getSelectTable() {
        let res = []
        if (this.elem === undefined)
            return res
        else {
            const tr_id = this.tr_id
            const keys = this.ids
            this.elem.find('tbody').find('tr').each(function () {
                const checkbox = $(this).first().find('input')
                if (checkbox && checkbox.is(':checked')) {
                    let id = $(this).attr('id')
                    id = id.replace(tr_id, '')
                    if (keys.includes(id))
                        res.push(id)
                }
            })
            return res
        }
    }

    /**
     * Check les element checkbox avec les id de la list donné
     * @param ids_to_check - list ids
     */
    setSelectListCheckbox(ids_to_check) {
        if (this.elem !== undefined) {
            const tr_id = this.tr_id
            this.elem.find('tbody').find('tr').each(function () {
                const id = $(this).attr('id').replace(tr_id, '')
                if (ids_to_check.includes(id))
                    $(this).first().find('input').prop('checked', true)
            })
        }
    }
}