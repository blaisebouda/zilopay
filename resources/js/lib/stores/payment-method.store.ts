import { useGet } from "@/hooks/use-get";
import { PaymentMethod } from "@/types/interface";
import { useEffect } from "react";
import { create } from "zustand";

interface PaymentMethodState {
    data: PaymentMethod[];
    setData: (data: PaymentMethod[]) => void;
    clear: () => void;
}

export const usePaymentMethodStore = create<PaymentMethodState>((set) => ({
    data: [],
    setData: (data) => set({ data }),
    clear: () => set({ data: [] }),
}));

export function usePaymentMethod() {
    const { data: storeData, setData } = usePaymentMethodStore();

    const { loading, error, refetch } = useGet<PaymentMethod[]>(
        "/payment-methods",
        false,
    );

    /**
     * Fetch seulement si le store est vide
     */
    useEffect(() => {
        if (!storeData.length) {
            refetch().then((res) => {
                if (res) setData(res);
            });
        }
    }, [refetch, setData, storeData.length]);

    return {
        data: storeData,
        loading: !storeData.length && loading,
        error,
        options:
            storeData.map((method) => ({
                value: method.id,
                label: method.name,
            })) || [],
    };
}
