import _FETCH from "@/_utils/_FETCH";

const API_URL = `${process.env.NEXT_PUBLIC_API_URL}/media`;

export const getMainInfo = async () => {
    return await _FETCH.request({url: `${API_URL}/main-info`});
}