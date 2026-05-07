import _FETCH from "@/_utils/_FETCH";
import {Form, Subscribe} from "@_types/callbacks";

const API_URL = `${process.env.NEXT_PUBLIC_API_URL}/callback`;

export const subscribe = async (data: Subscribe) => {
    return await _FETCH.progressTrackingRequest({url: `${API_URL}/subscribe`, options: {method: 'POST', body: JSON.stringify(data)}});
}

export const form = async (data: Form) => {
    return await _FETCH.progressTrackingRequest({url: `${API_URL}/form`, options: {method: 'POST', body: JSON.stringify(data)}});
}
