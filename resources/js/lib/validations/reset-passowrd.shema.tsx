import { isValidPhoneNumber } from "react-phone-number-input"
import z from "zod"

export const ResetPasswordSchema = z
  .object({
    phone_number: z
      .string()
      .refine(isValidPhoneNumber, { message: "Numéro de téléphone invalide" })
      .nullable(),
    email: z.email("Email invalide").nullable(),
    password: z
      .string()
      .min(8, "Le mot de passe doit contenir au moins 8 caractères"),
    password_confirmation: z
      .string()
      .min(8, "Le mot de passe doit contenir au moins 8 caractères"),
    otp_code: z.string().optional(),
  })
  .refine((data) => data.password === data.password_confirmation, {
    message: "Les mots de passe ne correspondent pas",
    path: ["password_confirmation"],
  })

export type ResetPasswordFormData = z.infer<typeof ResetPasswordSchema>
