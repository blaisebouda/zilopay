import z from "zod";
import { phoneSchema } from "./common";

export const MerchantFormSchema = z.object({
  ...phoneSchema,
  business_name: z
    .string()
    .min(2, "Le nom de l'entreprise doit contenir au moins 2 caractères"),
  business_email: z.email("Veuillez entrer une adresse email valide"),

  documents: z
    .array(z.instanceof(File))
    .min(1, "Veuillez télécharger au moins un document"),
  country: z.string().optional(),
});

export type MerchantFormData = z.infer<typeof MerchantFormSchema>;
