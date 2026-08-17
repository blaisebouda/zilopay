import { Button } from "@/components/ui/button"
import {
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { Checkbox } from "@/components/ui/checkbox"
import { ErrorsList } from "@/components/ui/errors-list"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { PhoneInput } from "@/components/ui/phone-input"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { useAppNavigation } from "@/hooks/use-app-navigation"
import { useFetch } from "@/hooks/use-fetch"
import { AppLayout } from "@/Layouts/AppLayout"
import api, { baseApi } from "@/lib/api"
import LS from "@/lib/ls"
import type { LoginResponse } from "@/types"
import { Link, router, usePage } from "@inertiajs/react"
import { Eye, EyeOff, Lock, Mail, Phone } from "lucide-react"
import { useEffect, useState } from "react"

export default function LoginPage() {
  const [showPassword, setShowPassword] = useState(false)
  const [form, setForm] = useState<{
    email: string | null
    phone_number: string | null
    password: string
    remember: boolean
  }>({
    email: null,
    phone_number: null,
    password: "",
    remember: false,
  })


  const [error, setError] = useState<string | null>(null)
  const [loading, setLoading] = useState(false)

  const { goTo } = useAppNavigation()

  const { props } = usePage() as any

  if (props.auth?.user) {
    goTo("/dashboard")
  }

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()

    setLoading(true);

    router.post('/auth/login', form, {
      onSuccess: async () => {
        const { data } = await api.get('auth/me');

        const result = data?.data

        if (result) {
          LS.set("user", result?.user)
          LS.set("wallet", result?.wallet)

          goTo("/dashboard")
        }

      },
      onError: (e) => {
        setError(Object.values(e).join())
      },
      onFinish: () => {
        setLoading(false);
      },
    });
    // post(form)
  }

  const reset = () => {
    setForm({
      email: null,
      phone_number: null,
      password: "",
      remember: false,
    })
  }

  return (
    <div className="flex items-center justify-center p-4">
      <div className="w-full bg-transparent max-w-md">
        <CardHeader className="text-center">
          <div className="flex justify-center pb-6">
            <a href="#">
              <img src="/images/logo.png" alt="Zilopay Logo" width={200} />
            </a>
          </div>
          <CardTitle className="text-3xl font-bold">Bon retour 👋!</CardTitle>
          <CardDescription>
            Veuillez entrer vos informations pour vous connecter
          </CardDescription>
        </CardHeader>

        <CardContent className="space-y-6">
          {/* <div className="grid grid-cols-3 gap-3">
            <Button variant="outline" className="w-full">
              <img src="/google-icon.svg" className="h-4 w-4" alt="Google" />
            </Button>
            <Button variant="outline" className="w-full">
              <span className="text-blue-600 font-bold text-lg">f</span>
            </Button>
            <Button variant="outline" className="w-full">
              <Github className="h-4 w-4" />
            </Button>
          </div>  */}

          {/* <div className="relative">
            <div className="absolute inset-0 flex items-center">
              <Separator />
            </div>
            <div className="relative flex justify-center text-xs uppercase">
              <span className="bg-white px-2 text-muted-foreground">ou</span>
            </div>
          </div> */}

          {/* Formulaire */}

          {error && (
            <ErrorsList
              title={error}
            />
          )}

          <form action="#" className="space-y-4" onSubmit={handleSubmit}>
            <div className="pt-4 space-y-4">
              <Tabs
                onValueChange={reset}
                defaultValue="email"
                className="w-full"
              >
                <TabsList className="grid w-full grid-cols-2">
                  <TabsTrigger value="email" className="gap-2">
                    <Mail className="h-4 w-4" />
                    Email
                  </TabsTrigger>
                  <TabsTrigger value="phone" className="gap-2">
                    <Phone className="h-4 w-4" />
                    Téléphone
                  </TabsTrigger>
                </TabsList>
                <TabsContent value="email" className="mt-4">
                  <div className="space-y-2">
                    <Label htmlFor="email">
                      <Mail className="h-4 w-4" />
                      Adresse e-mail*
                    </Label>
                    <Input
                      required={form.phone_number == null}
                      id="email"
                      type="email"
                      placeholder="Entrez votre e-mail"
                      value={form.email || ""}
                      onChange={(e) =>
                        setForm({
                          ...form,
                          email: e.target.value,
                        })
                      }
                    />
                  </div>
                </TabsContent>
                <TabsContent value="phone" className="mt-4">
                  <div className="space-y-4">
                    <Label htmlFor="email">
                      <Phone className="h-4 w-4" />
                      Numéro de téléphone*
                    </Label>
                    <PhoneInput
                      required={form.email == null}
                      placeholder="Entrez votre numéro"
                      value={form.phone_number || ""}
                      onChange={(value) =>
                        setForm({
                          ...form,
                          phone_number: value,
                        })
                      }
                    />
                  </div>
                </TabsContent>
              </Tabs>

              <div className="space-y-2">
                <Label htmlFor="password">
                  <Lock className="h-4 w-4" />
                  Mot de passe*
                </Label>
                <div className="relative">
                  <Input
                    required
                    id="password"
                    type={showPassword ? "text" : "password"}
                    placeholder="••••••••••••"
                    value={form.password}
                    onChange={(e) =>
                      setForm({
                        ...form,
                        password: e.target.value,
                      })
                    }
                  />
                  <Button
                    type="button"
                    variant="ghost"
                    size="sm"
                    className="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent"
                    onClick={() => setShowPassword(!showPassword)}
                  >
                    {showPassword ? (
                      <EyeOff className="h-4 w-4" />
                    ) : (
                      <Eye className="h-4 w-4" />
                    )}
                  </Button>
                </div>
              </div>
            </div>

            <div className="flex items-center justify-between">
              <div className="flex items-center space-x-2">
                <Checkbox
                  id="remember"
                  checked={form.remember}
                  onCheckedChange={(checked) =>
                    setForm({
                      ...form,
                      remember: checked as boolean,
                    })
                  }
                />
                <label
                  htmlFor="remember"
                  className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                  Se souvenir de moi
                </label>
              </div>
              <Button
                variant="link"
                className="px-0 font-normal text-sm text-primary"
              >
                Mot de passe oublié ?
              </Button>
            </div>

            <Button disabled={loading} className="w-full" type="submit">
              {loading ? "Connexion en cours..." : "Se connecter à Zilopay"}
            </Button>
          </form>
        </CardContent>

        <div className="flex justify-center py-4">
          <p className="text-sm text-muted-foreground">
            Nouveau sur notre plateforme ?{" "}
            <Link
              href="/register"
              className="hover:underline p-0 text-primary font-semibold"
            >
              Créer un compte
            </Link>
          </p>
        </div>
      </div>
    </div>
  )
}

LoginPage.layout = (page: React.ReactNode) => {
  return <AppLayout>{page}</AppLayout>
}
