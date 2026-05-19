const BASES = {
  TRANSACTION: "/transactions",
  AUTH: "/auth",
  VAULT: "/vaults",
  MERCHANT: "/merchant",
};

const ENDPOINTS = {
  TRANSACTIONS: {
    base: BASES.TRANSACTION,
    history: `${BASES.TRANSACTION}/history`,
    dashboard: `${BASES.TRANSACTION}/dashboard`,
    INIT_DEPOSIT: `${BASES.TRANSACTION}/init-deposit`,
    TRANSFER: `${BASES.TRANSACTION}/transfer`,
  },
  AUTH: {
    login: `${BASES.AUTH}/login`,
    logout: `${BASES.AUTH}/logout`,
    register: `${BASES.AUTH}/register`,
    verify_otp: `${BASES.AUTH}/verify-otp`,
    resend_otp: `${BASES.AUTH}/resend-otp`,
  },
  VAULT: {
    base: BASES.VAULT,
    deposit: "deposit",
    withdraw: "withdraw",
    toggle: "toggle",
  },
  MERCHANT: {
    base: BASES.MERCHANT,
    api_key: `${BASES.MERCHANT}/api-keys`,
    toggle_active: (uuid: string) => `${BASES.MERCHANT}/${uuid}/toggle-active`,
  },
} as const;

export default ENDPOINTS;
