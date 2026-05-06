// import createToast from "@/lib/createToast.ts";

const cleanRequest = async ({url, options = {method: "GET"}, toasts = false}: {
    url: string,
    options?: RequestInit,
    toasts?: boolean
}) => {
    // if(toasts) createToast({type: "onload", link: url});

    return await fetch(url, options)
        .catch(() => {
            // createToast({type: "onerror", link: url, text: 'Ошибка запроса'});
            return false;
        });
}

const fileRequest = async ({url, options = {method: "GET"}, toasts = false}: {
    url: string,
    options?: RequestInit,
    toasts?: boolean

}) => {
    // if(toasts) createToast({type: "onload", link: url});

    const RESPONSE = await fetch(url, options);
    const blob = await RESPONSE.blob();

    if(!(blob instanceof Blob)) {
        // createToast({type: "onerror", link: url, text: 'Ошибка запроса'});
        return false;
    }

    const disposition = RESPONSE.headers.get('Content-Disposition');
    let filename = 'file.txt';

    if (disposition && disposition.includes('filename=')) {
        filename = disposition
            .split('filename=')[1]
            .split(';')[0]
            .replace(/['"]/g, '');

        filename = decodeURIComponent(filename);
    }

    return {blob, filename};
}

const request = async ({url, options = {method: "GET", cache: "no-cache"}, toasts = false}: {
    url: string,
    options?: RequestInit,
    toasts?: boolean
}) => {
    // if(toasts) createToast({type: "onload", link: url});

    return await fetch(url, options)
        .then(response => response.json())
        .then(data => {
            if(!data || !data.success) {
                throw new Error(data.message ?? `Ошибка в получении данных с сервера.`);
            }

            // if(toasts) createToast({type: "onsuccess", link: url, text: data.message ?? ''});

            return data;
        })
        .catch(error=> {
            const MESSAGE = error instanceof Error && error.message !== 'Failed to fetch' ? error.message : "Ошибка соединения с сервером!";

            // createToast({type: "onerror", link: url, text: MESSAGE});

            return {success: false, error: MESSAGE};
        });
}

export default {request, cleanRequest, fileRequest};