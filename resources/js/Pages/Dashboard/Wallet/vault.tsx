import { Badge } from "@/components/ui/badge"
import { Button } from "@/components/ui/button"
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { SkeletonTable } from "@/components/ui/skeleton"
import ENDPOINTS from "@/constants/endpoints"
import { useGet } from "@/hooks/use-get"
import type { Vault, VaultDashboard } from "@/types"
import { Calendar, Eye, Lock, Shield } from "lucide-react"
import { useState } from "react"
import RechargeVault from "./partial/recharge-vault"
import VaultDetails from "./partial/vault-details"
import { VaultForm } from "./partial/vault-form"

const getTypeColor = (type: Vault["type"]) => {
  switch (type) {
    case "savings":
      return "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
    case "investment":
      return "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
    case "emergency":
      return "bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200"
    default:
      return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200"
  }
}

const getTypeLabel = (type: Vault["type"]) => {
  switch (type) {
    case "savings":
      return "Épargne"
    case "investment":
      return "Investissement"
    case "emergency":
      return "Urgence"
    default:
      return "Autre"
  }
}

const getStatusColor = (status: Vault["status"]) => {
  switch (status) {
    case "active":
      return "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
    case "locked":
      return "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"
    case "matured":
      return "bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200"
    default:
      return "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200"
  }
}

const getStatusLabel = (status: Vault["status"]) => {
  switch (status) {
    case "active":
      return "Actif"
    case "locked":
      return "Verrouillé"
    case "matured":
      return "Échu"
    default:
      return "Inconnu"
  }
}

export default function Vault() {
  const { result, loading, refetch } = useGet<VaultDashboard>(
    ENDPOINTS.VAULT.base
  )

  const [open, setOpen] = useState(false)
  const [currentVault, setCurrentVault] = useState<Vault | null>(null)

  return (
    <div className=" mx-auto ">
      {/* Vault details */}
      <VaultDetails
        currentVault={currentVault}
        open={open}
        onOpenChange={setOpen}
      />
      {/* Header */}
      <div className="mb-8">
        <div className="flex items-center justify-between mb-6">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">
              Mes Coffres
            </h1>
            <p className="text-gray-600 dark:text-gray-400">
              Gérez vos coffres d'épargne et d'investissement
            </p>
          </div>
          <VaultForm refresh={refetch} />
        </div>

        {/* Carte résumé */}
        <Card className="mb-8 bg-gradient text-white">
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <Shield className="h-5 w-5" />
              Valeur Totale des Coffres
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold mb-2">
              {result?.total_amount.toLocaleString("fr-FR", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })}{" "}
              CFA
            </div>
            <p className="text-blue-100">
              {result?.total_vaults} coffres actifs
            </p>
          </CardContent>
        </Card>
      </div>

      {/* Grille des coffres */}
      <SkeletonTable loading={loading} />
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {result?.vaults?.map((vault) => (
          <Card key={vault.uuid} className="hover:shadow-lg transition-shadow">
            <CardHeader>
              <div className="flex items-start justify-between">
                <div className="flex-1">
                  <CardTitle className="text-lg mb-1">{vault.name}</CardTitle>
                  <CardDescription className="text-sm">
                    {vault.description}
                  </CardDescription>
                </div>
                <Lock className="h-5 w-5 text-gray-400 shrink-0 ml-2" />
              </div>

              <div className="flex gap-2 mt-3">
                <Badge className={getTypeColor(vault.type)}>
                  {getTypeLabel(vault.type)}
                </Badge>
                <Badge className={getStatusColor(vault.status)}>
                  {getStatusLabel(vault.status)}
                </Badge>
              </div>
            </CardHeader>

            <CardContent className="space-y-4">
              {/* Montant */}
              <div className="text-center py-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div className="text-2xl font-bold text-gray-900 dark:text-white">
                  {vault.amount_label} {vault.currency_symbol}
                </div>
              </div>
              <div>
                {vault.maturity_date && (
                  <div className="flex items-center justify-between">
                    <span className="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                      <Calendar className="h-4 w-4" />
                      Échéance
                    </span>
                    <span className="font-medium">{vault.maturity_date}</span>
                  </div>
                )}

                <div className="flex items-center justify-between">
                  <span className="text-gray-600 dark:text-gray-400">
                    Créé le
                  </span>
                  <span className="font-medium">{vault.created_at}</span>
                </div>
              </div>

              {/* Actions */}
              <div className="flex gap-2 pt-2">
                <Button
                  onClick={() => {
                    setCurrentVault(vault)
                    setOpen(true)
                  }}
                  variant="outline"
                  size="sm"
                  className="flex-1"
                >
                  <Eye className="h-4 w-4 mr-1" />
                  Détails
                </Button>
                <RechargeVault refresh={refetch} vault={vault} />
              </div>
            </CardContent>
          </Card>
        ))}
      </div>

      {/* État vide si pas de coffres */}
      {result?.vaults?.length === 0 && (
        <Card className="text-center py-12">
          <CardContent>
            <Lock className="h-12 w-12 text-gray-400 mx-auto mb-4" />
            <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
              Aucun coffre disponible
            </h3>
            <p className="text-gray-600 dark:text-gray-400 mb-4">
              Commencez à épargner en créant votre premier coffre
            </p>
            <div className="flex justify-center">
              <VaultForm refresh={refetch} />
            </div>
          </CardContent>
        </Card>
      )}
    </div>
  )
}
