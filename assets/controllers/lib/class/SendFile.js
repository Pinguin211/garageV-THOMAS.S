import $ from "jquery";
import {file_request} from "../Request";

/**
 * Creer un widget pour envoyer un fichiers par une requete ajax
 */
export class SendFile {

    /**
     * @param url - Url de la requete
     * @param file_name - nom du fichier dans la requete
     * @param max_size - taille maximum du fichier
     * @param accepted_type - type de fichier accepté (si vide accepte tout type) Exemple : ['image/png', 'image/jpeg']
     */
    constructor(url, file_name, max_size = 2000000, accepted_type = []) {
        this.url = url
        this.name = file_name
        this.input = $(`<input type="file" name="${file_name}">`)
        this.file = false
        const here = this
        this.input.on('change', function (e) {
            here.file = e.target.files[0]
        })
        this.max_size = max_size
        this.accepted_type = accepted_type
    }

    /**
     * Retourne l'élément
     */
    getElem() {
        return this.input
    }

    /**
     *
     * Envoie le fichier et retourne la promesse d'envoie
     *
     * @param to_send - Element a envoyé en plus - Exemple: {'author': 'me', ...}
     * @param datatype - Type de retour de la reponse
     * @param csrf_token - Id de la node ou recuperer le token
     * @return {Promise<unknown>|Promise<never>}
     */
    sendFile(to_send = {}, datatype = 'text', csrf_token = 'csrf_token') {
        const formData = new FormData();
        $.each(to_send, (key, value) => {
            formData.append(key, value)
        })
        formData.append(this.name, this.input.prop('files')[0])
        return file_request(this.url, formData, datatype, csrf_token)
    }

    /**
     * Check la validité du fichier selon les parametre rentré
     * @return {boolean|*}
     */
    checkFile() {
        return this.checkFileSize() && this.checkFileType()
    }

    /**
     * Verifie si la taille du fichier est inferieur ou egale a max_size
     * @return {boolean|*|boolean}
     */
    checkFileSize() {
        return this.file && this.file.size <= this.max_size
    }

    /**
     * Verifie que le type de fichier est bien dans la liste correspondante
     * @return {boolean|*|boolean}
     */
    checkFileType() {
        return this.file && this.accepted_type.includes(this.file.type)
    }

    /**
     * Retire le fichier de l'input
     */
    removeFile() {
        this.input.val('')
        this.file = false
    }
}