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
  StepperNext,
  StepperPrev,
  StepperSeparator,
  StepperTitle,
  StepperTrigger,
  type StepperProps,
} from "@/components/ui/stepper";
import ENDPOINTS from "@/constants/endpoints";
import { useAppNavigation } from "@/hooks/use-app-navigation";
import { usePost } from "@/hooks/use-post";
import {
  MerchantFormSchema,
  type MerchantFormData,
} from "@/validations/merchant.schema";
import { zodResolver } from "@hookform/resolvers/zod";

import { useCallback, useState } from "react";
import { useForm } from "react-hook-form";
import { toast } from "sonner";
import MerchantCompanyForm from "./partial/merchant-company-form";
import MerchantDocumentForm from "./partial/merchant-document-form";

const steps = [
  {
    value: "company",
    title: "Infos de l'entreprise",
    description: "Entrez les infos de votre entreprise",
    fields: ["business_name", "business_email", "phone_number"] as const,
  },
  {
    value: "document",
    title: "Documents",
    description: "Importez vos documents",
    fields: ["documents"] as const,
  },
];

export default function MerchantCreatePage() {
  const [step, setStep] = useState("company");

  const form = useForm<MerchantFormData>({
    resolver: zodResolver(MerchantFormSchema),
    defaultValues: {
      business_name: "",
      business_email: "",
      phone_number: "",
      documents: [],
      country: "",
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

  const { post, error, loading } = usePost(ENDPOINTS.MERCHANT.base);
  const { goToDashboard } = useAppNavigation();

  const onSubmit = async (input: MerchantFormData) => {
    // Create FormData for file upload
    const formData = new FormData();
    formData.append("business_name", input.business_name);
    formData.append("business_email", input.business_email);
    formData.append("phone_number", input.phone_number);
    formData.append("country", input.country || "");

    // Append files
    input.documents.forEach((file, index) => {
      formData.append(`documents[${index}]`, file);
    });

    await post(formData as unknown as MerchantFormData);
    toast.success("Votre demande a bien été envoyée");
    goToDashboard("/merchants");
  };

  return (
    <div className="max-w-xl mx-auto">
      <div className="text-center mb-6">
        <h1 className="text-3xl font-bold mb-1">Devenir un marchand</h1>
        <p className="text-muted-foreground">
          Créez votre compte marchand pour commencer à accepter des paiements.
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
            <StepperContent value="company">
              <MerchantCompanyForm form={form} />
            </StepperContent>
            <StepperContent value="document">
              <MerchantDocumentForm form={form} />
            </StepperContent>
            <div className="mt-4 flex justify-between">
              <StepperPrev asChild>
                <Button type="button" variant="outline">
                  Précédents
                </Button>
              </StepperPrev>
              <div className="text-muted-foreground text-sm">
                Etape {stepIndex + 1} sur {steps.length}
              </div>
              {stepIndex === steps.length - 1 ? (
                <Button type="submit" disabled={loading}>
                  {loading ? "En cours..." : "Créer le compte"}
                </Button>
              ) : (
                <StepperNext
                  onClick={() => console.log(step)}
                  disabled={false}
                  asChild
                >
                  <Button type="button">Suivant</Button>
                </StepperNext>
              )}
            </div>
          </Stepper>
        </form>
      </Form>
    </div>
  );
}
