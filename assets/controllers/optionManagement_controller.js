import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {SelectorList} from "./lib/SelectorList";
import {request} from "./lib/Request";
import {createNotification} from "./lib/func/Notification";


export default class extends Controller {

    list = undefined

    connect() {
        this.getList()
        this.setAddButton()
        this.setDeleteButton()
    }

    getList() {
        request('/worker/option_list', {}, 'json')
            .then(response => {
                this.list = new SelectorList(response)
            })
            .catch(error => {
                this.list = error
                desactiveButton()
            })
            .finally(() => {
                this.setList()
            })
    }

    setList() {
        const elem = $('#options_list')
        elem.empty()
        if (typeof this.list == 'string')
            elem.append(`<p>${this.list}</p>`)
        else if (this.list)
            elem.append(this.list.getElem('name'))
    }

    setAddButton() {
        $('#option_add_button').on('click', () => {
            desactiveButton()
            const input = $('#option_input')
            const value = input.val().trim()
            if (value.length > 0) {
                request('/worker/option_add', {'option': value})
                    .then(response => {
                        if (response === '2')  {
                            createNotification('La nouvelle option a bien était ajouté')
                            this.getList()
                            input.val('')
                        }
                        else
                            createNotification('Cette option existe deja')
                    })
                    .catch(error => {
                        createNotification(error)
                    })
                    .finally(() => {
                        activateButton()
                    })
            }
            else {
                createNotification('Veuillez indiquer une valeur.')
                activateButton()
            }
        })
    }

    setDeleteButton() {
        $('#option_delete_button').on('click', () => {
            desactiveButton()
            const check_list = this.list.getSelectList()
            if (check_list.length > 0) {
                request('/worker/option_delete', {'options': check_list})
                    .then(() => {
                        createNotification('Les options on bien était supprimées.')
                        this.getList()
                    })
                    .catch(error => {
                        createNotification(error)
                    })
                    .finally(() => {
                        activateButton()
                    })
            }
            else {
                createNotification('Aucune options n\'est selectionné.')
                activateButton()
            }
        })
    }

}

function desactiveButton() {
    $('#option_add_button').prop('disabled', true)
    $('#option_delete_button').prop('disabled', true)
}

function activateButton() {
    $('#option_add_button').prop('disabled', false)
    $('#option_delete_button').prop('disabled', false)
}