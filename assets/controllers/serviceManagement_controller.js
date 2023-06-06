import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {TableManagement} from "./lib/TableManagement";


export default class extends Controller {

    connect() {

        $(document).ready(() => {
            const list = new TableManagement(
                $('#table_list'),
                '/service_informations',
                $('<i class="loader d-flex">')
            )

            list.value_name_keys = ['id', 'name']

            list.sup_cols = [getModificationLink]

            list.setThead(['ID', 'Titre', 'Modifié'])

            list.getList()

            list.setDeleteButton(
                $('#service_delete_button'),
                '/admin/service_delete',
                'ids',
                'Les services on bien était supprimé',
                'Aucun service n\'est selectionné'
            )
        })
    }
}

function getModificationLink(service) {
    return $(`<a class="bi bi-pencil-square" target="_blank" href="${'/admin/service?id=' + service['id']}">`);
}