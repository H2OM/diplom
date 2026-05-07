import _FETCH from "@/_utils/_FETCH";
import {CatalogFilters} from "@_types/catalog";
import formatQueryParams from "@utils/formatQueryParams";

const API_URL = `${process.env.NEXT_PUBLIC_API_URL}/catalog`;

export const get = async (filters: CatalogFilters) => {
    const params = formatQueryParams(filters);

    return await _FETCH.request({url: `${API_URL}/get${params ? `?${params}` : ''}`});
}