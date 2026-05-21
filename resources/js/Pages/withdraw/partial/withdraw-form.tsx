import { zodResolver } from "@hookform/resolvers/zod";
import { useForm } from "react-hook-form";

import { FormDialog } from "@/components/form/form-dialog";
import { Button } from "@/components/ui/button";
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { countries, currencies } from "@/constants";
import {
  withdrawSchema,
  type WithdrawFormData,
} from "@/validations/withdraw.schema";
import { Plus } from "lucide-react";

const WithdrawForm = () => {
  const form = useForm<WithdrawFormData>({
    resolver: zodResolver(withdrawSchema),
    defaultValues: {
      paymentMethod: "",
      amount: "",
      currency: "XOF",
      phoneNumber: "",
      country: "BF",
    },
  });

  const handleSubmit = (data: WithdrawFormData) => {
    console.log("Withdraw form submitted:", data);
    // TODO: Implementer la logique de soumission
  };
  return (
    <FormDialog
      title="Effectuer un retrait"
      description="Remplissez le formulaire ci-dessous pour effectuer un retrait"
      onSubmit={form.handleSubmit(handleSubmit)}
      trigger={
        <Button>
          <Plus className="h-4 w-4" /> Nouveau retrait
        </Button>
      }
      submitLabel="Retirer"
    >
      <Form {...form}>
        <FormField
          control={form.control}
          name="paymentMethod"
          render={({ field }) => (
            <FormItem>
              <FormLabel>Moyen de paiement</FormLabel>
              <Select onValueChange={field.onChange} defaultValue={field.value}>
                <FormControl>
                  <SelectTrigger className="w-full">
                    <SelectValue placeholder="Sélectionnez un moyen de paiement" />
                  </SelectTrigger>
                </FormControl>
                <SelectContent>
                  {[].map((method) => (
                    <SelectItem key={method.value} value={method.value}>
                      {method.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
              <FormMessage />
            </FormItem>
          )}
        />

        {/* Form Group pour le montant et la devise */}
        <div className="space-y-2 pt-4">
          <FormLabel className="text-base font-medium">Montant</FormLabel>
          <div className="grid grid-cols-[110px_1fr] gap-4">
            <FormField
              control={form.control}
              name="currency"
              render={({ field }) => (
                <FormItem>
                  <Select
                    onValueChange={field.onChange}
                    defaultValue={field.value}
                  >
                    <FormControl>
                      <SelectTrigger className="w-full">
                        <SelectValue placeholder="Devise" />
                      </SelectTrigger>
                    </FormControl>
                    <SelectContent>
                      {currencies.map((currency) => (
                        <SelectItem key={currency.value} value={currency.value}>
                          {currency.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  <FormMessage />
                </FormItem>
              )}
            />

            <FormField
              control={form.control}
              name="amount"
              render={({ field }) => (
                <FormItem>
                  <FormControl>
                    <Input
                      className="w-full"
                      type="text"
                      placeholder="Entrez le montant"
                      {...field}
                    />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
          </div>
        </div>

        {/* Form Group pour le pays et le numéro de téléphone */}
        <div className="space-y-2 pt-4">
          <FormLabel className="text-base font-medium">
            Numéro de téléphone
          </FormLabel>
          <div className="grid grid-cols-[106px_1fr] gap-4">
            <FormField
              control={form.control}
              name="country"
              render={({ field }) => (
                <FormItem>
                  <Select
                    onValueChange={field.onChange}
                    defaultValue={field.value}
                  >
                    <FormControl>
                      <SelectTrigger className="w-full text-ellipsis overflow-hidden">
                        <SelectValue placeholder="Pays" />
                      </SelectTrigger>
                    </FormControl>
                    <SelectContent>
                      {countries.map((country) => (
                        <SelectItem key={country.value} value={country.value}>
                          {country.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  <FormMessage />
                </FormItem>
              )}
            />
            <FormField
              control={form.control}
              name="phoneNumber"
              render={({ field }) => (
                <FormItem>
                  <FormControl>
                    <Input
                      type="tel"
                      placeholder="Numéro de téléphone"
                      {...field}
                    />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
          </div>
        </div>
      </Form>
    </FormDialog>
  );
};

export default WithdrawForm;
