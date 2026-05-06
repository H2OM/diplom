'use client';

import {createContext, useState, useEffect, ReactNode} from 'react';
import { favoritesAPI } from "@api";

const BasketContext = createContext<FavoritesContext | null>(null);

export function BasketProvider({ children }: { children: ReactNode }) {

    useEffect(() => {
        favoritesAPI.get().then(response => {
            if (response.success) setFavorites(response.data);
        });
    }, []);


    return (
        <BasketContext.Provider value={}>
            {children}
        </BasketContext.Provider>
    );
}

export default BasketContext;