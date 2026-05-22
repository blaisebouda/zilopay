"use client";

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
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { usePaymentMethod } from "@/stores/payment-method.store";

import { AmountField, PaymentMethodField } from "@/pages/shares/form-items";
import {
  transferSchema,
  type TransferFormData,
} from "@/validations/transfer.schema";
import { zodResolver } from "@hookform/resolvers/zod";
import { useForm } from "react-hook-form";

interface TransferFormProps {
  onSubmit: (data: TransferFormData) => void;
  isLoading?: boolean;
}

export function TransferForm({
  onSubmit,
  isLoading = false,
}: TransferFormProps) {
  const { data: paymentMethods } = usePaymentMethod();

  const form = useForm<TransferFormData>({
    resolver: zodResolver(transferSchema),
    defaultValues: {
      transfer_type: "system",
      amount: "",
      currency: "XOF",
      cardId: "",
      sourceMethod: "",
      targetMethod: "",
    },
  });

  const transferType = form.watch("transfer_type");
  const sourceMethod = parseInt(form.watch("sourceMethod") || "0");
  const isSystemTransfer = transferType === "system";
  const isInterTransfer = transferType === "inter_transaction";

  const handleSubmit = (data: TransferFormData) => {
    onSubmit(data);
  };

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(handleSubmit)} className="space-y-6">
        <FormField
          control={form.control}
          name="transfer_type"
          render={({ field }) => (
            <FormItem>
              <FormLabel>Type de transfert</FormLabel>
              <FormControl>
                <Tabs
                  value={field.value}
                  onValueChange={field.onChange}
                  className="w-full"
                  defaultValue="system"
                >
                  <TabsList className="grid w-full grid-cols-2">
                    <TabsTrigger value="system">Système</TabsTrigger>
                    <TabsTrigger value="inter_transaction">
                      Inter-méthode
                    </TabsTrigger>
                  </TabsList>
                  <TabsContent
                    value="system"
                    className="mt-2 text-sm text-muted-foreground"
                  >
                    Transférer vers un autre utilisateur avec son ID de carte
                  </TabsContent>
                  <TabsContent
                    value="inter_transaction"
                    className="mt-2 text-sm text-muted-foreground"
                  >
                    Transférer entre différentes méthodes de paiement
                  </TabsContent>
                </Tabs>
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />

        {/* Form Group pour le montant et la devise */}
        <div className="space-y-2">
          <AmountField form={form} />
        </div>

        {/* Champs spécifiques selon le type de transfert */}
        {isSystemTransfer && (
          <FormField
            control={form.control}
            name="cardId"
            render={({ field }) => (
              <FormItem>
                <FormLabel>ID de la carte du destinataire</FormLabel>
                <FormControl>
                  <Input
                    type="text"
                    placeholder="Entrez l'ID de la carte"
                    {...field}
                  />
                </FormControl>
                <FormMessage />
              </FormItem>
            )}
          />
        )}

        {isInterTransfer && (
          <div className="space-y-4">
            <PaymentMethodField
              form={form}
              name="sourceMethod"
              options={paymentMethods}
              label="Méthode source"
              placeholder="Sélectionnez la méthode source"
            />

            <PaymentMethodField
              form={form}
              name="targetMethod"
              options={paymentMethods.filter((m) => m.id !== sourceMethod)}
              label="Méthode de destination"
              placeholder="Sélectionnez la méthode de destination"
            />
          </div>
        )}

        <Button type="submit" className="w-full" disabled={isLoading}>
          {isLoading ? "Traitement..." : "Continuer"}
        </Button>
      </form>
    </Form>
  );
}
