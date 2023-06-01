import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {createNotification} from "./lib/func/Notification";
import {ListManagement} from "./lib/ListManagement";


export default class extends Controller {

    connect() {

        $(document).ready(() => {
            const list = new ListManagement(
                $('#options_list'),
                '/worker/option_list',
                $('#option_add_button'),
                $('#option_delete_button'),
                $('<i class="loader">')
            )

            list.value_name_key = 'name'

            list.getList()

            list.setAddButton(
                '/worker/option_add',
                [$('#option_input')],
                function (arr_input) {
                    return true
                    const values = list.list.getValueList()
                    const input = arr_input[0]
                    const value = input.val().trim()
                    if (value.length < 1) {
                        input.addClass('error')
                        createNotification('Veuillez indiquer une option')
                        return false
                    } else if (values.includes(value)) {
                        input.addClass('error')
                        createNotification('Cette options est deja existante')
                        return false
                    } else {
                        input.removeClass('error')
                        return true
                    }
                },
                'La nouvelle option a bien était ajouté'
            )

            list.setDeleteButton(
                '/worker/option_delete',
                'options',
                'Les options on bien était supprimées',
                'Aucune options n\'est selectionné.'
            )
        })
    }
}