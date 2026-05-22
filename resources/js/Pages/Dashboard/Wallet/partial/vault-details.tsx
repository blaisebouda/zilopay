"use client"

import { CardTable } from "@/components/table/card-table"
import { Badge } from "@/components/ui/badge"
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"
import { SkeletonTable } from "@/components/ui/skeleton"
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import ENDPOINTS from "@/constants/endpoints"
import { useGet } from "@/hooks/use-get"
import type { Vault } from "@/types"
import { PiggyBank, Target, Wallet } from "lucide-react"
import { useEffect, useState } from "react"

interface VaultDetailsProps {
  currentVault: Vault | null
  open: boolean
  onOpenChange: (open: boolean) => void
}

const typeIcons = {
  savings: PiggyBank,
  investment: Target,
  emergency: Wallet,
}

export default function VaultDetails({
  currentVault,
  open,
  onOpenChange,
}: VaultDetailsProps) {
  const endpoint = ENDPOINTS.VAULT.show(currentVault?.uuid || "")

  const [vault, setVault] = useState<Vault | null>(currentVault)
  const { result, refetch, loading } = useGet<Vault>(endpoint, false)

  useEffect(() => {
    if (open && currentVault) {
      setVault(currentVault)
      // refetch();
    }
  }, [open, currentVault?.uuid, refetch])

  useEffect(() => {
    if (result) {
      setVault(result)
    }
  }, [result])

  const formatAmount = (amount: number, symbol: string) => {
    return `${amount.toLocaleString("fr-FR")} ${symbol}`
  }

  // const formatDate = (date: string) => {
  //   if (!date) return "N/A";
  //   return format(new Date(date), "dd MMMM yyyy", { locale: fr });
  // };

  const TypeIcon = vault ? typeIcons[vault.type] : PiggyBank

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-[800px] max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <TypeIcon className="h-5 w-5" />
            Détails du coffre
          </DialogTitle>
          <DialogDescription>
            Informations et transactions du coffre
          </DialogDescription>
        </DialogHeader>

        {vault && (
          <div className="space-y-6 py-4">
            {/* Partie 1: Récap du Vault + btn lock/unlock */}
            <Card>
              <CardHeader className="flex flex-row items-start justify-between">
                <div className="space-y-1">
                  <CardTitle className="text-xl">{vault.name}</CardTitle>
                  {vault.description && (
                    <CardDescription>{vault.description}</CardDescription>
                  )}
                  <div className="flex items-center gap-2 pt-2">
                    <Badge
                      variant={
                        vault.status_color as Parameters<
                          typeof Badge
                        >[0]["variant"]
                      }
                    >
                      {vault.status_label}
                    </Badge>
                    <Badge variant="outline" className="text-xs">
                      {vault.type_label}
                    </Badge>
                  </div>
                </div>
                <div className="flex gap-2">
                  {/* <Button
                    variant={vault.is_locked ? "default" : "outline"}
                    size="sm"
                    onClick={handleToggleLock}
                    disabled={loading}
                    className="flex items-center gap-2"
                  >
                    {vault.is_locked ? (
                      <>
                        <LockOpen className="h-4 w-4" />
                        Déverrouiller
                      </>
                    ) : (
                      <>
                        <Lock className="h-4 w-4" />
                        Verrouiller
                      </>
                    )}
                  </Button> */}
                </div>
              </CardHeader>
              <CardContent className="flex justify-between gap-4">
                <div className="flex items-baseline gap-1">
                  <span className="text-3xl font-bold">
                    {formatAmount(vault.amount, vault.currency_symbol)}
                  </span>
                  <span className="text-muted-foreground text-sm">
                    ({vault.currency})
                  </span>
                </div>
                <div>
                  {vault.maturity_date && (
                    <div className="text-sm text-muted-foreground">
                      <span className="font-medium">Date d'échéance:</span>{" "}
                      {vault.maturity_date}
                    </div>
                  )}
                  <div className="text-sm text-muted-foreground">
                    <span className="font-medium">Créé le:</span>{" "}
                    {vault.created_at}
                  </div>
                </div>
              </CardContent>
            </Card>

            {/* Partie 2: Liste des transactions */}
            <div>
              <h3 className="text-lg font-semibold mb-4">
                Historique des transactions
              </h3>

              <SkeletonTable loading={loading} />

              {vault?.transactions && vault.transactions.length > 0 ? (
                <CardTable>
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>Date</TableHead>
                        <TableHead>Type</TableHead>
                        <TableHead>Montant</TableHead>
                        <TableHead>Description</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {vault?.transactions.map((transaction) => (
                        <TableRow key={transaction.uuid}>
                          <TableCell className="text-sm">
                            {transaction.created_at}
                          </TableCell>
                          <TableCell>
                            <Badge
                              variant={
                                transaction.type_color as Parameters<
                                  typeof Badge
                                >[0]["variant"]
                              }
                              className="text-xs"
                            >
                              {transaction.type_label}
                            </Badge>
                          </TableCell>
                          <TableCell className="font-medium">
                            {formatAmount(
                              transaction.amount,
                              vault.currency_symbol
                            )}
                          </TableCell>
                          <TableCell className="text-sm text-muted-foreground max-w-[200px] truncate">
                            {transaction.description || "-"}
                          </TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </CardTable>
              ) : (
                <div className="text-center py-8 text-muted-foreground">
                  Aucune transaction disponible.
                </div>
              )}
            </div>
          </div>
        )}
      </DialogContent>
    </Dialog>
  )
}
