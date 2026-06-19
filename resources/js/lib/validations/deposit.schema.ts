import { z } from "zod";
import { phoneSchema } from "./common";

export const depositSchema = z.object({
  ...phoneSchema,
  payment_method: z
    .string()
    .min(1, "Veuillez sélectionner un moyen de paiement"),
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
  country: z.string().min(1, "Veuillez sélectionner un pays"),
});

export type DepositFormData = z.infer<typeof depositSchema>;
