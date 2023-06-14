import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {TableManagement} from "./lib/TableManagement";
import {secureJqueryNodePrint} from "./lib/func/Security";


export default class extends Controller {

    connect() {

        $(document).ready(() => {
            const list = new TableManagement(
                $('#table_list'),
                '/worker/message_information',
                $('<i class="loader d-flex">')
            )

            list.value_name_keys = ['id', 'name', 'phone', 'email', 'date']

            list.sup_cols = [getDetailButton]


            list.setThead(['ID', 'Nom', 'Telephone', 'Email', 'Date', 'Détail'])

            list.getList()

            list.setDeleteButton(
                $('#delete_button'),
                '/worker/message_delete',
                'ids',
                'Les messages on bien était supprimé',
                'Aucun message n\'est selectionné'
            )
        })
    }
}

function getDetailButton(message) {

    const button = $('<button class="btn"><i class="bi bi-eject-fill"></i></button>')
    button.on('click', function () {
        const message_detail = $('#message_detail')
        message_detail.empty()


        const name = secureJqueryNodePrint('<p>', `Nom : ${message['name']}`)
        const phone = secureJqueryNodePrint('<p>', `Telephone : ${message['phone']}`)
        const email = secureJqueryNodePrint('<p>', `Email : ${message['email']}`)
        const text = secureJqueryNodePrint('<p>', `${message['message']}`)

        const info = $('<div class="col-lg-3 p-2">')
        info.append(name, phone, email)

        const content = $('<div class="col-lg-9 p-2">')
        content.append(text)

        const elem = $('<div class="d-flex flex-lg-row flex-column">')
        elem.append(info, content)

        message_detail.append(elem)
    })
    button.css('padding', '0 0.5em')
    button.css('color', 'white')
    return button
}