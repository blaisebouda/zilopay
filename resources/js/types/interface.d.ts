interface User {
  name: string
  email: string | null
  phone_number: string | null
  is_merchant: boolean
  is_admin: boolean
}

interface OtpResponse {
  identifier: string
  expires_at: string
  otp_expires_in: number
  token?: string
}

interface Wallet {
  id: string
  balance: number
  currency: string
  currency_symbol: string
}

interface MerchantAPIKey {
  uuid: string
  name: string
  key: string
  public_key: string
  secret?: string
  is_live: boolean
  is_active: boolean
  created_at: string
}

interface MerchantAPIKey {
  uuid: string
  name: string
  key: string
  public_key: string
  secret?: string
  is_live: boolean
  is_active: boolean
  created_at: string
}

interface ApiKey {
  uuid: string
  name: string
  key: string
  public_key: string
  is_live: boolean
  is_active: boolean
  expires_at: string
  created_at: string
}

export interface PaymentMethod {
  id: number
  name: string
  logo_url: string
  country_flag_url: string
  type: string
  code: string
  country: string
  country_label: string
  country_phone_code: string
  min_amount: number
  max_amount: number
  fee_percent: number
  fee_fixed: number
}

interface SharedPageProps {
  auth: { user: { name: string } | null }
}
