export const currencies = [
  { value: "XOF", label: "FCFA", symbol: "FCFA" },
  { value: "EUR", label: "Euro", symbol: "€" },
  { value: "USD", label: "Dollar", symbol: "$" },
] as const;

export const countries = [
  {
    value: "BF",
    label: " 🇧🇫 +226 (Burkina Faso)",
    code: "+226",
    phoneLength: 8,
  },
  {
    value: "CI",
    label: " 🇨🇮 +225 (Côte d'Ivoire)",
    code: "+225",
    phoneLength: 10,
  },
  {
    value: "CM",
    label: " 🇨🇲 +237 (Cameroun)",
    code: "+237",
    phoneLength: 9,
  },
] as const;

export const transferTypes = [
  { value: "system", label: "Transfert système" },
  { value: "inter_transaction", label: "Transaction entre méthodes" },
] as const;
