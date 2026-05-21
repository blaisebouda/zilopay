import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";
import ENDPOINTS from "@/constants/endpoints";
import { usePost } from "@/hooks/use-post";
import { useState } from "react";
import ShowCredentials from "./show-credentials";

interface ApiKeyFormData {
  name: string;
  is_live: boolean;
}

type ApiKeyResponse = {
  api_key: ApiKey;
  plain_secret: string;
};

interface ApiKeyFormProps {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  refresh: () => void;
}

export default function ApiKeyForm({
  open,
  onOpenChange,
  refresh,
}: ApiKeyFormProps) {
  const [formData, setFormData] = useState<ApiKeyFormData>({
    name: "",
    is_live: false,
  });

  const [showCredentials, setShowCredentials] = useState(false);
  const [result, setResult] = useState<ApiKeyResponse | null>(null);

  const { post, loading } = usePost<ApiKeyResponse>(ENDPOINTS.MERCHANT.api_key);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const resp = await post(formData);

    if (resp) {
      setResult(resp);
    }
    refresh();
    setShowCredentials(true);
    setFormData({ name: "", is_live: false });
    onOpenChange(false);
  };

  return (
    <>
      <ShowCredentials
        result={result}
        open={showCredentials}
        onOpenChange={setShowCredentials}
      />
      <Dialog open={open} onOpenChange={onOpenChange}>
        <DialogContent className="overflow-y-auto">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              Nouvelle clé API
            </DialogTitle>
            <DialogDescription>
              Créez une nouvelle clé API pour accéder à nos services
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={handleSubmit} className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="name">Nom de la clé</Label>
              <Input
                id="name"
                placeholder="Entrez un nom pour votre clé API"
                value={formData.name}
                onChange={(e) =>
                  setFormData({ ...formData, name: e.target.value })
                }
                required
              />
            </div>

            <div className="flex items-center justify-between">
              <div className="space-y-0.5">
                <Label htmlFor="is_live">Mode production</Label>
                <p className="text-sm text-muted-foreground">
                  Activez pour utiliser la clé en environnement de production
                </p>
              </div>
              <Switch
                id="is_live"
                checked={formData.is_live}
                onCheckedChange={(checked: boolean) =>
                  setFormData({ ...formData, is_live: checked })
                }
              />
            </div>

            <div className="flex justify-end gap-2">
              <Button
                type="button"
                variant="outline"
                onClick={() => onOpenChange(false)}
              >
                Annuler
              </Button>
              <Button disabled={loading} type="submit">
                {loading ? "Création en cours..." : "Créer la clé"}
              </Button>
            </div>
          </form>
        </DialogContent>
      </Dialog>
    </>
  );
}
