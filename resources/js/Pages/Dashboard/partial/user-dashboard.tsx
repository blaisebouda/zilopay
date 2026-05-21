import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { Plus, Send, Upload } from "lucide-react"

import { WalletCard } from "@/components/ui/wallet-card"
import ENDPOINTS from "@/constants/endpoints"
import { ROUTE } from "@/constants/route"
import { useAppNavigation } from "@/hooks/use-app-navigation"
import { useGet } from "@/hooks/use-get"
import type { UserDashboard } from "@/types"
import RecentTransactions from "./transaction-table"

export default function UserDashboard() {
  const { goTo, goToDashboard } = useAppNavigation()

  const { result, loading } = useGet<UserDashboard>(
    ENDPOINTS.TRANSACTIONS.dashboard
  )

  //sessionStorage.clear();

  return (
    <div className="space-y-8 bg-background text-foreground">
      {/* <div className="mx-auto max-w-7xl">
        <div className="mb-6 flex items-center justify-between">
          <h2 className="flex items-center gap-2 text-xl font-bold">
            Mes Portefeuilles
            <span className="rounded bg-muted px-2 py-0.5 text-[10px] text-muted-foreground uppercase">
              Multi-devises
            </span>
          </h2>
          <Button
            variant="outline"
            size="sm"
            className="text-blue-600 hover:text-blue-700 dark:text-blue-400"
          >
            Ajouter un portefeuille
          </Button>
        </div> */}

      {result?.wallet && (
        <WalletCard
          id={result?.wallet?.id || ""}
          balance={result?.wallet?.balance || 0}
          currency={result?.wallet?.currency || "XOF"}
          currency_symbol={result?.wallet?.currency_symbol || "XOF"}
        />
      )}

      {/* Actions Rapides */}
      <Card className="mx-auto max-w-7xl bg-slate-100 shadow-none dark:bg-slate-800">
        <CardContent className="p-6">
          <p className="mb-4 text-[10px] font-bold tracking-widest text-primary uppercase dark:text-blue-400">
            Actions Rapides
          </p>
          <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
            <Button onClick={() => goTo(ROUTE.DEPOSIT)} size="lg">
              <Plus className="h-5 w-5" /> Déposer
            </Button>
            <Button
              onClick={() => goToDashboard(ROUTE.WITHDRAWS)}
              variant="outline"
              size="lg"
            >
              <Upload className="h-5 w-5 rotate-180" /> Retirer
            </Button>
            <Button
              onClick={() => goTo(ROUTE.TRANSFER)}
              variant="outline"
              size="lg"
            >
              <Send className="h-5 w-5" /> Transférer
            </Button>
          </div>
        </CardContent>
      </Card>

      {/* Transactions */}
      <div className="mx-auto max-w-7xl">
        <RecentTransactions
          loading={loading}
          transactions={result?.transactions}
        />
      </div>
    </div>
  )
}
