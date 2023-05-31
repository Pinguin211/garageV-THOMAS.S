/**
 * Verifie si la veleur donné est bien une chaine de charactere au format JSON
 * @param value - valeur a tester
 * @return {boolean}
 */
export function isJSONString(value) {
    try {
        JSON.parse(value);
        return true;
    } catch (error) {
        return false;
    }
}