import { SectionHeader } from "@/components/sections/section-header";
import { Button } from "@/components/ui/button";
import Empty from "@/components/ui/empty";
import { SkeletonTable } from "@/components/ui/skeleton";
import ENDPOINTS from "@/constants/endpoints";
import { useAppNavigation } from "@/hooks/use-app-navigation";
import { useGet } from "@/hooks/use-get";
import { ROUTE } from "@/router/route";
import type { MerchantResponse, MerchantTransaction } from "@/types";
import { Building2, KeyIcon } from "lucide-react";
import { useMemo } from "react";
import MerchantInfoCard from "./partial/merchant-info-card";
import StatsCards from "./partial/stats-cards";
import TransactionTable from "./partial/transaction-table";

const transactions: MerchantTransaction[] = [
  {
    reference: "TRX-8291",
    client: "+225 07 45 82 91",
    operator: "Orange",
    amount: 15000,
    amount_label: "15 000 XOF",
    status: "Success",
    status_color: "success",
    date: "Just now",
  },
  {
    reference: "TRX-8298",
    client: "+225 01 12 44 91",
    operator: "Moov",
    amount: 5500,
    amount_label: "5 500 XOF",
    status: "Pending",
    status_color: "warning",
    date: "2 mins ago",
  },
];

export default function MerchantPage() {
  const { goTo, goToDashboard } = useAppNavigation();

  const { result, loading } = useGet<MerchantResponse>(ENDPOINTS.MERCHANT.base);

  const isMerchant = useMemo(() => result?.statistics !== undefined, [result]);

  return (
    <>
      <SectionHeader
        title="Mon compte marchand"
        description="Gérez vos informations personnelles et votre compte marchand."
      >
        {isMerchant && (
          <Button
            onClick={() => goToDashboard(ROUTE.API_KEYS)}
            className="flex items-center gap-2"
          >
            <KeyIcon />
            Mes clés API
          </Button>
        )}
      </SectionHeader>
      {isMerchant && (
        <div className="space-y-6">
          <StatsCards />
          <TransactionTable transactions={transactions} />
        </div>
      )}

      {loading && <SkeletonTable />}
      {result && !isMerchant && <MerchantInfoCard merchant={result.merchant} />}
      {!result && !loading && (
        <Empty
          Icon={Building2}
          title="Aucun marchand trouvé"
          description="Vous n'avez pas de pas de compte marchand pour le moment."
        >
          <Button onClick={() => goTo(ROUTE.MERCHANT_CREATE)}>
            Devenir un marchand
          </Button>
        </Empty>
      )}
    </>
  );
}
