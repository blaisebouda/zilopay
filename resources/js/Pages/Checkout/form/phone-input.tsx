import { ErrorLabel } from "@/components/ui/error-label"
import {
  InputGroup,
  InputGroupAddon,
  InputGroupInput,
} from "@/components/ui/input-group"
import type { PaymentMethod } from "@/types/interface"
import { useState } from "react"

interface PhoneInputProps {
  paymentMethod?: PaymentMethod
  country?: PaymentMethod
  errorMessage?: string
  onChange: (value: string) => void
}

const formatPhoneNumber = (value: string) => {
  const phoneNumber = value.replace(/\D/g, "")

  if (phoneNumber.length <= 2) return phoneNumber

  const chunks: string[] = []
  for (let i = 0; i < phoneNumber.length; i += 2) {
    chunks.push(phoneNumber.slice(i, i + 2))
  }
  return chunks.join(" ")
}

export default function PhoneInput({
  paymentMethod,
  onChange,
  country,
  errorMessage,
}: PhoneInputProps) {
  const [phone, setPhone] = useState("")

  const handlePhoneChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const formattedValue = formatPhoneNumber(e.target.value)
    setPhone(formattedValue)
    onChange(formattedValue)
  }

  return (
    <div>
      <label htmlFor="phone-input" className="text-sm font-medium">
        Numéro de téléphone
      </label>
      <InputGroup className="flex px-1">
        <InputGroupAddon className="pt-2 pl-2">
          {country && (
            <>
              <img
                src={country?.country_flag_url || ""}
                alt={`${country?.country_label || country} flag`}
                className="w-6 overflow-hidden rounded-[4px]"
              />
              {country?.country_phone_code}
            </>
          )}
        </InputGroupAddon>
        <InputGroupInput
          id="phone-input"
          type="phone"
          placeholder="07 07 07 07 07"
          onChange={handlePhoneChange}
          value={phone}
          maxLength={14}
        />
        <InputGroupAddon align="inline-end">
          {paymentMethod?.logo_url && (
            <img
              src={paymentMethod.logo_url}
              alt={paymentMethod.name || "Payment method"}
              className="size-6 rounded-full border border-white bg-card"
            />
          )}
        </InputGroupAddon>
      </InputGroup>
      <ErrorLabel message={errorMessage} />
    </div>
  )
}
