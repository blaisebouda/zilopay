import { removeSpaces } from "@/lib/utils";
import type { InitDepositFormData } from "@/types/checkout";
import { PaymentMethod } from "@/types/interface";
import { useState } from "react";

type FormFieldSchema = {
    message: string;
    validate: (value: string | null, country?: string) => boolean;
};

function validatePhone(country: string, value: string | null): boolean {
    if (value === null) return false;
    const phoneLengths = {
        BF: [8],
        CI: [8, 10],
        SN: [8],
    };
    const lengths = phoneLengths[country as keyof typeof phoneLengths];
    if (!lengths) return false;

    const phoneNumber = removeSpaces(value);

    return lengths.includes(phoneNumber.length);
}

export function useInitDepositFormValidation(paymentMethods: PaymentMethod[]) {
    const [errors, setErrors] = useState<{
        [K in keyof InitDepositFormData]?: string;
    }>({});

    //console.log(paymentMethods)

    const schema: Record<keyof InitDepositFormData, FormFieldSchema> = {
        country: {
            message: "Selectionner un pays",
            validate: (value) =>
                value !== null &&
                paymentMethods.some((pm) => pm.country === value),
        },
        paymentMethod: {
            message: "Selectionner un moyen de paiement",
            validate: (value) =>
                value !== null &&
                paymentMethods.some((pm) => pm.id === Number(value)),
        },
        phone: {
            message: "Numéro de téléphone invalide",
            validate: (value, country) => validatePhone(country || "", value),
        },
    };

    const validateForm = (form: InitDepositFormData): boolean => {
        const newErrors: { [K in keyof InitDepositFormData]?: string } = {};
        for (const [key, field] of Object.entries(schema)) {
            const value = form[key as keyof InitDepositFormData];
            if (!field.validate(value, form.country)) {
                newErrors[key as keyof InitDepositFormData] = field.message;
            }
        }
        setErrors(newErrors);

        return Object.keys(newErrors).length === 0;
    };

    return {
        errors,
        validateForm,
    };
}
