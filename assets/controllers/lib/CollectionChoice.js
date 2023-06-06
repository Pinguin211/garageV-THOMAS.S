import $ from 'jquery'
import {createNotification} from "./func/Notification";


export class CollectionChoice {

    constructor(widget_elem, row_elem, add_limit = 100) {
        this.row_elem = row_elem
        this.widget_elem = widget_elem
        this.proto = widget_elem.attr('data-prototype')
        this.add_limit = add_limit
        this.delete_button = false
    }

    setAddButton(button_attr, text = 'Ajouté') {
        const button = $(`<button type="button" ${button_attr}>${text}</button>`)
        button.on('click', () => {
            const i = this.widget_elem.children().length
            if (i >= this.add_limit)
                createNotification('Ajouts impossible')
            else {
                const new_input = $(this.proto.replaceAll('__name__label__', i).replaceAll('__name__', i))
                this.setElemDeleteButton(new_input)
                this.widget_elem.append(new_input)
            }
        })
        this.row_elem.append(button)
    }

    setDeleteButton(selector = '<button type="button">Supprimé</button>') {
        this.delete_button = selector
        const childs = this.widget_elem.children()
        const here = this
        childs.each(function () {
           here.setElemDeleteButton($(this))
        })
    }

    setElemDeleteButton(elem) {
        if (this.delete_button) {
            const button = $(this.delete_button)
            button.on('click', () => {
                let i = 0
                let new_elem = elem
                while (new_elem.next().length && i < this.add_limit) {
                    const next = new_elem.next()
                    new_elem.find('input').val(next.find('input').val())
                    new_elem = next
                    i += 1
                }
                new_elem.remove()
            })
            elem.append(button)
        }
    }
}
