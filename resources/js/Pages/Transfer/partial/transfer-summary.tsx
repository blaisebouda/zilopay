import { Button } from "@/components/ui/button"
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { currencies, transferTypes } from "@/constants"
import { int } from "@/lib/utils"

import LS from "@/lib/ls"
import { usePaymentMethod } from "@/lib/stores/payment-method.store"
import type { TransferFormData } from "@/lib/validations/transfer.schema"

interface TransferSummaryProps {
  data: TransferFormData
  onConfirm: (formData: object) => void
  onBack: () => void
  isLoading?: boolean
}

export function TransferSummary({
  data,
  onConfirm,
  onBack,
  isLoading = false,
}: TransferSummaryProps) {
  const { data: paymentMethods } = usePaymentMethod()

  const transferType = transferTypes.find((t) => t.value === data.transfer_type)

  const currency = currencies.find((c) => c.value === data.currency)
  const sourceMethod = paymentMethods.find(
    (method) => method.id === int(data.sourceMethod)
  )
  const targetMethod = paymentMethods.find(
    (method) => method.id === int(data.targetMethod)
  )

  const calculateFees = () => {
    const amount = parseInt(data.amount)
    return Math.floor(amount * 0.01) // 2% de frais
  }

  const fees = calculateFees()
  const total = int(data.amount) + fees

  const parsedData = (from: TransferFormData) => ({
    type: from.transfer_type,
    sender_wallet_id: LS.get("wallet")?.id,
    receiver_wallet_id: from.cardId,
    amount: from.amount,
    source_method: from.sourceMethod,
    target_method: from.targetMethod,
  })

  return (
    <Card className="shadow-none">
      <CardHeader>
        <CardTitle className="text-2xl">Récapitulatif du transfert</CardTitle>
        <CardDescription>Détails du transfert avec les frais</CardDescription>
      </CardHeader>
      <CardContent className="space-y-4">
        <div className="space-y-2">
          <div className="flex justify-between">
            <span className="text-muted-foreground">Type de transfert:</span>
            <span className="font-medium">{transferType?.label}</span>
          </div>

          <div className="flex justify-between">
            <span className="text-muted-foreground">Montant:</span>
            <span className="font-medium">
              {parseInt(data.amount).toLocaleString()} {currency?.symbol}
            </span>
          </div>

          {data.transfer_type === "system" && (
            <div className="flex justify-between">
              <span className="text-muted-foreground">
                ID carte destinataire:
              </span>
              <span className="font-medium">{data.cardId}</span>
            </div>
          )}

          {data.transfer_type === "inter_transaction" && (
            <>
              <div className="flex justify-between">
                <span className="text-muted-foreground">De:</span>
                <span className="font-medium">{sourceMethod?.name}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-muted-foreground">Vers:</span>
                <span className="font-medium">{targetMethod?.name}</span>
              </div>
            </>
          )}
        </div>

        <div className="border-t pt-4">
          <div className="space-y-2">
            <div className="flex text-muted-foreground justify-between">
              <span>Frais de transfert:</span>
              <span className="font-medium">
                {fees.toLocaleString()} {currency?.symbol}
              </span>
            </div>
            <div className="flex justify-between text-lg font-semibold">
              <span>Total à payer:</span>
              <span>
                {total.toLocaleString()} {currency?.symbol}
              </span>
            </div>
          </div>
        </div>

        <div className="flex gap-3 pt-4">
          <Button
            variant="outline"
            onClick={onBack}
            className="flex-1"
            disabled={isLoading}
          >
            Modifier
          </Button>
          <Button
            onClick={() => onConfirm(parsedData(data))}
            className="flex-1"
            disabled={isLoading}
          >
            {isLoading ? "Traitement..." : "Confirmer le transfert"}
          </Button>
        </div>
      </CardContent>
    </Card>
  )
}
