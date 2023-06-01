import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {createNotification} from "./lib/func/Notification";
import {ListManagement} from "./lib/ListManagement";


export default class extends Controller {

    connect() {

        $(document).ready(() => {
            const list = new ListManagement(
                $('#worker_list'),
                '/admin/worker_list',
                $('#add_button'),
                $('#delete_button')
            )

            list.value_name_key = 'email'

            list.getList()

            list.setAddButton(
                '/admin/add_worker',
                [$('#email_input'), $('#password_input')],
                function (arr_input) {
                    const emails = list.list.getValueList()
                    const email_input = arr_input[0]
                    const email = email_input.val().trim()
                    if (emails.includes(email)) {
                        createNotification("Cette email est deja existant")
                        email_input.addClass('error')
                        return false
                    }
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        createNotification("L'email n'est pas au bon format ( exemple@gmail.fr )")
                        email_input.addClass('error')
                        return false
                    }
                    const password_input = arr_input[1]
                    if (!/^(?=.*\S{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*\d)\S*$/.test(password_input.val().trim())) {
                        createNotification("Le mot de passe doit contenir au minimum 8 caractères, 1 minuscule, 1 majuscule et 1 chiffre")
                        password_input.addClass('error')
                        return false
                    }
                    password_input.removeClass('error')
                    email_input.removeClass('error')
                    return true
                },
                'Le nouvelle employé à bien était ajouté'
            )

            list.setDeleteButton(
                '/admin/delete_worker',
                'ids',
                'Les employés on bien était supprimés',
                'Aucun employé n\'est selectionné.'
            )
        })
    }
}