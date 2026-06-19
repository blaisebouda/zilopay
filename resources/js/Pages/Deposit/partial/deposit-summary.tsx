import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { countries, currencies } from "@/constants"
import LS from "@/lib/ls"
import { usePaymentMethod } from "@/lib/stores/payment-method.store"
import { type DepositFormData } from "@/lib/validations/deposit.schema"

interface DepositSummaryProps {
  data: DepositFormData
  onConfirm: (data: object) => void
  onBack: () => void
  isLoading?: boolean
}

export function DepositSummary({
  data,
  onConfirm,
  onBack,
  isLoading = false,
}: DepositSummaryProps) {
  const { data: paymentMethods } = usePaymentMethod()

  const paymentMethod = paymentMethods.find(
    (method) => method.id === parseInt(data.payment_method)
  )
  const currency = currencies.find((c) => c.value === data.currency)
  const country = countries.find((c) => c.value === data.country)

  const parseData = (form: DepositFormData) => ({
    wallet_id: LS.get("wallet")?.id,
    amount: parseInt(form.amount),
    payment_method_id: parseInt(form.payment_method),
    phone_number: form.phone_number,
  })

  const calculateFees = () => {
    const amount = parseInt(data.amount)
    return Math.floor(amount * 0.001) // 2% de frais
  }

  const fees = calculateFees()

  return (
    <Card>
      <CardHeader>
        <CardTitle>Récapitulatif du dépôt</CardTitle>
      </CardHeader>
      <CardContent className="space-y-4">
        <div className="space-y-2">
          <div className="flex justify-between">
            <span className="text-muted-foreground">Moyen de paiement:</span>
            <span className="font-medium">{paymentMethod?.name}</span>
          </div>
          <div className="flex justify-between">
            <span className="text-muted-foreground">Montant:</span>
            <span className="font-medium">
              {parseInt(data.amount).toLocaleString()} {currency?.symbol}
            </span>
          </div>
          <div className="flex justify-between">
            <span className="text-muted-foreground">Pays:</span>
            <span className="font-medium">{country?.label}</span>
          </div>
          <div className="flex justify-between">
            <span className="text-muted-foreground">Numéro de téléphone:</span>
            <span className="font-medium">{data.phone_number}</span>
          </div>
        </div>

        <div className="border-t pt-4 space-y-4">
          <div className="flex text-muted-foreground justify-between">
            <span>Frais de dépôt:</span>
            <span className="font-medium">
              {fees.toLocaleString()} {currency?.symbol}
            </span>
          </div>
          <div className="flex justify-between text-lg font-semibold">
            <span>Total à payer:</span>
            <span>
              {(parseInt(data.amount) + fees).toLocaleString()}{" "}
              {currency?.symbol}
            </span>
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
            onClick={() => onConfirm(parseData(data))}
            className="flex-1"
            disabled={isLoading}
          >
            {isLoading ? "Traitement..." : "Confirmer le dépôt"}
          </Button>
        </div>
      </CardContent>
    </Card>
  )
}
