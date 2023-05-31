import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'


export default class extends Controller {

    connect() {
        $(document).ready(() => {
            this.setFormRadio();
        });
    }

    setFormRadio(selected_class = 'selected') {
        funcElems(
            '.form-radio',
            function (radio) {
                update_radio(radio, selected_class)
                radio.on('click', function () {
                    update_radio($(this), selected_class)
                })
            }
        )
    }
}

function funcElems(selector, func) {
    $(selector).each(function() {
        func($(this))
    });
}

/**
 * Ajoute un evenement qui met a jour la class du label en fonction de l'input checked
 * @param radio - Element radio formulaire symfony
 * @param checked_class - Class a ajouté dans le label quand l'input est checked
 */
function update_radio(radio, checked_class = 'selected') {
    radio.find('input').each(function () {
        const label = $('label[for="' + $(this).attr('id') + '"]')
        if ($(this).is(':checked'))
            label.addClass(checked_class)
        else
            label.removeClass(checked_class)
    })
}