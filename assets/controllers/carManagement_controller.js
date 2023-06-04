import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {createNotification} from "./lib/func/Notification";
import {TableManagement} from "./lib/TableManagement";


export default class extends Controller {

    connect() {

        $(document).ready(() => {
            const list = new TableManagement(
                $('#table_list'),
                '/cars_informations',
                $('<i class="loader d-flex">')
            )

            list.value_name_keys = ['id', 'title', 'year', 'kilometers', 'fuel' , 'price']

            list.sup_cols = [getModificationLink, getOccasionLink]

            list.setThead(['ID', 'Titre', 'Année', 'Kilometres', 'Type énergie', 'Prix (€)', 'Modifié', 'Détails'])

            list.getList()

            list.setDeleteButton(
                $('#cars_delete_button'),
                '/worker/car_delete',
                'ids',
                'Les voitures on bien était supprimé',
                'Aucune voiture n\'est selectionné'
            )
        })
    }
}

function getModificationLink(car) {
    return $(`<a class="bi bi-pencil-square" target="_blank" href="${'/worker/car?id=' + car['id']}">`);
}

function getOccasionLink(car) {
    return $(`<a class="bi bi-box-arrow-up-right" target="_blank" href="${'/occasions/car?id=' + car['id']}">`);
}