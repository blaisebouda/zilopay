import { CardTable } from "@/components/table/card-table"
import type { Transaction } from "@/types"
import TransactionTable from "@/components/shares/transaction-table"
import { useAppNavigation } from "@/hooks/use-app-navigation"
import { ROUTE } from "@/constants/route"

export default function RecentTransactions({
  transactions,
  loading,
}: {
  transactions: Transaction[] | null
  loading: boolean
}) {
  const { goToDashboard } = useAppNavigation()

  return (
    <>
      <div className="flex items-center justify-between pb-4">
        <h2 className="text-xl font-bold">Transactions Récentes</h2>
        <button
          onClick={() => goToDashboard(ROUTE.TRANSACTIONS)}
          className="text-sm text-muted-foreground hover:underline"
        >
          Voir tout l'historique
        </button>
      </div>
      <CardTable>
        <TransactionTable loading={loading} transactions={transactions} />
      </CardTable>
    </>
  )
}
