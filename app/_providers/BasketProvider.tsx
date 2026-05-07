'use client';

import {createContext, useState, useEffect, ReactNode} from 'react';
import { basketAPI } from "@api";
import {CatalogProductBasket} from "@_types/catalog";
import {ProviderBasket} from "@_types/providers";

const BasketContext = createContext<ProviderBasket | null>(null);

export function BasketProvider({ children }: { children: ReactNode }) {
    const [basket, setBasket] = useState<CatalogProductBasket[]>([]);
    const [isPending, setIsPending] = useState(false);

    useEffect(() => {
        basketAPI.get().then(response => {
            if (response.success) setBasket(response.data);
        });
    }, []);

    const executeAction = async (
        optimisticUpdate: (() => void) | null,
        apiCall: () => Promise<{success: boolean; data?: CatalogProductBasket[]}>,
    ) => {
        if(isPending) return;

        setIsPending(true);

        const fallback = [...basket];

        optimisticUpdate && optimisticUpdate();

        const response = await apiCall();

        if(!response.success) {
            setBasket(fallback);

        } else if(response.data) {
            setBasket(response.data);
        }

        setIsPending(false);
    }

    const add = (
        productId: number,
        productSize: string,
        toastSuccess: boolean = true,
        count?: number,
    ) => {
        void executeAction(
            null,
            () => basketAPI.add({id: productId, size: productSize, count}, toastSuccess)
        );
    }

    const setCount = (productId: number, productSize: string, count: number) => {
        void executeAction(
            () => setBasket(prev => prev
                .map(p => (p.id === productId && p.size === productSize)
                    ? {...p, size: productSize, count: count }
                    : p
            )),
            () => basketAPI.setCount({id: productId, size: productSize, count})
        );
    }

    const decrement = (productId: number, productSize: string) => {
        void executeAction(
            () => setBasket(prev => prev
                .map(p => (p.id === productId && p.size === productSize)
                    ? { ...p, value: p.count - 1 }
                    : p
                )
                .filter(p => p.count > 0)
            ),
            () => basketAPI.decrement({id: productId, size: productSize})
        );
    }

    const remove = (productId: number, productSize: string) => {
        void executeAction(
            () => setBasket(prev =>
                prev.filter(p => (p.id !== productId && p.size !== productSize))
            ),
            () => basketAPI.remove({id: productId, size: productSize})
        );
    }

    const clear = () => {
        void executeAction(
            () => setBasket([]),
            () => basketAPI.clear()
        );
    }

    const toggle = (productId: number, productSize: string) => {
        if(isPending) return;

        if(basket.find(p => (p.id === productId && p.size === productSize))) {
            void remove(productId, productSize);
        } else {
            void add(productId, productSize);
        }
    }

    return (
        <BasketContext.Provider value={{basket, isPending, add, setCount, decrement, remove, clear, toggle}}>
            {children}
        </BasketContext.Provider>
    );
}

export default BasketContext;