"use client";

import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { ErrorsList } from "@/components/ui/errors-list";
import ENDPOINTS from "@/constants/endpoints";
import { type TransferFormData } from "@/constants/transfer-types";
import { useAppNavigation } from "@/hooks/use-app-navigation";
import { usePost } from "@/hooks/use-post";
import { ROUTE } from "@/router/route";
import { useState } from "react";
import { toast } from "sonner";
import { TransferForm } from "./partial/transfer-form";
import { TransferSummary } from "./partial/transfer-summary";

export default function Transfer() {
  const [step, setStep] = useState<"form" | "summary">("form");
  const [transferData, setTransferData] = useState<TransferFormData | null>(
    null,
  );
  const [isLoading, setIsLoading] = useState(false);

  const handleFormSubmit = (data: TransferFormData) => {
    setTransferData(data);
    setStep("summary");
  };
  const { post: sendTransfer, error } = usePost(
    ENDPOINTS.TRANSACTIONS.TRANSFER,
  );

  const { goToDashboard } = useAppNavigation();

  const handleConfirm = async (transferData: object) => {
    if (!transferData) return;

    setIsLoading(true);
    try {
      await sendTransfer(transferData);
      toast.success("Transfert effectué avec succès!", {
        position: "top-center",
      });
      goToDashboard(ROUTE.TRANSACTIONS);
    } finally {
      setIsLoading(false);
    }
  };

  const handleBack = () => {
    setStep("form");
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <Card className="max-w-md mx-auto">
        <CardHeader>
          <CardTitle className="text-2xl">Effectuer un transfert</CardTitle>
          <CardDescription>
            Transférer de l'argent vers un utilisateur ou entre méthodes de
            paiement
          </CardDescription>
        </CardHeader>
        <CardContent>
          {error && (
            <ErrorsList
              title={error.response?.message}
              errors={error.response?.errors}
            />
          )}

          {step === "form" && (
            <TransferForm onSubmit={handleFormSubmit} isLoading={isLoading} />
          )}

          {step === "summary" && transferData && (
            <TransferSummary
              data={transferData}
              onConfirm={handleConfirm}
              onBack={handleBack}
              isLoading={isLoading}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
