import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Label } from "@/components/ui/label";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import type { PaymentMethod } from "@/types/interface";
import { useId } from "react";
import { ErrorLabel } from "@/components/ui/error-label";

interface PaymentMethodSelectProps {
    paymentMethods: PaymentMethod[];
    onChange: (value: string) => void;
    country?: string;
    errorMessage?: string;
}

export default function PaymentMethodSelect({
    paymentMethods,
    onChange,
    country,
    errorMessage,
}: PaymentMethodSelectProps) {
    const id = useId();

    const paymentMethodOptions = country
        ? paymentMethods
              .filter((method) => method.country === country)
              .map((method) => ({
                  value: method.id.toString(),
                  label: method.name,
                  img_url: method.logo_url,
              }))
        : [];

    return (
        <div className="w-full space-y-2">
            <Label htmlFor={id}>Méthode de paiement</Label>
            <Select onValueChange={onChange} disabled={!country}>
                <SelectTrigger
                    id={id}
                    className="w-full px-3 [&>span]:flex [&>span]:items-center [&>span]:gap-2 [&>span_img]:shrink-0"
                >
                    <SelectValue placeholder="Sélectionner une méthode..." />
                </SelectTrigger>
                <SelectContent className="[&_*[role=option]]:pr-8 [&_*[role=option]]:pl-2 [&_*[role=option]>span]:right-2 [&_*[role=option]>span]:left-auto [&_*[role=option]>span]:flex [&_*[role=option]>span]:items-center [&_*[role=option]>span]:gap-2">
                    <SelectGroup>
                        <SelectLabel className="pl-2">
                            Méthode de paiement
                        </SelectLabel>
                        {paymentMethodOptions.map((item) => (
                            <SelectItem key={item.value} value={item.value}>
                                <Avatar className="size-6">
                                    <AvatarImage
                                        src={item.img_url}
                                        alt={item.label}
                                        className="rounded-full border border-white"
                                    />
                                    <AvatarFallback className="text-sm">
                                        {item.label.charAt(0)}
                                    </AvatarFallback>
                                </Avatar>
                                <span className="truncate">{item.label}</span>
                            </SelectItem>
                        ))}
                    </SelectGroup>
                </SelectContent>
            </Select>
            <ErrorLabel message={errorMessage} />
        </div>
    );
}
