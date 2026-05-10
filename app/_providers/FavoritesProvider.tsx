'use client';

import {createContext, useState, useEffect, ReactNode} from 'react';
import { favoritesAPI } from "@api";
import {ProviderFavorites} from "@_types/providers";

const FavoritesContext = createContext<ProviderFavorites | null>(null);

export function FavoritesProvider({ children }: { children: ReactNode }) {
    const [favorites, setFavorites] = useState<number[]>([]);
    const [isPending, setIsPending] = useState(false);

    useEffect(() => {
        favoritesAPI.get().then(response => {
            if (response.success) setFavorites(response.data);
        });
    }, []);

    const toggle = async (id?: number) => {
        if(isPending || !id) return;

        setIsPending(true);

        // Оптимистичное обновление (сразу меняем в UI)
        const isAdded = favorites.includes(id);

        setFavorites(prev => isAdded ? prev.filter(f => f !== id) : [...prev, id]);

        let response;

        if (isAdded) {
            response = await favoritesAPI.remove(id);

        } else {
            response = await favoritesAPI.add(id);
        }

        if (!response.success) {
            setFavorites(prev => isAdded ? [...prev, id] : prev.filter(f => f !== id));
        } else {
            setFavorites(response.data);
        }

        setIsPending(false);
    };

    return (
        <FavoritesContext.Provider value={{ favorites, isPending, toggle }}>
            {children}
        </FavoritesContext.Provider>
    );
}

export default FavoritesContext;