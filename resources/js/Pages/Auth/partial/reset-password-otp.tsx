import { RefreshCwIcon } from "lucide-react"

import { LoadingButton } from "@/components/customs/loading-button"
import { Button } from "@/components/ui/button"
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { ErrorsList } from "@/components/ui/errors-list"
import { Field, FieldLabel } from "@/components/ui/field"
import {
  InputOTP,
  InputOTPGroup,
  InputOTPSeparator,
  InputOTPSlot,
} from "@/components/ui/input-otp"
import ENDPOINTS from "@/constants/endpoints"
import { useAppNavigation } from "@/hooks/use-app-navigation"
import { usePost } from "@/hooks/use-post"
import { useState } from "react"
import { toast } from "sonner"
import type { OtpResponse, User } from "@/types/interface"

export function ResetPasswordOTPForm({
  result,
  nextStep,
}: {
  result: OtpResponse
  nextStep: (otp: string) => void
}) {
  const [otp, setOtp] = useState("")

  const onSubmit = async () => {
    nextStep(otp)
  }

  const { loading: resendLoading, post: resendPost } = usePost("")

  const resend = async () => {
    // await resendPost({
    //   identifier: result.identifier,
    // })
    toast.success("Le code de vérification a bien été renvoyé")
  }
  return (
    <Card className="mx-auto max-w-sm">
      <CardHeader>
        <CardTitle>Code Vérification</CardTitle>
        <CardDescription>
          Entrez le code de vérification que nous avons envoyé{" "}
          {result.identifier.includes("@") ? "à" : "au"}:{" "}
          <span className="font-bold">{result.identifier}</span>
        </CardDescription>
      </CardHeader>
      <CardContent>
        <Field>
          <div className="flex items-center justify-between">
            <FieldLabel htmlFor="otp-verification">
              Code de vérification
            </FieldLabel>
            <Button
              type="button"
              variant="outline"
              size="sm"
              onClick={resend}
              disabled={resendLoading}
            >
              <RefreshCwIcon />
              Renvoyer
            </Button>
          </div>
          <InputOTP
            maxLength={6}
            id="otp-verification"
            required
            value={otp}
            onChange={setOtp}
          >
            <InputOTPGroup className="*:data-[slot=input-otp-slot]:h-12 *:data-[slot=input-otp-slot]:w-11 *:data-[slot=input-otp-slot]:text-xl">
              <InputOTPSlot index={0} />
              <InputOTPSlot index={1} />
            </InputOTPGroup>
            <InputOTPSeparator className="mx-1" />
            <InputOTPGroup className="*:data-[slot=input-otp-slot]:h-12 *:data-[slot=input-otp-slot]:w-11 *:data-[slot=input-otp-slot]:text-xl">
              <InputOTPSlot index={2} />
              <InputOTPSlot index={3} />
            </InputOTPGroup>
            <InputOTPSeparator className="mx-1" />
            <InputOTPGroup className="*:data-[slot=input-otp-slot]:h-12 *:data-[slot=input-otp-slot]:w-11 *:data-[slot=input-otp-slot]:text-xl">
              <InputOTPSlot index={4} />
              <InputOTPSlot index={5} />
            </InputOTPGroup>
          </InputOTP>
        </Field>
      </CardContent>
      <CardFooter>
        <Field>
          <LoadingButton
            type="button"
            disabled={otp.length !== 6}
            className="w-full"
            loadingLabel="Vérification..."
            onClick={onSubmit}
          >
            Vérifier
          </LoadingButton>
        </Field>
      </CardFooter>
    </Card>
  )
}
