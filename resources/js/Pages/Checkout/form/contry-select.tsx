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
import type { Option } from "@/types/checkout";
import { useId } from "react";
import { ErrorLabel } from "@/components/ui/error-label";
import { PaymentMethod } from "@/types/interface";

interface ContrySelectProps {
    paymentMethods: PaymentMethod[];
    onChange: (value: string) => void;
    errorMessage?: string;
}

export default function ContrySelect({
    paymentMethods,
    onChange,
    errorMessage,
}: ContrySelectProps) {
    const countries: Option[] = paymentMethods
        .map((method) => ({
            value: method.country,
            label: method.country_label,
            img_url: method.country_flag_url,
        }))
        .filter((country, index, self) => {
            return self.findIndex((c) => c.value === country.value) === index;
        });

    const id = useId();

    return (
        <div className="w-full space-y-2">
            <Label htmlFor={id}>Pays</Label>
            <Select onValueChange={onChange}>
                <SelectTrigger
                    id={id}
                    className="w-full px-3 [&>span]:flex [&>span]:items-center [&>span]:gap-2 [&>span_svg]:shrink-0 [&>span_svg]:text-muted-foreground/80"
                >
                    <SelectValue placeholder="Selectionner un pays" />
                </SelectTrigger>
                <SelectContent className="max-h-100 [&_*[role=option]]:pr-8 [&_*[role=option]]:pl-2 [&_*[role=option]>span]:right-2 [&_*[role=option]>span]:left-auto [&_*[role=option]>span]:flex [&_*[role=option]>span]:items-center [&_*[role=option]>span]:gap-2 [&_*[role=option]>span>svg]:shrink-0 [&_*[role=option]>span>svg]:text-muted-foreground/80">
                    <SelectGroup>
                        <SelectLabel className="pl-2">Pays</SelectLabel>
                        {countries.map((country) => (
                            <SelectItem
                                key={country.value}
                                value={country.value}
                            >
                                <img
                                    src={country.img_url}
                                    alt={`${country.label} flag`}
                                    className="w-6 overflow-hidden rounded-[4px]"
                                />{" "}
                                <span className="truncate">
                                    {country.label}
                                </span>
                            </SelectItem>
                        ))}
                    </SelectGroup>
                </SelectContent>
            </Select>
            <ErrorLabel message={errorMessage} />
        </div>
    );
}
