import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form"
import { Input } from "@/components/ui/input"
import { PhoneInput } from "@/components/ui/phone-input"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import type { ResetPasswordFormData } from "@/lib/validations/reset-passowrd.shema"
import { Mail, Phone } from "lucide-react"
import type { UseFormReturn } from "react-hook-form"
interface RegisterInfosFormProps {
  form: UseFormReturn<ResetPasswordFormData>
}

export function ResetPasswordForm({ form }: RegisterInfosFormProps) {
  const reset = () => {
    form.setValue("email", null)
    form.setValue("phone_number", null)
  }
  return (
    <div className="mx-auto bg-card border p-4 sm:p-6 rounded-lg space-y-6">
      <Tabs onValueChange={reset} defaultValue="email" className="w-full">
        <TabsList className="grid w-full grid-cols-2">
          <TabsTrigger value="email" className="gap-2">
            <Mail className="h-4 w-4" />
            Email
          </TabsTrigger>
          <TabsTrigger value="phone" className="gap-2">
            <Phone className="h-4 w-4" />
            Téléphone
          </TabsTrigger>
        </TabsList>
        <TabsContent value="email" className="mt-4">
          <FormField
            control={form.control}
            name="email"
            render={({ field }) => (
              <FormItem>
                <FormLabel>
                  <Mail className="h-4 w-4" />
                  Adresse email
                </FormLabel>
                <FormControl>
                  <Input
                    required={form.getValues("phone_number") == null}
                    type="email"
                    placeholder="exemple@email.com"
                    {...field}
                    value={field.value || ""}
                  />
                </FormControl>
                <FormMessage />
              </FormItem>
            )}
          />
        </TabsContent>
        <TabsContent value="phone" className="mt-4">
          <FormField
            control={form.control}
            name="phone_number"
            render={({ field }) => (
              <FormItem className="flex flex-col items-start">
                <FormLabel className="text-left">
                  <Phone className="h-4 w-4" />
                  Numéro de téléphone
                </FormLabel>
                <FormControl className="w-full">
                  <PhoneInput
                    required={form.getValues("email") == null}
                    placeholder="Entrez votre numéro"
                    {...field}
                    value={field.value || ""}
                  />
                </FormControl>
                <FormMessage />
              </FormItem>
            )}
          />
        </TabsContent>
      </Tabs>
    </div>
  )
}
