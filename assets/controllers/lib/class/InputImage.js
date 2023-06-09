import $ from "jquery";
import {InputFile} from "./InputFile";


export class InputImage extends InputFile{
    constructor(file_name = 'image', delete_overlay_selector = '<div>x</div>', illustrator = undefined, div_attr = '', max_size_mo = 2, accepted_type = ['image/png', 'image/jpeg', 'image/jpg']) {
        super(file_name, max_size_mo * 1048576, accepted_type);
        this.illustrator = illustrator
        this.delete_overlay_selector = delete_overlay_selector
        this.div = $(`<div ${div_attr}>`)
        /**
         * Fonction qui sera realiser si l'image ajouté ne convient pas
         * @type {function}
         */
        this.alert_func = undefined
    }

    getElem() {
        if (this.illustrator === undefined)
            this.div.append(this.input)
        else {
            const input = this.input
            input.attr('hidden', true)
            input.on('change', () => {
                if (this.checkFile())
                    this.showMiniatureImage()
                else {
                    if (this.alert_func)
                        this.alert_func()
                    this.removeImage()
                }
            })
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
    showMiniatureImage() {
        if (this.illustrator) {
            this.illustrator.attr('hidden', true)
            const reader = new FileReader();
            const here = this
            reader.onload = function (e) {
                const imageURL = e.target.result;
                const imageElement = $('<img>').attr('src', imageURL);
                here.mini_img = imageElement
                here.div.append(imageElement);
                here.appendOverlay()
            };
            reader.readAsDataURL(this.file);
        }
    }

    /**
     * Si l'illustrator est defini, le remplace par l'image input
     * @return {boolean|*|jQuery}
     */
    showMiniatureImageByUrl(img_url, image_name, image_type = 'image/png') {
        if (this.illustrator) {
            this.illustrator.attr('hidden', true)
            const imageElement = $('<img>').attr('src', img_url)
            this.div.append(imageElement);
            this.appendOverlay()

            const data = new DataTransfer()
            data.items.add(new File([], image_name, { type: image_type }))
            this.input.prop('files', data.files)
        }
    }


    removeImage() {
        if (this.illustrator) {
            this.div.find('img').remove()
            this.illustrator.removeAttr('hidden')
        }
        super.removeFile()
    }

    appendOverlay() {
        const overlay = $(this.delete_overlay_selector)
        overlay.on('click', () => {
            this.removeImage()
            overlay.remove()
        })
        this.div.append(overlay)
    }
}