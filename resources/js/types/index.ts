import { type VariantProps } from "class-variance-authority";

export type RouterLinkType = {
  title: string;
  url: string;
};

export type StatusColor = VariantProps<
  typeof import("../components/ui/badge").badgeVariants
>["variant"];

export type LoginResponse = {
  user: User;
  token: string;
  wallet: Wallet;
};

export type Transaction = {
  type: string;
  target: string;
  reference: string;
  amount: string;
  status: string;
  status_color: StatusColor;
  created_at: string;
  date: string;
  operator: string;
  is_deposit: boolean;
  is_withdrawal: boolean;
  is_transfer: boolean;
};

export type Merchant = {
  uuid: string;
  business_name: string;
  business_email: string;
  phone_number: string;
  country: string;
  fee_fixed: number;
  fee_percent: number;
  status: string;
  status_label: string;
  status_color: StatusColor;
  approved_at: string;
  created_at: string;
};

export type MerchantResponse = {
  merchant: Merchant;
  statistics?: object;
  transactions?: MerchantTransaction[];
};

export type MerchantTransaction = {
  reference: string;
  client: string;
  operator: string;
  amount: number;
  amount_label: string;
  status: string;
  status_color: StatusColor;
  date: string;
};

export type UserDashboard = {
  wallet: Wallet;
  transactions: Transaction[];
};

export type Withdraw = {
  target: string;
  ref: string;
  amount: string;
  status: string;
  statusColor: StatusColor;
  date: string;
  operator: string;
};

export type VaultTransaction = {
  uuid: string;
  amount: number;
  type: string;
  type_label: string;
  type_color: string;
  description: string;
  created_at: string;
};

export type Vault = {
  uuid: string;
  name: string;
  description: string;
  amount: number;
  amount_label: string;
  currency: string;
  currency_symbol: string;
  type: "savings" | "investment" | "emergency";
  type_label: string;
  type_color: string;
  status: "active" | "locked" | "matured";
  status_label: string;
  status_color: string;
  maturity_date: string;
  is_locked: boolean;
  is_active: boolean;
  created_at: string;
  transactions?: VaultTransaction[];
};
export type VaultDashboard = {
  total_vaults: number;
  total_amount: number;
  vaults: Vault[];
};
