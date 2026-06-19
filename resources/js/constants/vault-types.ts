import { z } from "zod";

export const vaultFormSchema = z.object({
  name: z.string().min(1, "Le nom est requis"),
  description: z.string().nullable().optional(),
  type: z.enum(["savings", "investment", "emergency"]),
  maturity_date: z.string().nullable().optional(),
});

export type VaultFormData = z.infer<typeof vaultFormSchema>;
