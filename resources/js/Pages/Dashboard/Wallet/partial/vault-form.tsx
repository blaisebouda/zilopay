"use client"

import { Button } from "@/components/ui/button"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog"
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
import { Textarea } from "@/components/ui/textarea"
import ENDPOINTS from "@/constants/endpoints"
import { vaultFormSchema, type VaultFormData } from "@/constants/vault-types"
import { usePost } from "@/hooks/use-post"
import { zodResolver } from "@hookform/resolvers/zod"
import { Lock, PiggyBank, Target, Wallet } from "lucide-react"
import { useState } from "react"
import { useForm } from "react-hook-form"
import { toast } from "sonner"

interface VaultFormProps {
  refresh: () => void
}

export function VaultForm({ refresh }: VaultFormProps) {
  const form = useForm<VaultFormData>({
    resolver: zodResolver(vaultFormSchema),
    defaultValues: {
      name: "",
      description: null,
      type: "savings",
      maturity_date: null,
    },
  })

  const [open, setOpen] = useState(false)

  const { post: createVault, loading, error } = usePost(ENDPOINTS.VAULT.base)

  const onSubmit = async (data: VaultFormData) => {
    try {
      await createVault(data)
      toast.success("Coffre créé avec succès !")
      onClose()
      refresh()
    } catch {
      toast.error("Erreur lors de la création du coffre")
    }
  }

  const handleSubmit = (data: VaultFormData) => {
    onSubmit(data)
  }

  const onClose = () => {
    setOpen(false)
    form.reset()
  }

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>
        <Button className="flex items-center gap-2">
          <Lock className="h-4 w-4" />
          Créer un coffre
        </Button>
      </DialogTrigger>
      <DialogContent className="sm:max-w-[500px]">
        <DialogHeader>
          <DialogTitle>Créer un nouveau coffre</DialogTitle>
          <DialogDescription>
            Remplissez les informations pour créer votre coffre d'épargne
          </DialogDescription>
        </DialogHeader>

        {error && (
          <ErrorsList
            title={error.response?.message}
            errors={error.response?.errors}
          />
        )}

        <Form {...form}>
          <form
            onSubmit={form.handleSubmit(handleSubmit)}
            className="space-y-6"
          >
            <FormField
              control={form.control}
              name="name"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Nom du coffre</FormLabel>
                  <FormControl>
                    <Input placeholder="Ex: Épargne vacances" {...field} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            <FormField
              control={form.control}
              name="description"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Description (optionnel)</FormLabel>
                  <FormControl>
                    <Textarea
                      placeholder="Décrivez l'objectif de ce coffre..."
                      {...field}
                      value={field.value || ""}
                      onChange={(e) => field.onChange(e.target.value || null)}
                    />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            <FormField
              control={form.control}
              name="type"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Type de coffre</FormLabel>
                  <FormControl>
                    <div className="grid grid-cols-3 gap-3">
                      <Button
                        type="button"
                        variant={
                          field.value === "savings" ? "default" : "outline"
                        }
                        className="flex flex-col items-center gap-2 h-auto py-3"
                        onClick={() => field.onChange("savings")}
                      >
                        <PiggyBank className="h-5 w-5" />
                        <span className="text-xs">Épargne</span>
                      </Button>
                      <Button
                        type="button"
                        variant={
                          field.value === "investment" ? "default" : "outline"
                        }
                        className="flex flex-col items-center gap-2 h-auto py-3"
                        onClick={() => field.onChange("investment")}
                      >
                        <Target className="h-5 w-5" />
                        <span className="text-xs">Investissement</span>
                      </Button>
                      <Button
                        type="button"
                        variant={
                          field.value === "emergency" ? "default" : "outline"
                        }
                        className="flex flex-col items-center gap-2 h-auto py-3"
                        onClick={() => field.onChange("emergency")}
                      >
                        <Wallet className="h-5 w-5" />
                        <span className="text-xs">Urgence</span>
                      </Button>
                    </div>
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            <FormField
              control={form.control}
              name="maturity_date"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Date d'échéance (optionnel)</FormLabel>
                  <FormControl>
                    <Input
                      type="date"
                      {...field}
                      value={field.value || ""}
                      onChange={(e) => field.onChange(e.target.value || null)}
                    />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            <Button type="submit" className="w-full" disabled={loading}>
              {loading ? "Création..." : "Créer le coffre"}
            </Button>
          </form>
        </Form>
      </DialogContent>
    </Dialog>
  )
}
