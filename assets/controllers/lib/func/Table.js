/**
 * Transforme les keys du tableau en string
 * @param table - Tableau associatif
 */
export function setKeysToString(table) {
    let new_table = {}
    Object.entries(table).forEach(([key, value])=> {
        new_table[key.toString()] = value
    })
    return new_table
}

/**
 * Renvoie la clé de la valeurs donné ou -1 si elle n'existe pas
 * @param dict - Le dictionnaire
 * @param value - La valeur de la clé recherché
 * @return {string|number}
 */
export function indexOfValue(dict, value) {
    for (let key in dict) {
        if (dict[key] === value)
            return key;
    }
    return -1;
}
