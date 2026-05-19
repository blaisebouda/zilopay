import { isValidPhoneNumber } from "react-phone-number-input";
import z from "zod";

export const phoneSchema = {
  phone_number: z
    .string()
    .refine(isValidPhoneNumber, { message: "Numéro de téléphone invalide" }),
};
