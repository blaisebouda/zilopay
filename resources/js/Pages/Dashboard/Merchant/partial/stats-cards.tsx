import { Card, CardContent } from "@/components/ui/card";
import {
  BadgeCheck,
  ShoppingBag,
  TrendingDown,
  TrendingUp,
  WalletIcon,
  type LucideIcon,
} from "lucide-react";

export default function StatsCards() {
  return (
    <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
      <StatCard
        title="Solde total combiné"
        value="1 250 800"
        valueSuffix="XOF"
        change="+12% ce mois-ci"
        Icon={WalletIcon}
        variant="up"
      />
      <StatCard
        title="Ventes du jour"
        value="3 250 400"
        valueSuffix="XOF"
        change="+12% vs hier"
        Icon={ShoppingBag}
        variant="up"
      />
      <StatCard
        title="Taux de réussite"
        value="95%"
        change="-1.2% vs moyenne"
        Icon={BadgeCheck}
        variant="down"
      />
    </div>
  );
}

type StatCardProps = {
  title: string;
  value: string;
  valueSuffix?: string;
  change: string;
  Icon: LucideIcon;
  variant?: "up" | "down" | "neutral";
};
function StatCard({
  title,
  value,
  valueSuffix,
  change,
  Icon,
  variant = "up",
}: StatCardProps) {
  const variantClasses = {
    up: "bg-green-100 text-green-700",
    down: "bg-red-100 text-red-700",
    neutral: "bg-gray-100 text-gray-700",
  };
  return (
    <Card>
      <CardContent className="space-y-2">
        <div className="flex justify-between items-center">
          <div className="space-y-2">
            <div className="text-sm font-medium text-muted-foreground">
              {title}
            </div>
            <div className="text-2xl font-bold">
              {value}
              {valueSuffix && (
                <span className="text-base pl-2 font-normal text-accent-foreground">
                  {valueSuffix}
                </span>
              )}
            </div>
          </div>
          <div className="bg-primary/10 text-primary flex size-8 shrink-0 items-center justify-center rounded-md">
            {<Icon className="size-5" />}
          </div>
        </div>

        <p
          className={`mt-4 flex px-2 py-0.5 text-sm font-medium w-max items-center rounded-full ${variantClasses[variant]}`}
        >
          {variant === "up" ? (
            <TrendingUp className="mr-2 h-4 w-4" />
          ) : variant === "down" ? (
            <TrendingDown className="mr-2 h-4 w-4" />
          ) : (
            <TrendingUp className="mr-2 h-4 w-4" />
          )}{" "}
          {change}
        </p>
      </CardContent>
    </Card>
  );
}
