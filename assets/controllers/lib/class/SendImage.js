import $ from "jquery";
import {SendFile} from "./SendFile";

/**
 * Creer un widget pour envoyer une image par une requete ajax
 * @param illustrator - Element qui sera affiché a la place de l'element de base
 * @param div_attr - attributs de la div contenant les elements
 */
export class SendImage extends SendFile{
    constructor(url, file_name = 'image', illustrator = undefined, div_attr = '', max_size_mo = 2, accepted_type = ['image/png', 'image/jpeg', 'image/jpg']) {
        super(url, file_name, max_size_mo * 1048576, accepted_type);
        this.illustrator = illustrator
        this.div = $(`<div ${div_attr}>`)
    }

    getElem() {
        if (this.illustrator === undefined)
            this.div.append(this.input)
        else {
            this.input.attr('hidden', true)
            const input = this.input
            this.illustrator.on('click', function () {
                input.click()
            })
            this.div.append(this.illustrator)
            this.div.append(this.input)
        }
        return this.div
    }

    /**
     * Si l'illustrator est defini, le remplace par l'image input
     * @return {boolean|*|jQuery}
     */
    showMiniatureImage(overlay_element = undefined) {
        if (this.illustrator) {
            this.illustrator.attr('hidden', true)
            const reader = new FileReader();
            const here = this
            reader.onload = function (e) {
                const imageURL = e.target.result;
                const imageElement = $('<img>').attr('src', imageURL);
                here.mini_img = imageElement
                here.div.append(imageElement);
                if (overlay_element !== undefined)
                    here.div.append(overlay_element)
            };
            reader.readAsDataURL(this.file);
        }
    }

    /**
     * Si l'illustrator est defini, le remplace par l'image input
     * @return {boolean|*|jQuery}
     */
    showMiniatureImageByUrl(img_url, overlay_element = undefined) {
        if (this.illustrator) {
            this.illustrator.attr('hidden', true)
            const here = this
            const imageElement = $('<img>').attr('src', img_url)
            here.div.append(imageElement);
            if (overlay_element !== undefined)
                here.div.append(overlay_element)
        }
    }


    removeImage() {
        if (this.illustrator) {
            this.div.find('img').remove()
            this.div.find('.overlay').remove()
            this.illustrator.removeAttr('hidden')
        }
        super.removeFile()
    }
}