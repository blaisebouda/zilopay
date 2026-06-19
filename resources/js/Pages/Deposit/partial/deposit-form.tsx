"use client"

import { Button } from "@/components/ui/button"
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form"
import { PhoneInput } from "@/components/ui/phone-input"
import { AmountField, PaymentMethodField } from "@/components/shares/form-items"
import {
  depositSchema,
  type DepositFormData,
} from "@/lib/validations/deposit.schema"
import { zodResolver } from "@hookform/resolvers/zod"
import { useForm } from "react-hook-form"

interface DepositFormProps {
  onSubmit: (data: DepositFormData) => void
  isLoading?: boolean
}

export function DepositForm({ onSubmit, isLoading = false }: DepositFormProps) {
  const form = useForm<DepositFormData>({
    resolver: zodResolver(depositSchema),
    defaultValues: {
      payment_method: "",
      amount: "",
      currency: "XOF",
      phone_number: "",
      country: "BF",
    },
  })

  const handleSubmit = (data: DepositFormData) => {
    onSubmit(data)
  }

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(handleSubmit)} className="space-y-6">
        <PaymentMethodField form={form} />

        {/* Form Group pour le montant et la devise */}
        <div className="space-y-2">
          <AmountField form={form} />
        </div>

        {/* Form Group pour le pays et le numéro de téléphone */}

        <FormField
          control={form.control}
          name="phone_number"
          render={({ field }) => (
            <FormItem>
              <FormLabel>Numéro de téléphone</FormLabel>
              <FormControl className="w-full">
                <PhoneInput
                  placeholder="Entrez votre numéro"
                  {...field}
                  value={field.value || ""}
                />
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />

        <Button type="submit" className="w-full" disabled={isLoading}>
          {isLoading ? "Traitement..." : "Continuer"}
        </Button>
      </form>
    </Form>
  )
}
