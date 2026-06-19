import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert"
import {
  AlertDialog,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog"
import { Badge } from "@/components/ui/badge"
import { Code } from "@/components/ui/code"
import { ApiKey } from "@/types/interface"
import { ShieldAlert } from "lucide-react"

type ApiKeyResponse = {
  api_key: ApiKey
  plain_secret: string
}

type ShowCredentialsProps = {
  result: ApiKeyResponse | null
  open: boolean
  onOpenChange: (open: boolean) => void
}

export default function ShowCredentials({
  result,
  open,
  onOpenChange,
}: ShowCredentialsProps) {
  return (
    <AlertDialog open={open} onOpenChange={onOpenChange}>
      <AlertDialogContent className="sm:max-w-2xl!">
        <AlertDialogHeader>
          <AlertDialogTitle className="font-semibold text-2xl tracking-[-0.015em]">
            🎉 La clé API a bien été créée !
          </AlertDialogTitle>
          <AlertDialogDescription className="text-[15px]">
            Veuillez sauvegarder le secret, il ne sera pas affiché à nouveau.
          </AlertDialogDescription>
        </AlertDialogHeader>
        {result && <ApiKeyDetails result={result} />}
        <AlertDialogFooter className="mt-4">
          <AlertDialogCancel>Fermer</AlertDialogCancel>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  )
}

export function ApiKeyDetails({ result }: { result: ApiKeyResponse }) {
  const apiKey = result.api_key

  return (
    <div className=" space-y-4">
      <div className="border p-4 bg-accent/20 w-full space-y-2 rounded-lg">
        <div>
          <span className="font-medium text-accent-foreground pr-2">Nom:</span>
          {apiKey.name}
        </div>
        <div className="flex justify-between">
          <div>
            <span className="font-medium text-accent-foreground pr-2">
              Status:
            </span>
            <Badge variant={apiKey.is_active ? "success" : "failed"}>
              {apiKey.is_active ? "Actif" : "Bloqué"}
            </Badge>
          </div>
          <div>
            <span className="font-medium text-accent-foreground pr-2">
              Environment:
            </span>
            <Badge variant={apiKey.is_live ? "default" : "secondary"}>
              {apiKey.is_live ? "Production" : "Test"}
            </Badge>
          </div>
        </div>
      </div>

      <div>
        <div className="font-medium">Clé d'environnement</div>
        <Code text={apiKey.key} />
      </div>
      <div>
        <div className="font-medium">Clé publique</div>
        <Code text={apiKey.public_key} />
      </div>

      <div className="space-y-1">
        <div className="font-medium">Clé secrète</div>
        <Code text={result.plain_secret} />
        <Alert className="border-none w-full bg-amber-600/10 text-amber-500 dark:bg-amber-600/15">
          <ShieldAlert className="size-4" />
          <AlertTitle>Important !!</AlertTitle>
          <AlertDescription className="text-amber-500">
            Veuillez sauvegarder le secret, il ne sera pas affiché à nouveau.
          </AlertDescription>
        </Alert>
      </div>
    </div>
  )
}
