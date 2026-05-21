import { Card, CardContent } from "@/components/ui/card"
import type { Wallet } from "@/types/interface"

import { Copy, WalletIcon } from "lucide-react"

interface WalletProps {
  currency: string
  currencySymbol: string
  label: string
  balance: string
  id: string
  color: "green" | "blue" | "purple"
  isDefault?: boolean
}

export function WalletCard2({
  currency,
  currencySymbol,
  label,
  balance,
  id,
  color,
  isDefault,
}: WalletProps) {
  return isDefault ? (
    <DefaultCard
      currency={currency}
      currencySymbol={currencySymbol}
      label={label}
      balance={balance}
      id={id}
      color={color}
    />
  ) : (
    <CCard
      currency={currency}
      currencySymbol={currencySymbol}
      label={label}
      balance={balance}
      id={id}
      color={color}
    />
  )
}

function CCard({
  currency,
  currencySymbol,
  label,
  balance,
  id,
  color,
}: WalletProps) {
  const colorMap = {
    green: "text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10",
    blue: "text-blue-500 bg-blue-50 dark:bg-blue-500/10",
    purple: "text-indigo-500 bg-indigo-50 dark:bg-indigo-500/10",
  }

  return (
    <Card className="overflow-hidden shadow-none">
      <CardContent className="space-y-6">
        <div className="flex items-start justify-between">
          <div
            className={`flex size-12 items-center justify-center rounded-xl font-bold ${colorMap[color]}`}
          >
            {currencySymbol}
          </div>
          <span className={`text-xs font-medium`}>ID: {id}</span>
        </div>
        <div>
          <p className="text-xs font-medium tracking-tighter text-muted-foreground uppercase">
            {label}
          </p>
          <h3 className="text-2xl font-black">
            {balance}{" "}
            <span className="text-sm font-normal text-muted-foreground">
              {currency}
            </span>
          </h3>
        </div>
      </CardContent>
    </Card>
  )
}

function DefaultCard({
  currency,
  currencySymbol,
  label,
  balance,
  id,
}: WalletProps) {
  return (
    <Card className="overflow-hidden bg-gradient shadow-none border-none ">
      <CardContent className="space-y-6">
        <div className="flex items-start justify-between">
          <div
            className={`flex size-12 items-center bg-white/20 text-white justify-center rounded-xl font-bold`}
          >
            {currencySymbol}
          </div>
          <span className={`text-xs font-medium text-white/80`}>ID: {id}</span>
        </div>
        <div>
          <p className="text-xs font-medium tracking-tighter text-white/80 uppercase">
            {label}
          </p>
          <h3 className="text-2xl font-bold text-white">
            {balance}{" "}
            <span className="text-sm font-normal text-white/80">
              {currency}
            </span>
          </h3>
        </div>
      </CardContent>
    </Card>
  )
}

/**
 * Wallet Card Component
 */
export function WalletCard({ id, balance, currency, currency_symbol }: Wallet) {
  const formattedBalance = new Intl.NumberFormat("fr-FR", {
    minimumFractionDigits: 2,
  }).format(balance)

  return (
    <div className="w-full rounded-2xl p-6 bg-gradient text-white shadow-lg">
      {/* Header */}
      <div className="flex items-center justify-between mb-6">
        <div className="flex items-center gap-2">
          <WalletIcon className="w-5 h-5 opacity-80" />
          <span className="text-sm opacity-80">Wallet</span>
        </div>

        <span className="text-xs bg-white/20 px-3 py-1 rounded-full">
          {currency}
        </span>
      </div>

      {/* Balance */}
      <div className="mb-6">
        <p className="text-sm opacity-80 mb-1">Solde disponible</p>
        <h2 className="text-3xl font-semibold tracking-tight">
          {currency_symbol} {formattedBalance}
        </h2>
      </div>

      {/* Footer */}
      <div className="flex items-center justify-between">
        <div>
          <p className="text-xs opacity-70">ID du wallet</p>
          <p className="text-sm font-medium">{id}</p>
        </div>

        <button
          onClick={() => navigator.clipboard.writeText(id)}
          className="flex items-center gap-2 text-xs bg-white/20 hover:bg-white/30 px-3 py-2 rounded-lg transition"
        >
          <Copy className="w-4 h-4" />
          Copier
        </button>
      </div>
    </div>
  )
}
