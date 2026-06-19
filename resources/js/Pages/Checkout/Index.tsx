import { AppLayout } from "@/Layouts/AppLayout"

import { Button } from "@/components/ui/button"
import { useInitDepositFormValidation } from "@/hooks/use-form-validation"
import { usePaymentMethod } from "@/lib/stores/payment-method.store"
import { int } from "@/lib/utils"
import type { InitDepositFormData } from "@/types/checkout"
import { usePage } from "@inertiajs/react"
import { useState } from "react"
import CountrySelect from "./form/contry-select"
import PaymentMethodSelect from "./form/payment-method-select"
import PhoneInput from "./form/phone-input"
import { getUrlParams, initPayment } from "./partial/pay-service"

function InitDeposit() {
  const { data } = usePaymentMethod()
  const { amountFormatted } = getUrlParams()
  const [loading, setLoading] = useState(false)

  const [formData, setFormData] = useState<InitDepositFormData>({
    country: "",
    paymentMethod: "",
    phone: "",
  })

  const { errors, validateForm } = useInitDepositFormValidation(data || [])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    const isValid = validateForm(formData)

    if (isValid) {
      try {
        setLoading(true)
        const response = await initPayment(formData, data!)
        console.log(response)
      } catch (error) {
        console.error(error)
      } finally {
        setLoading(false)
      }
    }
  }

  return (
    <div className="mx-auto flex max-w-lg flex-col gap-4 space-y-6 rounded-2xl border bg-card p-6 text-sm leading-loose shadow-stone-400">
      <Header />
      <form className="space-y-2" action="#" onSubmit={handleSubmit}>
        <div className="grid grid-cols-2 gap-4">
          <CountrySelect
            errorMessage={errors.country}
            paymentMethods={data || []}
            onChange={(value) => setFormData({ ...formData, country: value })}
          />
          <PaymentMethodSelect
            errorMessage={errors.paymentMethod}
            paymentMethods={data || []}
            country={formData.country}
            onChange={(value) =>
              setFormData({ ...formData, paymentMethod: value })
            }
          />
        </div>
        <PhoneInput
          errorMessage={errors.phone}
          country={data?.find((m) => m.country === formData.country)}
          paymentMethod={data?.find(
            (method) => method.id === int(formData.paymentMethod)
          )}
          onChange={(value) => setFormData({ ...formData, phone: value })}
        />
        <Button
          size="lg"
          type="submit"
          className="mt-4 w-full rounded-lg font-semibold"
          disabled={loading}
        >
          {loading ? "Traitement..." : `Payer ${amountFormatted}`}
        </Button>
      </form>

      <Footer />
    </div>
  )
}

function Header() {
  const { merchant_name, amountFormatted } = getUrlParams()

  return (
    <div className="flex justify-between rounded-lg border  bg-[#E6F0FA] px-4 py-2">
      <div>
        <p className="text-muted-foreground">Marchand</p>
        <p className="text-base font-semibold">{merchant_name}</p>
      </div>
      <div className="text-right">
        <p className="text-muted-foreground">Montant</p>
        <p className="text-base font-semibold">{amountFormatted}</p>
      </div>
    </div>
  )
}

function Footer() {
  return (
    <footer className="flex justify-between text-sm leading-loose">
      <div>
        <p className="text-muted-foreground">En cliquant, vous acceptez les</p>
        <div className="">
          <a href="#" className="font-semibold underline hover:opacity-80">
            Conditions d'utilisation
          </a>
        </div>
      </div>
      <div className="text-right">
        <p className="text-muted-foreground">Payement sécurisé par</p>
        <p className="font-semibold">Zilopay</p>
      </div>
    </footer>
  )
}

function ErrorLink() {
  return (
    <div className="flex items-center justify-center min-h-screen p-4">
      <div className="text-center w-full max-w-xs mx-auto">
        <img
          className="mx-auto"
          src="/images/error-link.png"
          alt="Error link"
        />
        <h1 className="text-2xl font-bold">Lien de paiement invalide</h1>
        <p className="text-muted-foreground">
          Le lien de paiement que vous avez utilisé est invalide ou a expiré.
        </p>
      </div>
    </div>
  )
}

export default function Checkout() {
  const { props } = usePage()

  if (props.is_valid) {
    return <InitDeposit />
  }

  return <ErrorLink />
}

Checkout.layout = (page: React.ReactNode) => <AppLayout>{page}</AppLayout>
