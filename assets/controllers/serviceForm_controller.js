import {Controller} from '@hotwired/stimulus';
import $ from 'jquery'
import {CollectionChoice} from "./lib/CollectionChoice";
import {InputImage} from "./lib/class/InputImage";
import {getSvgPhoto} from "./lib/func/SvgElement";
import {getUrlParams} from "./lib/func/Url";
import {request} from "./lib/func/Request";

export default class extends Controller {

    connect() {
        $(document).ready(() => {
            this.setCollectionChoice()
            this.setWidgetImage()
        })
    }

    setCollectionChoice() {
        const collection = new CollectionChoice($("#service_list"), $("#service_list_row"))
        collection.setAddButton('id="form_collection_choice_add_button" class="bi bi-plus-lg"', '')
        collection.setDeleteButton('<button type="button" class="bi bi-trash"></button>')
    }

    setWidgetImage() {
        const inputImage = new InputImage(
            'image',
            '<div class="overlay"></div>',
            getSvgPhoto(),
            'class="add-img"'
            )
        $('#images_widget').append(inputImage.getElem())

        const id = getUrlParams()['id']
        if (id)
        {
            request(
                '/admin/get_service_image_path',
                {'id': id},
                'text',
            )
                .then(response => {
                    inputImage.showMiniatureImageByUrl(
                        '/images/services/' + response,
                        response
                    )
                })
        }
    }
}