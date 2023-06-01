import {SelectorList} from "./class/SelectorList";
import {request} from "./func/Request";
import {createNotification} from "./func/Notification";


export class ListManagement {

    /**
     *
     * @param list_elem - Element dans le quel ajouté la liste
     * @param getUrl - Url ou recuperer les donné de la liste
     * @param add_button_elem - Element qui envera les informations des inputs avec un click
     * @param delete_button_elem - Element qui suprimera les element dans la liste avec un click
     * @param loader - Element affiché pendant le chargement de la liste
     */
    constructor(list_elem, getUrl, add_button_elem, delete_button_elem, loader = undefined) {
        this.list_elem = list_elem
        this.url = getUrl
        this.add_button = add_button_elem
        this.delete_button = delete_button_elem
        this.loader = loader
        this.list = undefined
        /**
         * Clé de la valeur dans l'array de reponse
         * @type {string}
         */
        this.value_name_key = 'value'
    }

    getList() {
        if (this.loader)
            this.list_elem.append(this.loader)
        request(this.url, {}, 'json')
            .then(response => {
                this.list = new SelectorList(response)
            })
            .catch(error => {
                this.list = error
                this.desactiveButton()
            })
            .finally(() => {
                this.setList()
            })
    }

    setList() {
        const elem = this.list_elem
        elem.empty()
        if (typeof this.list == 'string')
            elem.append(`<p>${this.list}</p>`)
        else if (this.list)
            elem.append(this.list.getElem(this.value_name_key))
    }

    desactiveButton() {
        this.add_button.prop('disabled', true)
        this.delete_button.prop('disabled', true)
    }

    activateButton() {
        this.add_button.prop('disabled', false)
        this.delete_button.prop('disabled', false)
    }

    /**
     *
     * @param sendUrl - Url ou envoyé les donné des input
     * @param arr_input - Array des input ou collecter les donné
     * @param verification_func - Fonction de verification des input, prend comme argument ${arr_input} et doit retourné un bool true si les verif son correct
     * @param then_message - Message de reussite
     */
    setAddButton(sendUrl, arr_input, verification_func, then_message) {
        this.add_button.on('click', () => {
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
            }
            else
                this.activateButton()
        })
    }

    /**
     *
     * @param sendUrl - url ou envoyé les donné
     * @param data_name - nom de la donné dans le $_POST
     * @param then_message - Message de reussite
     * @param no_selection_message - Message si aucun element n'est selectionné
     */
    setDeleteButton(sendUrl, data_name, then_message, no_selection_message) {
        this.delete_button.on('click', () => {
            this.desactiveButton()
            const check_list = this.list.getSelectList()
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
            }
            else {
                createNotification(no_selection_message)
                this.activateButton()
            }
        })
    }

}