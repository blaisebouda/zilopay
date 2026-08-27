import { Button } from "@/components/ui/button"
import { ErrorsList } from "@/components/ui/errors-list"
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form"
import {
  Stepper,
  StepperContent,
  StepperDescription,
  StepperIndicator,
  StepperItem,
  StepperList,
  StepperSeparator,
  StepperTitle,
  StepperTrigger,
  type StepperProps,
} from "@/components/ui/stepper"
import { usePost } from "@/hooks/use-post"
import { zodResolver } from "@hookform/resolvers/zod"
import { useCallback, useState } from "react"
import { useForm } from "react-hook-form"
import { toast } from "sonner"
import { Field, FieldLabel } from "@/components/ui/field"
import { InputOTP } from "@/components/ui/input-otp"
import { LoadingButton } from "@/components/customs/loading-button"
import { useAppNavigation } from "@/hooks/use-app-navigation"
import { ResetPasswordForm } from "./partial/reset-password-form"
import { ResetPasswordOTPForm } from "./partial/reset-password-otp"
import { Input } from "@/components/ui/input"
import { LockIcon, ShieldCheck } from "lucide-react"
import { OtpResponse } from "@/types/interface"
import { ResetPasswordFormData } from "@/lib/validations/reset-passowrd.shema"

type ResetFormData = {
  email: string | null
  phone_number: string | null
  token: string
  otp_code: string
  password: string
  password_confirmation: string
}

const steps = [
  {
    value: "request",
    title: "Demande",
    description: "Votre Email ou téléphone",
    fields: ["email", "phone_number"] as const,
  },
  {
    value: "otp",
    title: "OTP",
    description: "Entrez le code OTP reçu",
    fields: ["otp_code"] as const,
  },
  {
    value: "reset",
    title: "Réinitialiser",
    description: "Nouveau mot de passe",
    fields: ["otp_code", "password", "password_confirmation"] as const,
  },
]

export default function ResetPasswordPage() {
  const [step, setStep] = useState("request")

  const form = useForm<ResetPasswordFormData>({
    defaultValues: {
      phone_number: null,
      email: null,
      password: "",
      otp_code: "",
      password_confirmation: "",
    },
  })

  const stepIndex = steps.findIndex((s) => s.value === step)

  const onValidate: NonNullable<StepperProps["onValidate"]> = useCallback(
    async (_value, direction) => {
      if (direction === "prev") return true

      const stepData = steps.find((s) => s.value === step)
      if (!stepData) return true

      const isValid = await form.trigger(stepData.fields)

      if (!isValid) {
        toast.info("Veuillez remplir tous les champs requis")
      }

      return isValid
    },
    [form, step]
  )

  const [resetResponse, setResetResponse] = useState<any | null>(null)

  const {
    post: postForgot,
    error: forgotError,
    loading: forgotLoading,
  } = usePost<OtpResponse, { email: string }>("/auth/forgot-password")

  const {
    post: postReset,
    error: resetError,
    loading: resetLoading,
  } = usePost<any, Partial<ResetFormData>>("/auth/reset-password")

  const { goTo } = useAppNavigation()

  const onSubmitRequest = async (input: ResetFormData) => {
    const response = await postForgot({ email: input.email ?? "" })
    setResetResponse(response)
    toast.success(
      "Un email de réinitialisation a été envoyé. Vérifiez votre boîte."
    )
    setStep("otp")
  }

  const onSubmitReset = async (input: ResetFormData) => {
    await postReset({
      token: resetResponse?.token,
      email: input.email ?? "",
      otp_code: input.otp_code,
      password: input.password,
      password_confirmation: input.password_confirmation,
    })
    toast.success("Mot de passe réinitialisé avec succès")
    goTo("/login")
  }

  const handleOtpSubmit = (otp: string) => {
    form.setValue("otp_code", otp)
    setStep("reset")
  }

  const handleBack = () => {
    if (stepIndex === 0) {
      goTo("/login")
    } else {
      setStep(steps[stepIndex - 1].value)
    }
  }

  return (
    <div className="max-w-lg mx-auto p-4">
      <div className="text-center mb-6">
        <h1 className="text-3xl font-bold mb-1">
          Réinitialiser le mot de passe
        </h1>
        <p className="text-muted-foreground">
          Suivez les étapes pour réinitialiser votre mot de passe.
        </p>
      </div>

      {(forgotError?.response || resetError?.response) && (
        <ErrorsList
          title={
            forgotError?.response?.message || resetError?.response?.message
          }
          errors={forgotError?.response?.errors || resetError?.response?.errors}
        />
      )}

      <Form {...form}>
        <form
          className="w-full"
          onSubmit={form.handleSubmit(
            step === "request" ? onSubmitRequest : onSubmitReset
          )}
        >
          <Stepper value={step} onValueChange={setStep} onValidate={onValidate}>
            <StepperList>
              {steps.map((s) => (
                <StepperItem key={s.value} value={s.value}>
                  <StepperTrigger>
                    <StepperIndicator />
                    <div className="flex flex-col gap-px">
                      <StepperTitle>{s.title}</StepperTitle>
                      <StepperDescription>{s.description}</StepperDescription>
                    </div>
                  </StepperTrigger>
                  <StepperSeparator className="mx-4" />
                </StepperItem>
              ))}
            </StepperList>

            <StepperContent value="request">
              <ResetPasswordForm form={form} />
            </StepperContent>

            <StepperContent value="otp">
              <ResetPasswordOTPForm
                result={resetResponse}
                nextStep={handleOtpSubmit}
              />
            </StepperContent>

            <StepperContent value="reset">
              <div className="mx-auto  bg-card border p-4 sm:p-6 rounded-lg space-y-6">
                <FormField
                  control={form.control}
                  name="password"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel className="flex items-center gap-2">
                        <LockIcon className="h-4 w-4" />
                        Mot de passe
                      </FormLabel>
                      <FormControl>
                        <Input
                          type="password"
                          placeholder="Entrez votre mot de passe"
                          {...field}
                        />
                      </FormControl>
                      <FormMessage />
                    </FormItem>
                  )}
                />
                <FormField
                  control={form.control}
                  name="password_confirmation"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel className="flex items-center gap-2">
                        <ShieldCheck className="h-4 w-4" />
                        Confirmez le mot de passe
                      </FormLabel>
                      <FormControl>
                        <Input
                          type="password"
                          placeholder="Confirmez votre mot de passe"
                          {...field}
                        />
                      </FormControl>
                      <FormMessage />
                    </FormItem>
                  )}
                />
              </div>
            </StepperContent>

            {stepIndex === 0 && (
              <div className="flex justify-between items-center">
                <p className="text-sm text-muted-foreground">
                  Vous recevrez un code OTP sur votre adresse email ou
                  téléphone.
                </p>
                <Button type="submit" disabled={forgotLoading}>
                  {forgotLoading ? "Envoi..." : "Envoyer"}
                </Button>
              </div>
            )}

            {stepIndex === 2 && (
              <div className="flex justify-end items-center">
                <LoadingButton
                  type="submit"
                  loadingLabel="Réinitialisation..."
                  disabled={resetLoading}
                >
                  Réinitialiser
                </LoadingButton>
              </div>
            )}
          </Stepper>
        </form>
      </Form>
    </div>
  )
}
