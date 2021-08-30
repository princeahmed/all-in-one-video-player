export function updateParam(param) {
    const key = Object.keys(param)[0];
    const value = param[key];

    function addOrReplaceParam(param, value) {
        const url = window.location.href;

        const stringToAdd = `${param}=` + value;

        const has_param = url.match(/\?./);

        if (window.location.search === "") {
            return `${url}${has_param ? '&' : '?'}${stringToAdd}`;
        }

        if (window.location.search.indexOf(`${param}=`) === -1) {
            return `${url}${has_param ? '&' : '?'}${stringToAdd}`;
        }

        const searchParams = window.location.search.substring(1).split("&");

        for (let i = 0; i < searchParams.length; i++) {
            if (searchParams[i].indexOf(`${param}=`) > -1) {
                searchParams[i] = `${param}=` + value;
                break;
            }
        }

        return url.split("?")[0] + "?" + searchParams.join("&");
    }

    updateURL(addOrReplaceParam(key, value));

}

export function updateURL(url) {

    try {
        history.pushState('', '', url);
    } catch (e) {
        console.log(e);
    }
}