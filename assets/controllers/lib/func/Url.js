//Recupere les parametres dans la barre d'adresse
export function getUrlParams() {
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