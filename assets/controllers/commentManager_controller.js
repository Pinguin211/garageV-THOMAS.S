import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {TableManagement} from "./lib/TableManagement";
import {createNotification} from "./lib/func/Notification";


export default class extends Controller {

    connect() {

        $(document).ready(() => {
            this.setCommentList()
            this.setButtonList()
            this.setCheckboxBtn()
        })
    }

    setCommentList() {
        const list = new TableManagement(
            $('#table_list'),
            '/comment_informations',
            $('<i class="loader d-flex">')
        )

        list.value_name_keys = ['id', 'poster', 'note', 'date', 'comment']

        list.setThead(['ID', 'Auteur', 'Note (/5)', 'Date', 'Commentaire'])

        list.data_func = () => {
            const valid_check = $('#validate_comment').is(':checked')
            const unvalid_check = $('#unvalidate_comment').is(':checked')

            let i = 0;
            if (valid_check)
                i += 1
            if (unvalid_check)
                i += 2
            if (i === 0)
                i = 3
            return {'option': i}
        }
        list.getList()
        this.list = list
    }

    setButtonList() {

        this.list.setDeleteButton(
            $('#remove_comment'),
            '/worker/remove_comment',
            'ids',
            'Les avis on était supprimé',
            'Aucun avis sélectionné'
        )

        this.list.addRequestButtonIds(
            $('#unvalid_comment'),
            '/worker/unvalidate_comment',
            'ids',
            'Les avis on était invalidés',
            'Aucun avis sélectionné'
        )

        this.list.addRequestButtonIds(
            $('#valid_comment'),
            '/worker/validate_comment',
            'ids',
            'Les avis on était validés',
            'Aucun avis sélectionné'
        )

        $(document).ajaxSuccess((event, xhr, settings) => {
            if (settings.url === '/add_comment') {
                this.list.getList()
            }
        });
    }


    setCheckboxBtn() {
        const valid_check = $('#validate_comment')
        const unvalid_check = $('#unvalidate_comment')

        valid_check.on('change', () => {
            if (this.list)
                this.list.getList()
        })
        unvalid_check.on('change', () => {
            if (this.list)
                this.list.getList()
        })
    }
}