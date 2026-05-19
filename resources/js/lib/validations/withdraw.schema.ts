import { z } from "zod";

export const withdrawSchema = z.object({
  paymentMethod: z
    .string()
    .min(1, "Veuillez sélectionner un moyen de paiement"),
  amount: z
    .string()
    .min(1, "Le montant est requis")
    .regex(/^\d+$/, "Le montant doit être un nombre valide")
    .refine(
      (val: string) => parseInt(val) >= 500,
      "Le montant minimum est de 500 FCFA",
    )
    .refine(
      (val: string) => parseInt(val) <= 500000,
      "Le montant maximum est de 500 000 FCFA",
    ),
  currency: z
    .string()
    .min(1, "Veuillez sélectionner une devise"),
  phoneNumber: z
    .string()
    .min(1, "Le numéro de téléphone est requis")
    .regex(
      /^[0-9]{8,11}$/,
      "Le numéro de téléphone doit contenir entre 8 et 11 chiffres",
    ),
  country: z
    .string()
    .min(1, "Veuillez sélectionner un pays"),
});

export type WithdrawFormData = z.infer<typeof withdrawSchema>;
