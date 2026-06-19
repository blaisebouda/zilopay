import {
  CreditCard,
  Eye,
  EyeOff,
  Plus,
  Send,
  Upload,
  Vault,
} from "lucide-react"

import { CopyButton } from "@/components/ui/copy-button"
import ENDPOINTS from "@/constants/endpoints"
import { ROUTE } from "@/constants/route"
import { useAppNavigation } from "@/hooks/use-app-navigation"
import { useGet } from "@/hooks/use-get"
import type { UserDashboard } from "@/types"
import { Wallet } from "@/types/interface"
import RecentTransactions from "./transaction-table"
import { useState } from "react"
import { CardSection } from "./card-section"
import { ActionButton } from "@/components/ui/action-btn"

export default function UserDashboard() {
  const { goTo, goToDashboard } = useAppNavigation()

  const { result, loading } = useGet<UserDashboard>(
    ENDPOINTS.TRANSACTIONS.dashboard
  )

  //sessionStorage.clear();

  return (
    <div className="space-y-8 bg-background text-foreground">
      <div className="grid gap-4 grid-cols-1 lg:grid-cols-3">
        {result?.wallet && (
          <WalletCard
            id={result?.wallet?.id || ""}
            balance={result?.wallet?.balance || 0}
            currency={result?.wallet?.currency || "XOF"}
            currency_symbol={result?.wallet?.currency_symbol || "XOF"}
          />
        )}

        <VaultCard />

        {/* Actions Rapides */}
        <div className=" rounded-2xl bg-card p-4 flex flex-col justify-between">
          <p className="mb-4 font-semibold text-accent-foreground">
            Actions Rapides
          </p>
          <div className="grid grid-cols-3 gap-2">
            <ActionButton icon={<Plus />} onClick={() => goTo(ROUTE.DEPOSIT)}>
              Déposer
            </ActionButton>
            <ActionButton icon={<Send />} onClick={() => goTo(ROUTE.TRANSFER)}>
              Transférer
            </ActionButton>
            <ActionButton
              icon={<Upload />}
              onClick={() => goTo(ROUTE.WITHDRAWS)}
            >
              Retirer
            </ActionButton>
          </div>
        </div>
      </div>

      {/* Transactions */}
      <div className="mx-auto grid lg:grid-cols-[1fr_400px] gap-4">
        <RecentTransactions
          loading={loading}
          transactions={result?.transactions || null}
        />
        <CardSection />
      </div>
    </div>
  )
}

/**
 * Wallet Card Component
 */
export function WalletCard({ id, balance, currency, currency_symbol }: Wallet) {
  const formattedBalance = new Intl.NumberFormat("fr-FR", {
    minimumFractionDigits: 2,
  }).format(balance)

  const [isVisible, setIsVisible] = useState(false)

  return (
    <div className="w-full flex flex-col gap-4 rounded-2xl p-4 bg-primary text-white shadow-lg">
      <div>
        <div className="flex items-center gap-2">
          <span className="text-sm opacity-80">Solde Total</span>
          {isVisible ? (
            <Eye
              className="w-5 h-5 opacity-80 cursor-pointer"
              onClick={() => setIsVisible(false)}
            />
          ) : (
            <EyeOff
              className="w-5 h-5 opacity-80 cursor-pointer"
              onClick={() => setIsVisible(true)}
            />
          )}
        </div>

        <h2 className="text-3xl mt-1 font-semibold tracking-tight">
          {currency_symbol} {isVisible ? formattedBalance : "******"}
        </h2>
      </div>

      <div>
        <p className="text-xs opacity-70">ID du wallet</p>
        <div className="flex items-center gap-2">
          <p className="text-sm font-medium">{id}</p>
          <CopyButton className="text-white" value={id} />
        </div>
      </div>
    </div>
  )
}

function VaultCard() {
  return (
    <div className="w-full bg-card p-4 flex flex-col gap-4 justify-center rounded-2xl">
      <div className="flex items-center gap-4">
        <div className="size-12 bg-primary/10 rounded-full flex text-primary items-center justify-center">
          <Vault size={24} />
        </div>
        <div>
          <p className="text-sm">Solde du coffre</p>
          <h2 className="font-bold text-xl">FCA 80 800</h2>
        </div>
      </div>
      <div className="flex items-center gap-4">
        <div className="size-12 bg-green-700/10 rounded-full flex text-green-700 items-center justify-center">
          <CreditCard size={24} />
        </div>
        <div>
          <p className="text-sm">Carte bancaire</p>
          <h2 className="font-bold text-xl">FCA 44 400</h2>
        </div>
      </div>
    </div>
  )
}
