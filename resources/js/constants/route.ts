const ROUTE = {
  DASHBOARD: "dashboard",
  TRANSACTIONS: "transactions",
  WALLETS: "wallets",
  WITHDRAWS: "withdraws",
  DEPOSIT: "deposit",
  SETTINGS: "settings",
  TRANSFER: "transfer",
  // VAULT: "vault",
  API_KEYS: "api-keys",
  MERCHANTS: "merchants",
  MERCHANT_CREATE: "merchants/create",
} as const

const buildDashboardRoute = (route: string) => {
  return `dashboard/${route}`
}

export { buildDashboardRoute, ROUTE }
