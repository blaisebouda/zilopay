import {
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form"
import {
  InputGroup,
  InputGroupAddon,
  InputGroupInput,
  InputGroupText,
} from "@/components/ui/input-group"
import type { UseFormReturn } from "react-hook-form"

import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { usePaymentMethod } from "@/lib/stores/payment-method.store"
import { useId } from "react"
import { PaymentMethod } from "@/types/interface"

type AmountFieldProps = {
  form: UseFormReturn<any>
}

export function AmountField({ form }: AmountFieldProps) {
  return (
    <FormField
      control={form.control}
      name="amount"
      render={({ field }) => (
        <FormItem>
          <FormLabel>Montant</FormLabel>
          <InputGroup>
            <InputGroupAddon>
              <InputGroupText>XOF</InputGroupText>
            </InputGroupAddon>
            <InputGroupInput {...field} placeholder="10 000" />
            <InputGroupAddon align="inline-end">
              <InputGroupText>F CFA</InputGroupText>
            </InputGroupAddon>
          </InputGroup>
          <FormMessage />
        </FormItem>
      )}
    />
  )
}

interface PaymentMethodFieldProps {
  form: UseFormReturn<any>
  name?: string
  options?: PaymentMethod[]
  label?: string
  placeholder?: string
}

export function PaymentMethodField({
  form,
  name = "payment_method",
  options,
  label,
  placeholder,
}: PaymentMethodFieldProps) {
  const { data } = usePaymentMethod()
  const id = useId()

  const paymentMethods = options || data || []

  return (
    <FormField
      control={form.control}
      name={name}
      render={({ field }) => (
        <FormItem>
          <FormLabel>{label || "Moyen de paiement"}</FormLabel>

          <Select value={field.value} onValueChange={field.onChange}>
            <SelectTrigger
              id={id}
              className="w-full pl-2 [&>span]:flex [&>span]:items-center [&>span]:gap-2 [&>span_img]:shrink-0"
            >
              <SelectValue
                placeholder={placeholder || "Selectionnez un moyen de paiement"}
              />
            </SelectTrigger>
            <SelectContent className="[&_*[role=option]]:pr-8 [&_*[role=option]]:pl-2 [&_*[role=option]>span]:right-2 [&_*[role=option]>span]:left-auto [&_*[role=option]>span]:flex [&_*[role=option]>span]:items-center [&_*[role=option]>span]:gap-2">
              <SelectGroup>
                <SelectLabel className="pl-2">
                  Selectionnez un moyen de paiement
                </SelectLabel>
                {paymentMethods.map((item) => (
                  <SelectItem key={item.id} value={item.id.toString()}>
                    <Avatar className="size-6">
                      <AvatarImage
                        src={item.logo_url}
                        alt={item.name}
                        className="rounded-full"
                      />
                      <AvatarFallback className="text-xs">
                        {item.name.charAt(0)}
                      </AvatarFallback>
                    </Avatar>
                    <span className="truncate">{item.name}</span>
                  </SelectItem>
                ))}
              </SelectGroup>
            </SelectContent>
          </Select>
        </FormItem>
      )}
    />
  )
}
