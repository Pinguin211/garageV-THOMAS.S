import $ from "jquery";
import {setKeysToString} from "../func/Table";
import {secureJqueryNodePrint} from "../func/Security";

/**
 * Creer une liste avec les element sélectionnable par une checkbox
 */
export class SelectorList {

    /**
     * @param arr - Exemple : {1: {id: 23, 'name': 'Pomme'}, {id: 34, 'name': 'Poire'}, {id: 45, 'name': 'Ananas'}}
     * @param ul_id - ID de la liste
     * @param li_id - Base de l'ID de la ligne, l'id est completé par la clé de ${arr}
     * @param ul_class - class pour ul
     * @param li_class - class pour li
     */
    constructor(arr, ul_class = '', li_class = '', ul_id = 'SelectorList', li_id = 'SelectorLine_') {
        this.arr = arr
        this.ids = []
        this.ul_id = ul_id
        this.li_id = li_id
        this.ul_class = ul_class
        this.li_class = li_class
        this.elem = undefined
    }

    /**
     * Renvoie la liste sous forme d'elemnt jquery
     *
     * HTML exempe:
     *
     * <ul id="${ul_id}" class="${ul_class}">
     *     <li id="${li_id + key}" class="${li_class}">
     *         <input type="checkbox">
     *         <p>${value}</p>
     *     </li>
     * </ul>
     *
     * @return {*|jQuery|HTMLElement}
     */
    getElem(value_name_key, checkbox_id_name_key = 'id') {
        let ids = []
        const ul = $(`<ul class="${this.ul_class}" id="${this.ul_id}">`)
        Object.entries(this.arr).forEach(([, value]) => {
            const id = value[checkbox_id_name_key].toString()
            ids.push(id)
            ul.append(
                $(`<li class="${this.li_class}" id="${this.li_id + id}">`).append(
                    $('<input type="checkbox">'),
                    secureJqueryNodePrint('<p>', value[value_name_key]) // Print securisé contre XSS
                ))
        })
        this.ids = ids
        this.elem = ul
        return ul
    }

    /**
     * Renvoie la list des keys des element actuelement selectionné
     */
    getSelectList() {
        let res = []
        if (this.elem === undefined)
            return res
        else {
            const li_id = this.li_id
            const keys = this.ids
            this.elem.find('li').each(function () {
                const checkbox = $(this).find('input')
                if (checkbox && checkbox.is(':checked')) {
                    let id = $(this).attr('id')
                    id = id.replace(li_id, '')
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
            const li_id = this.li_id
            this.elem.find('li').each(function () {
                const id = $(this).attr('id').replace(li_id, '')
                if (ids_to_check.includes(id))
                    $(this).find('input').prop('checked', true)
            })
        }
    }

    /**
     * Renvoie la liste des valeurs dans la list
     */
    getValueList() {
        if (this.elem) {
            let values = []
            this.elem.find('li').each(function () {
                values.push($(this).find('p').text())
            })
            return values
        }
        else
            return []
    }
}