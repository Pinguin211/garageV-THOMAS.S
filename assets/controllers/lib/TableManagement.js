import {request} from "./func/Request";
import {createNotification} from "./func/Notification";
import {SelectorTable} from "./class/SelectorTable";


export class TableManagement {

    /**
     * @param table_elem - Element dans le quel ajouté la table
     * @param getUrl - Url ou recuperer les donné de la table
     * @param loader - Element affiché pendant le chargement de la liste
     */
    constructor(table_elem, getUrl, loader = undefined) {
        this.table_elem = table_elem
        this.url = getUrl
        this.loader = loader
        this.add_button = undefined
        this.delete_button = undefined
        this.table = undefined
        /**
         * Clé de la valeur dans l'array de reponse
         * @type {array}
         */
        this.value_name_keys = []

        /* Ajouter un thead */
        this.thead = false
        this.thead_names = undefined
        this.thead_class = undefined

        /*Ajouter des colonne */
        this.sup_cols = []
    }

    getList() {
        if (this.loader)
            this.table_elem.append(this.loader)
        request(this.url, {}, 'json')
            .then(response => {
                this.table = new SelectorTable(response)
                this.table.values_keys = this.value_name_keys
                if (this.thead)
                    this.table.setThead(this.thead_names, this.thead_class)
                const table = this.table
                this.sup_cols.forEach(function (func) {
                    table.addCol(func)
                })
            })
            .catch(error => {
                this.table = error
                this.desactiveButton()
            })
            .finally(() => {
                this.setList()
            })
    }

    setList() {
        const elem = this.table_elem
        elem.empty()
        if (typeof this.table == 'string')
            elem.append(`<p>${this.table}</p>`)
        else if (this.table)
        {
            elem.append(this.table.getElem())
        }
    }

    setThead(arr_names, thead_class = '')
    {
        this.thead = true
        this.thead_names = arr_names
        this.thead_class = thead_class
    }

    desactiveButton() {
        if (this.add_button !== undefined)
            this.add_button.prop('disabled', true)
        if (this.delete_button !== undefined)
            this.delete_button.prop('disabled', true)
    }

    activateButton() {
        if (this.add_button !== undefined)
            this.add_button.prop('disabled', false)
        if (this.delete_button !== undefined)
            this.delete_button.prop('disabled', false)
    }

    /**
     * @param add_button_elem - Element qui envera les informations des inputs avec un click
     * @param sendUrl - Url ou envoyé les donné des input
     * @param arr_input - Array des input ou collecter les donné
     * @param verification_func - Fonction de verification des input, prend comme argument ${arr_input} et doit retourné un bool true si les verif son correct
     * @param then_message - Message de reussite
     */
    setAddButton(add_button_elem, sendUrl, arr_input, verification_func, then_message) {
        add_button_elem.on('click', () => {
            this.desactiveButton()
            if (verification_func(arr_input)) {
                let data = {}
                arr_input.forEach(function (input) {
                    const name = input.attr('name')
                    data[name] = input.val().trim()
                })
                request(sendUrl, data)
                    .then(() => {
                        createNotification(then_message)
                        arr_input.forEach(function (input) {
                            input.val('')
                        })
                        this.getList()
                    })
                    .catch(error => {
                        createNotification(error)
                    })
                    .finally(() => {
                        this.activateButton()
                    })
            } else
                this.activateButton()
        })
        this.add_button = add_button_elem
    }

    /**
     * @param delete_button_elem - Element qui suprimera les element dans la liste avec un click
     * @param sendUrl - url ou envoyé les donné
     * @param data_name - nom de la donné dans le $_POST
     * @param then_message - Message de reussite
     * @param no_selection_message - Message si aucun element n'est selectionné
     */
    setDeleteButton(delete_button_elem, sendUrl, data_name, then_message, no_selection_message) {
        delete_button_elem.on('click', () => {
            this.desactiveButton()
            const check_list = this.table.getSelectTable()
            if (check_list.length > 0) {
                request(sendUrl, {[data_name]: check_list})
                    .then(() => {
                        createNotification(then_message)
                        this.getList()
                    })
                    .catch(error => {
                        createNotification(error)
                    })
                    .finally(() => {
                        this.activateButton()
                    })
            } else {
                createNotification(no_selection_message)
                this.activateButton()
            }
        })
        this.delete_button = delete_button_elem
    }

}