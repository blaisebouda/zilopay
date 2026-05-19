import { Button } from "@/components/ui/button";
import { ErrorsList } from "@/components/ui/errors-list";
import { Form } from "@/components/ui/form";
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
} from "@/components/ui/stepper";
import ENDPOINTS from "@/constants/endpoints";

import { usePost } from "@/hooks/use-post";
import {
  RegisterSchema,
  type RegisterFormData,
} from "@/validations/register.shema";
import { zodResolver } from "@hookform/resolvers/zod";
import { useCallback, useState } from "react";
import { useForm } from "react-hook-form";
import { NavLink } from "react-router-dom";
import { toast } from "sonner";
import { InputOTPForm } from "./otp-verification";
import { RegisterInfosForm } from "./register-infos-form";

const steps = [
  {
    value: "infos",
    title: "Informations",
    description: "Entrez votre email ou téléphone",
    fields: [
      "email",
      "phone_number",
      "password",
      "password_confirmation",
    ] as const,
  },
  {
    value: "otp",
    title: "Vérification",
    description: "Vérifiez votre code OTP",
    fields: [] as const,
  },
];

export default function RegisterPage() {
  const [step, setStep] = useState("infos");

  const form = useForm<RegisterFormData>({
    resolver: zodResolver(RegisterSchema),
    defaultValues: {
      email: null,
      phone_number: null,
      password: "",
      password_confirmation: "",
      name: "",
      policy_accepted: true,
    },
  });

  const stepIndex = steps.findIndex((s) => s.value === step);

  const onValidate: NonNullable<StepperProps["onValidate"]> = useCallback(
    async (_value, direction) => {
      if (direction === "prev") return true;

      const stepData = steps.find((s) => s.value === step);
      if (!stepData) return true;

      const isValid = await form.trigger(stepData.fields);

      if (!isValid) {
        toast.info("Veuillez remplir tous les champs requis");
      }

      return isValid;
    },
    [form, step],
  );

  const { post, error, loading } = usePost<OtpResponse, RegisterFormData>(
    ENDPOINTS.AUTH.register,
  );

  const [registerResponse, setRegisterResponse] = useState<OtpResponse | null>(
    null,
  );

  const onSubmit = async (input: RegisterFormData) => {
    const response = await post(input);
    setRegisterResponse(response);
    toast.success("Votre compte a été créé avec succès");
    form.reset();
    setStep("otp");
  };

  return (
    <div className="max-w-lg mx-auto">
      <div className="text-center mb-6">
        <h1 className="text-3xl font-bold mb-1">Créer un compte</h1>
        <p className="text-muted-foreground">
          Inscrivez-vous pour commencer à utiliser nos services.
        </p>
      </div>

      {error?.response && (
        <ErrorsList
          title={error.response.message}
          errors={error.response.errors}
        />
      )}

      <Form {...form}>
        <form className="w-full" onSubmit={form.handleSubmit(onSubmit)}>
          <Stepper value={step} onValueChange={setStep} onValidate={onValidate}>
            <StepperList>
              {steps.map((step) => (
                <StepperItem key={step.value} value={step.value}>
                  <StepperTrigger>
                    <StepperIndicator />
                    <div className="flex flex-col gap-px">
                      <StepperTitle>{step.title}</StepperTitle>
                      <StepperDescription>
                        {step.description}
                      </StepperDescription>
                    </div>
                  </StepperTrigger>
                  <StepperSeparator className="mx-4" />
                </StepperItem>
              ))}
            </StepperList>
            <StepperContent value="infos">
              <RegisterInfosForm form={form} />
            </StepperContent>
            <StepperContent value="otp">
              {registerResponse && <InputOTPForm result={registerResponse} />}
            </StepperContent>
            {stepIndex === 0 && (
              <>
                <p className="text-sm text-muted-foreground text-center">
                  En continuant, vous acceptez nos{" "}
                  <a href="#" className="text-primary hover:underline">
                    conditions
                  </a>{" "}
                  d'utilisation.
                </p>
                <div className="flex justify-between items-center">
                  <p className="text-sm text-muted-foreground">
                    Déjà un compte ?{" "}
                    <NavLink
                      to="/login"
                      className="hover:underline p-0 text-primary font-semibold"
                    >
                      Se connecter
                    </NavLink>
                  </p>

                  <Button type="submit" disabled={loading}>
                    {loading ? "En cours..." : "Créer le compte"}
                  </Button>
                </div>
              </>
            )}
          </Stepper>
        </form>
      </Form>
    </div>
  );
}
