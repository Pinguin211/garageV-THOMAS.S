import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {SelectorList} from "./lib/class/SelectorList";
import {request} from "./lib/func/Request";
import {getSvgPhoto} from "./lib/func/SvgElement";
import {InputImage} from "./lib/class/InputImage";
import {getUrlParams} from "./lib/func/Url";
import {createNotification} from "./lib/func/Notification";


export default class extends Controller {

    //Liste des options
    list = undefined


    //WidgetImages
    NB_WIDGETS = 5
    widgets = {}

    connect() {

        $(document).ready(() => {
            this.getList() // recupere la liste des options
            this.setFormAction() // Ajoute les event pour lier la liste des options au formulaire
            this.setImageWidget()
        })

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

    setImageWidget() {
        for (let i = 1; i <= this.NB_WIDGETS; i++) {
            this.AddImageWidget(i.toString())
        }

        request(
            '/worker/get_car_image_path',
            {'id': getUrlParams()['id']},
            'json'
        )
            .then(response => {
                $.each(response, (key, name) => {
                    const widget = this.widgets[key]
                    const url = '/images/occasions/' + name
                    widget.showMiniatureImageByUrl(url, name)
                })
            })

    }

    AddImageWidget(place) {
        const inputImage = new InputImage(
            `image_${place}`,
            '<div class="overlay"></div>',
            getSvgPhoto(),
            'class="add-img"'
        )

        inputImage.alert_func = () => {
            createNotification('Le format de l\'image est invalide' )
        }

        $('#images_widget').append(inputImage.getElem())
        this.widgets[place] = inputImage
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