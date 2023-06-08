import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {InputRate} from "./lib/class/InputRate";
import {createNotification} from "./lib/func/Notification";
import {request} from "./lib/func/Request";


export default class extends Controller {

    MAX_RATE = 5
    MAX_NAME_LENGTH = 100
    MAX_COMMENT_LENGTH = 100

    connect() {

        $(document).ready(() => {
            this.setElem()
            this.setStarRate()
            this.setSubmit()
        })
    }

    setElem() {
        this.input_rate = $('#comment_star_rate_value')
        this.input_comment = $('#comment_text')
        this.input_name = $('#comment_name')
        this.send_button = $('#comment_submit')
    }

    setStarRate() {
        const rate = new InputRate(
            this.MAX_RATE,
            this.input_rate,
            $('<i class="bi bi-star-fill">'),
            $('<i class="bi bi-star">'),
            5
        )

        $('#comment_star_rate').append(rate.getElem('class="form-rate-selector"'))
    }

    setSubmit() {
        this.send_button.on('click', () => {
            if (this.checkInputRate() && this.checkInputName() && this.checkInputComment()) {
                const poster = this.input_name.val()
                const comment = this.input_comment.val()
                const note = this.input_rate.val()
                request(
                    '/add_comment',
                    {
                        'poster': poster,
                        'comment': comment,
                        'note': note
                    })
                    .then(response => {
                        createNotification(response)
                        this.input_name.val('')
                        this.input_comment.val('')
                    })
                    .catch(message => {
                        createNotification(message)
                    })
            }
        })
    }

    checkInputRate() {
        const val = this.input_rate.val()
        if (val > this.MAX_RATE)
            this.input_rate.val(this.MAX_RATE)
        else if (val < 1)
            this.input_rate.val(1)
        return true
    }

    checkInputName() {
        return checkStringInput(this.input_name, 'Le nom', this.MAX_NAME_LENGTH, 6)
    }

    checkInputComment() {
        return checkStringInput(this.input_comment, 'Le commentaire', this.MAX_COMMENT_LENGTH, 10)
    }

}

function checkStringInput(input, subject, max_len, min_len)
{
    const string = input.val()
    if (string.length > max_len)
    {
        input.addClass('error')
        createNotification(`${subject} ne doit pas exceder ${max_len} carateres`)
        return false
    }
    else if (string.length < min_len)
    {
        input.addClass('error')
        createNotification(`${subject} doit contenir minimum ${min_len} carateres`)
        return false
    }
    input.removeClass('error')
    return true
}
