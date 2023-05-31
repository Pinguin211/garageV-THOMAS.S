import $ from "jquery";

/**
 * Retourne une node jquery ou le contenu est affiché orginal, les balise html ne sont pas traduite
 * @param selector - Selecteur de la node
 * @param content - Contenu de la node
 * @param attr - Attribut de la node - Exemple : {'class': 'title', 'id': 'headtitle'}
 */
export function secureJqueryNodePrint(selector, content, attr = {}) {
    const node = $(selector).text(content)
    Object.entries(attr).forEach(([key, val]) => {
        node.attr(key, val)
    })
    return node
}