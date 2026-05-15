'use client';

import {createContext, useState, useEffect, ReactNode} from 'react';
import {basketAPI} from "@api";
import {ProductBasket} from "@_types/product";
import {ProviderBasket} from "@_types/providers";

const BasketContext = createContext<ProviderBasket | null>(null);

export function BasketProvider({children}: { children: ReactNode }) {
    const [basket, setBasket] = useState<ProductBasket[]>([]);
    const [isPending, setIsPending] = useState(true);

    useEffect(() => void get(), []);

    const get = async () => {
        setIsPending(true);

        const response = await basketAPI.get();

        setIsPending(false);

        if (response.success) setBasket(response.data);
    };

    const executeAction = async (
        optimisticUpdate: (() => void) | null,
        apiCall: () => Promise<{ success: boolean; data?: ProductBasket[] }>,
    ) => {
        if (isPending) return;

        setIsPending(true);

        const fallback = [...basket];

        optimisticUpdate && optimisticUpdate();

        const response = await apiCall();

        if (!response.success) {
            setBasket(fallback);

        } else if (response.data) {
            setBasket(response.data);
        }

        setIsPending(false);
    }

    const add = (
        productId: number,
        count?: number,
        toastSuccess: boolean = true,
    ) => {
        void executeAction(
            null,
            () => basketAPI.add({id: productId, count}, toastSuccess)
        );
    }

    const setCount = (productId: number, count: number) => {
        void executeAction(
            () => setBasket(prev => prev
                .map(p => (p.id === productId)
                    ? {...p, count: count}
                    : p
                )),
            () => basketAPI.setCount({id: productId, count})
        );
    }

    const decrement = (productId: number) => {
        void executeAction(
            () => setBasket(prev => prev
                .map(p => (p.id === productId)
                    ? {...p, value: p.count - 1}
                    : p
                )
                .filter(p => p.count > 0)
            ),
            () => basketAPI.decrement({id: productId})
        );
    }

    const remove = (productId: number) => {
        void executeAction(
            () => setBasket(prev =>
                prev.filter(p => p.id !== productId)
            ),
            () => basketAPI.remove({id: productId})
        );
    }

    const clear = () => {
        void executeAction(
            () => setBasket([]),
            () => basketAPI.clear()
        );
    }

    const toggle = (productId: number) => {
        if (isPending) return;

        if (basket.find(p => (p.id === productId))) {
            void remove(productId);
        } else {
            void add(productId);
        }
    }

    return (
        <BasketContext.Provider value={{basket, isPending, add, setCount, decrement, remove, clear, toggle}}>
            {children}
        </BasketContext.Provider>
    );
}

export default BasketContext;