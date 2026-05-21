import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Switch } from "@/components/ui/switch";
import { Card, CardContent } from "@/components/ui/card";
import { Code } from "@/components/ui/code";
import Empty from "@/components/ui/empty";
import { SkeletonTable } from "@/components/ui/skeleton";
import ENDPOINTS from "@/constants/endpoints";
import { useGet } from "@/hooks/use-get";
import { Key, Plus, Trash2 } from "lucide-react";
import { useState } from "react";
import ApiKeyForm from "./partial/api-key-form";

export default function ApiKeyPage() {
  const [isFormOpen, setIsFormOpen] = useState(false);

  const { loading, result, refetch } = useGet<ApiKey[]>(
    ENDPOINTS.MERCHANT.api_key,
  );

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-2xl font-bold">Clés API</h1>
          <p className="text-muted-foreground">
            Gérez vos clés API pour accéder à nos services
          </p>
        </div>
        <Button
          onClick={() => setIsFormOpen(true)}
          className="flex items-center gap-2"
        >
          <Plus className="h-4 w-4" />
          Ajouter une clé
        </Button>
      </div>

      <SkeletonTable loading={loading} />

      <div className="grid gap-4 lg:grid-cols-2">
        {result?.map((item: ApiKey) => (
          <ApiCard key={item.key} item={item} />
        ))}
      </div>
      {result?.length === 0 && (
        <Empty
          title="Aucune clé API"
          description="Vous n'avez pas encore créé de clé API"
          Icon={Key}
        />
      )}

      <ApiKeyForm
        open={isFormOpen}
        onOpenChange={setIsFormOpen}
        refresh={refetch}
      />
    </div>
  );
}

function ApiCard({ item }: { item: ApiKey }) {
  return (
    <Card>
      <CardContent>
        <div className="flex items-center justify-between">
          <div>
            <div className="flex items-center gap-2">
              <span className="font-bold">Nom:</span>
              <span className="text-lg">{item.name}</span>
              <Badge variant={item.is_live ? "default" : "secondary"}>
                {item.is_active ? "Production" : "Test"}
              </Badge>
            </div>
            <div className="pt-4">
              <Code text={item.key} canCopy={false} />
            </div>
          </div>

          <div className="flex items-center gap-2">
            <Switch
              title={item.is_active ? "Désactiver" : "Activer"}
              checked={item.is_active}
              // onCheckedChange={() => toggleApiKeyStatus(apiKey.id)}
            />

            <Button
              variant="ghost"
              size="icon-sm"
              className="text-destructive hover:text-destructive"
            >
              <Trash2 className="h-3 w-3" />
            </Button>
          </div>
        </div>
      </CardContent>
    </Card>
  );
}
