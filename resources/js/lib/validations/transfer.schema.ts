import { z } from "zod";

export const transferSchema = z
  .object({
    transfer_type: z
      .string()
      .min(1, "Veuillez sélectionner un type de transfert"),
    amount: z
      .string()
      .min(1, "Le montant est requis")
      .regex(/^\d+$/, "Le montant doit être un nombre valide")
      .refine(
        (val: string) => parseInt(val) >= 100,
        "Le montant minimum est de 100 FCFA",
      )
      .refine(
        (val: string) => parseInt(val) <= 1000000,
        "Le montant maximum est de 1 000 000 FCFA",
      ),
    currency: z.string().min(1, "Veuillez sélectionner une devise"),
    cardId: z.string().optional(),
    sourceMethod: z.string().optional(),
    targetMethod: z.string().optional(),
  })
  .refine(
    (data) => {
      if (data.transfer_type === "system") {
        return data.cardId && data.cardId.length > 0;
      }
      if (data.transfer_type === "inter_transaction") {
        return (
          data.sourceMethod &&
          data.targetMethod &&
          data.sourceMethod !== data.targetMethod
        );
      }
      return true;
    },
    {
      message:
        "Veuillez remplir tous les champs requis pour ce type de transfert",
      path: ["cardId"],
    },
  );

export type TransferFormData = z.infer<typeof transferSchema>;
