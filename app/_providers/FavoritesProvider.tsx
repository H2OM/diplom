'use client';

import {createContext, useState, useEffect, ReactNode} from 'react';
import { favoritesAPI } from "@api";

const FavoritesContext = createContext<FavoritesContext | null>(null);

export function FavoritesProvider({ children }: { children: ReactNode }) {
    const [favorites, setFavorites] = useState<number[] | null>(null);

    useEffect(() => {
        favoritesAPI.get().then(response => {
            if (response.success) setFavorites(response.data);
        });
    }, []);

    const toggleFavorite = async (id: number) => {
        if(!favorites) return;

        // Оптимистичное обновление (сразу меняем в UI)
        const isAdded = favorites.includes(id);

        setFavorites(prev => isAdded ? prev!.filter(f => f !== id) : [...prev!, id]);

        let response;

        if (isAdded) {
            response = await favoritesAPI.remove(id);

        } else {
            response = await favoritesAPI.add(id);
        }

        if (!response.success) {
            setFavorites(prev => isAdded ? [...prev!, id] : prev!.filter(f => f !== id));
        }
    };

    return (
        <FavoritesContext.Provider value={{ favorites, toggleFavorite }}>
            {children}
        </FavoritesContext.Provider>
    );
}

export default FavoritesContext;