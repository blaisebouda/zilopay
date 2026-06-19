import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form"
import { Input } from "@/components/ui/input"
import { PhoneInput } from "@/components/ui/phone-input"
import type { UseFormReturn } from "react-hook-form"
import type { MerchantFormData } from "@/lib/validations/merchant.schema"

interface MerchantCompanyFormProps {
  form: UseFormReturn<MerchantFormData>
}

export default function MerchantCompanyForm({
  form,
}: MerchantCompanyFormProps) {
  return (
    <div className="bg-card border p-4 sm:p-6 rounded-lg space-y-6">
      <FormField
        control={form.control}
        name="business_name"
        render={({ field }) => (
          <FormItem>
            <FormLabel>Nom de l'entreprise</FormLabel>
            <FormControl>
              <Input
                placeholder="Entrez le nom de votre entreprise"
                {...field}
              />
            </FormControl>
            <FormMessage />
          </FormItem>
        )}
      />
      <FormField
        control={form.control}
        name="business_email"
        render={({ field }) => (
          <FormItem>
            <FormLabel>Email de l'entreprise</FormLabel>
            <FormControl>
              <Input
                type="email"
                placeholder="Entrez l'email de votre entreprise"
                {...field}
              />
            </FormControl>
            <FormMessage />
          </FormItem>
        )}
      />
      <FormField
        control={form.control}
        name="phone_number"
        render={({ field }) => (
          <FormItem className="flex flex-col items-start">
            <FormLabel className="text-left">Numéro de téléphone</FormLabel>
            <FormControl className="w-full">
              <PhoneInput
                onCountryChange={(meta) => {
                  form.setValue("country", meta?.country || "")
                }}
                placeholder="Entrez un numéro de téléphone"
                {...field}
              />
            </FormControl>
            <FormMessage />
          </FormItem>
        )}
      />
    </div>
  )
}
