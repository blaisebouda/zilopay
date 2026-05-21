"use client";

import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { ErrorsList } from "@/components/ui/errors-list";
import { type DepositFormData } from "@/constants";
import ENDPOINTS from "@/constants/endpoints";
import { useAppNavigation } from "@/hooks/use-app-navigation";
import { usePost } from "@/hooks/use-post";
import { DepositSummary } from "@/pages/deposit/partial/deposit-summary";
import { ROUTE } from "@/router/route";
import { useState } from "react";
import { toast } from "sonner";
import { DepositForm } from "./partial/deposit-form";

type Step = "form" | "summary";

export default function Deposit() {
  const [step, setStep] = useState<Step>("form");
  const [formData, setFormData] = useState<DepositFormData | null>(null);
  const [isLoading, setIsLoading] = useState(false);

  const handleFormSubmit = (data: DepositFormData) => {
    setFormData(data);
    setStep("summary");
  };

  const { post: initDeposit, error } = usePost(
    ENDPOINTS.TRANSACTIONS.INIT_DEPOSIT,
  );

  const { goToDashboard } = useAppNavigation();

  const handleConfirm = async (formData: object) => {
    setIsLoading(true);
    try {
      await initDeposit(formData);

      toast.success("Dépôt effectué avec succès!", {
        position: "top-center",
      });
      goToDashboard(ROUTE.TRANSACTIONS);
      setStep("form");
      setFormData(null);
    } finally {
      setIsLoading(false);
    }
  };

  const handleBack = () => {
    setStep("form");
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="max-w-md mx-auto">
        <Card>
          <CardHeader>
            <CardTitle className="text-2xl">Dépôt d'argent</CardTitle>
            <CardDescription>
              Confirmez vos informations de dépôt
            </CardDescription>
          </CardHeader>

          <CardContent>
            {error && (
              <ErrorsList
                title={error.response?.message}
                errors={error.response?.errors}
              />
            )}
            {step === "form" ? (
              <DepositForm onSubmit={handleFormSubmit} isLoading={isLoading} />
            ) : (
              formData && (
                <DepositSummary
                  data={formData}
                  onConfirm={handleConfirm}
                  onBack={handleBack}
                  isLoading={isLoading}
                />
              )
            )}
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
