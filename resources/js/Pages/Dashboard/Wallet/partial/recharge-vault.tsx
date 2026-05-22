import { FormDialog } from "@/components/form/form-dialog"
import { Button } from "@/components/ui/button"
import { ErrorsList } from "@/components/ui/errors-list"
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form"
import { Input } from "@/components/ui/input"
import ENDPOINTS from "@/constants/endpoints"
import { usePost } from "@/hooks/use-post"
import LS from "@/lib/ls"
import { Endpoint } from "@/lib/utils"
import type { Vault } from "@/types"
import { zodResolver } from "@hookform/resolvers/zod"
import { useState } from "react"
import { useForm } from "react-hook-form"
import { toast } from "sonner"
import { z } from "zod"

interface RechargeVaultProps {
  refresh: () => void
  vault?: Vault
}

// TODO : Implement recharge vault form (form: {ammout}) open dialog
export default function RechargeVault({ refresh, vault }: RechargeVaultProps) {
  const form = useForm<{ amount: number }>({
    resolver: zodResolver(
      z.object({
        amount: z.number().min(100, "Le montant doit être supérieur à 100"),
      }) as any
    ),
    defaultValues: {
      amount: 0,
    },
  })

  const [open, setOpen] = useState(false)
  const endpoint = Endpoint(ENDPOINTS.VAULT.base).from([
    vault?.uuid || "",
    ENDPOINTS.VAULT.deposit,
  ])
  const { post: createVault, loading, error } = usePost(endpoint)

  const handleSubmit = async () => {
    try {
      await createVault({
        ...form.getValues(),
        wallet_id: LS.get("wallet")?.id,
      })
      toast.success("Coffre rechargé avec succès !")
      onClose()
      refresh()
    } catch {
      toast.error("Erreur lors du rechargement du coffre")
    }
  }

  const onClose = () => {
    setOpen(false)
    form.reset()
  }

  return (
    <FormDialog
      title="Recharger le coffre"
      description="Transférer de l'argent depuis votre compte Zilopay vers le coffre"
      loading={loading}
      open={open}
      onSubmit={handleSubmit}
      onOpenChange={setOpen}
      trigger={
        <Button size="sm" className="flex-1">
          Recharger
        </Button>
      }
      submitLabel="Recharger"
    >
      <div className="space-y-4">
        {error && <ErrorsList title={error.response?.message} />}

        <Form {...form}>
          <form
            onSubmit={form.handleSubmit(handleSubmit)}
            className="space-y-6"
          >
            <FormField
              control={form.control}
              name="amount"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Montant (FCFA)</FormLabel>
                  <FormControl>
                    <Input type="number" placeholder="Ex: 1000" {...field} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
          </form>
        </Form>
      </div>
    </FormDialog>
  )
}
