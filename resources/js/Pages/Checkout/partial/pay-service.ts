import api from "@/lib/api"
import { removeSpaces } from "@/lib/utils"
import { InitDepositFormData } from "@/types/checkout"
import { PaymentMethod } from "@/types/interface"
import axios from "axios"

export function getUrlParams() {
  const params = new URLSearchParams(window.location.search)

  const amount = params.get("amount") || "0"
  const currency = params.get("currency") || "XOF"

  const amountFormatted = new Intl.NumberFormat("fr-FR", {
    style: "currency",
    currency,
  }).format(Number(amount))

  return {
    amount,
    merchant_name: params.get("merchant_name") || "",
    currency,
    amountFormatted,
    fullPath: window.location.pathname + window.location.search,
  }
}

export async function initPayment(
  form: InitDepositFormData,
  PaymentMethods: PaymentMethod[]
) {
  const { country, paymentMethod, phone } = form

  const pm = PaymentMethods.find(
    (method) => method.id === Number(paymentMethod)
  )

  const { fullPath } = getUrlParams()

  const response = await axios.post(fullPath, {
    country,
    payment_method: paymentMethod,
    customer_phone: pm?.country_phone_code + removeSpaces(phone),
  })

  return response
}
