import $ from "jquery";


export class InputRate {

    constructor(max_rate, input_elem, selected_elem, empty_elem, default_value = 1) {
        this.max_rate = max_rate
        this.input_elem = input_elem
        this.selected_elem = selected_elem
        this.empty_elem = empty_elem
        this.default_value = default_value
    }


    getElem(attr = '') {
        const final_elem = $(`<div ${attr}>`)

        for (let i = 1; i <= this.max_rate; i++) {

            const div = $('<div>')

            div.append(this.empty_elem.clone())
            div.append(this.selected_elem.clone())

            if (i <= this.default_value)
                switchDivSelected(div)
            else
                switchDivEmpty(div)

            div.on('click', () => {
                this.input_elem.val(i)

                div.prevAll().each(function () {
                    const elem = $(this);
                    switchDivSelected(elem)
                })
                div.nextAll().each(function () {
                    const elem = $(this);
                    switchDivEmpty(elem)
                })
                switchDivSelected(div)
            })

            final_elem.append(div)

        }
        this.input_elem.val(this.default_value)
        return final_elem

    }
}

function switchDivSelected(div) {
    const empty = div.children().eq(0)
    const select = div.children().eq(1)
    select.attr('hidden', false)
    empty.attr('hidden', true)

}

function switchDivEmpty(div) {
    const empty = div.children().eq(0)
    const select = div.children().eq(1)
    select.attr('hidden', true)
    empty.attr('hidden', false)
}