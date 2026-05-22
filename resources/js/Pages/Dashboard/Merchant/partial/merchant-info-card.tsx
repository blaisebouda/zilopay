import { Badge } from "@/components/ui/badge";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import type { Merchant } from "@/types";
import {
  Building,
  Calendar,
  Coins,
  Mail,
  MapPin,
  Percent,
  Phone,
} from "lucide-react";

interface MerchantInfoCardProps {
  merchant: Merchant;
}

export default function MerchantInfoCard({ merchant }: MerchantInfoCardProps) {
  const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString("fr-FR", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
  };

  return (
    <Card>
      <CardHeader className="flex flex-row items-center justify-between">
        <div className="flex items-center gap-3">
          <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
            <Building className="h-5 w-5 text-primary" />
          </div>
          <div>
            <CardTitle>{merchant.business_name}</CardTitle>
            <p className="text-sm text-muted-foreground">{merchant.uuid}</p>
          </div>
        </div>
        <Badge variant={merchant.status_color}>{merchant.status_label}</Badge>
      </CardHeader>
      <CardContent className="grid gap-6 md:grid-cols-2">
        <div className="flex items-start gap-3">
          <Mail className="mt-0.5 h-4 w-4 text-muted-foreground" />
          <div>
            <p className="text-sm font-medium">Email</p>
            <p className="text-sm text-muted-foreground">
              {merchant.business_email}
            </p>
          </div>
        </div>
        <div className="flex items-start gap-3">
          <Phone className="mt-0.5 h-4 w-4 text-muted-foreground" />
          <div>
            <p className="text-sm font-medium">Téléphone</p>
            <p className="text-sm text-muted-foreground">
              {merchant.phone_number}
            </p>
          </div>
        </div>
        <div className="flex items-start gap-3">
          <MapPin className="mt-0.5 h-4 w-4 text-muted-foreground" />
          <div>
            <p className="text-sm font-medium">Pays</p>
            <p className="text-sm text-muted-foreground">{merchant.country}</p>
          </div>
        </div>
        <div className="flex items-start gap-3">
          <Coins className="mt-0.5 h-4 w-4 text-muted-foreground" />
          <div>
            <p className="text-sm font-medium">Frais fixes</p>
            <p className="text-sm text-muted-foreground">
              {merchant.fee_fixed} XOF
            </p>
          </div>
        </div>
        <div className="flex items-start gap-3">
          <Percent className="mt-0.5 h-4 w-4 text-muted-foreground" />
          <div>
            <p className="text-sm font-medium">Frais percentage</p>
            <p className="text-sm text-muted-foreground">
              {merchant.fee_percent}%
            </p>
          </div>
        </div>
      </CardContent>
    </Card>
  );
}
