import { Button } from "@/components/ui/button"
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form"
import { Input } from "@/components/ui/input"
import { User } from "@/types/interface"
import { zodResolver } from "@hookform/resolvers/zod"
import { useForm } from "react-hook-form"
import z from "zod"

const EditSchema = z.object({
  name: z.string().min(2, "Le nom doit contenir au moins 2 caractères"),
})

type EditFormData = z.infer<typeof EditSchema>

export default function FormProfile({ user }: { user: User }) {
  const form = useForm<EditFormData>({
    resolver: zodResolver(EditSchema),
    defaultValues: {
      name: user.name,
    },
  })

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(() => {})} className="space-y-4">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <FormLabel>Email</FormLabel>
            <Input
              placeholder="Ex: jean.kaborer@example.com"
              value={user.email || ""}
              readOnly
              className="bg-muted mt-2"
            />
          </div>
          <div>
            <FormLabel>Numéro de téléphone</FormLabel>
            <Input
              placeholder="Ex: 0123456789"
              value={user.phone_number || ""}
              readOnly
              className="bg-muted mt-2"
            />
          </div>
        </div>

        <FormField
          control={form.control}
          name="name"
          render={({ field }) => (
            <FormItem>
              <FormLabel>Nom Complet</FormLabel>
              <FormControl>
                <Input placeholder="Ex: Jean Kaborer" {...field} />
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />
        <Button type="submit">Sauvegarder</Button>
      </form>
    </Form>
  )
}
