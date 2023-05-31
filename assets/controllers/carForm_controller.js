import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {SelectorList} from "./lib/SelectorList";
import {request} from "./lib/Request";
import {SendImage} from "./lib/class/SendImage";
import {getSvgPhoto} from "./lib/func/SvgElement";
import {createNotification} from "./lib/func/Notification";


export default class extends Controller {

    //Liste des options
    list = undefined


    //Widgets des images


    connect() {
        this.getList() // recupere la liste des options
        this.setFormAction() // Ajoute les event pour lier la liste des options au formulaire

       const senders = {
        "1": this.AddImageWidget(1), // Ajoute le widgets pour l'ajouts des images
        "2": this.AddImageWidget(2), // Ajoute le widgets pour l'ajouts des images
        "3": this.AddImageWidget(3), // Ajoute le widgets pour l'ajouts des images
        "4": this.AddImageWidget(4), // Ajoute le widgets pour l'ajouts des images
        "5": this.AddImageWidget(5), // Ajoute le widgets pour l'ajouts des images
        }

        const params = getUrlParams();
        if (params.hasOwnProperty('id')) {
            setActualMiniature(params.id, senders)
        }
    }

    //Liste des option

    getList() {
        request('/worker/option_list', {}, 'json')
            .then(response => {
                this.list = new SelectorList(response)
            })
            .catch(error => {
                this.list = error
            })
            .finally(() => {
                this.setList()
            })
    }

    setList() {
        const elem = $('#options_list')
        elem.empty()
        if (typeof this.list == 'string')
            elem.append(`<p>${this.list}</p>`)
        else if (this.list) {
            elem.append(this.list.getElem('name'))
            this.list.setSelectListCheckbox(getCheckedOptionForm())
        }
    }

    setFormAction() {
        const form = $('form[name="car"]')
        const here = this
        form.on('submit', function (event) {
            event.preventDefault()
            updateOptionForm(here.list.getSelectList())
            this.submit()
        })
    }

    //------------------------------------------//

    // Widgets des images

    AddImageWidget(place = 1) {
        const sender = new SendImage('',
            `image_${place}`,
            getSvgPhoto(),
            'class="add-img"'
        )
        const overlay = getOverlayElement(sender)
        sender.input.on('change', function () {
            if (sender.checkFile()) {
                sender.div.addClass('charged')
                sender.showMiniatureImage(overlay)
            }
            else if (sender.input.val() !== '')
            {
                createNotification('L\'image n\'est pas au bon format')
                sender.removeFile()
            }
        })
        $('#images_widget').append(sender.getElem())
        return sender
    }
}

//Check les options dans la liste reel du formulaire selon la liste js
function updateOptionForm(ids_to_check) {
    const checkboxs = $('#car_options').find('input')
    checkboxs.each(function () {
        const value = $(this).val()
        if (ids_to_check.includes(value))
            $(this).prop('checked', true)
        else
            $(this).prop('checked', false)
    })
}

//Recupere la liste des ids des options qui sont deja coché dans la list reel du formulaire
function getCheckedOptionForm() {
    const checkboxs = $('#car_options').find('input')
    let arr = []
    checkboxs.each(function () {
        if ($(this).is(':checked'))
            arr.push($(this).val())
    })
    return arr
}

function getOverlayElement(sender)
{
    const overlay = $('<div class="overlay">')
    overlay.on('click', function () {
        if (sender.div.attr('class').includes('charged')) {
            sender.div.removeClass('charged')
            sender.removeImage()
        }
    })
    return overlay
}

function setActualMiniature(id, senders) {
    request(
        '/worker/get_car_image_path',
        {'id': id},
        'json'
    )
        .then(response => {
            $.each(response, function (key, name) {
                const sender = senders[key]
                const url = '/images/occasions/' + name
                sender.showMiniatureImageByUrl(url,  getOverlayElement(sender))
                sender.div.addClass('charged')
                const data = new DataTransfer()
                data.items.add(new File([], name, { type: 'image/png' }))
                sender.input.prop('files', data.files)
            })
        })
}

//Recupere les parametres dans la barre d'adresse
function getUrlParams() {
    let params = {};
    const search = window.location.search.substring(1);
    let vars = search.split('&');
    for (let i = 0; i < vars.length; i++) {
        const pair = vars[i].split('=');
        const key = decodeURIComponent(pair[0]);
        params[key] = decodeURIComponent(pair[1]);
    }
    return params;
}