"use client"
import Heading from "@/components/ui/heading"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import DashboardLayout from "@/Layouts/DashboardLayout"
import { KeyIcon, Shield, User2 } from "lucide-react"
import Password from "./password"
import Profile from "./profile"
import TwoFactor from "./two-factor"

export default function Settings() {
  const tabs = [
    {
      name: "Profil",
      value: "profile",
      icon: <User2 />,
      content: <Profile />,
    },
    {
      name: "Mot de passe",
      value: "password",
      icon: <KeyIcon />,
      content: <Password />,
    },
    {
      name: "Authentification à deux facteurs",
      value: "two-factor",
      icon: <Shield />,
      content: <TwoFactor />,
    },
  ]
  return (
    <>
      <Tabs
        className="flex w-full flex-row items-start justify-center gap-4"
        defaultValue={tabs[0].value}
        orientation="vertical"
      >
        <TabsList className="grid shrink-0 grid-cols-1 gap-1 bg-background p-0">
          <Heading
            title="Paramètres"
            description="Gérez vos paramètres de compte"
          />
          {tabs.map((tab) => (
            <TabsTrigger
              className="justify-start rounded-none border border-transparent border-b-[3px] px-3 py-1.5 data-[state=active]:border-primary"
              key={tab.value}
              value={tab.value}
            >
              {tab.icon} {tab.name}
            </TabsTrigger>
          ))}
        </TabsList>

        <div className="w-full shadow-lg max-w-3xl bg-card p-4 border rounded-xl h-[calc(100vh-140px)]">
          {tabs.map((tab) => (
            <TabsContent className="" key={tab.value} value={tab.value}>
              {tab.content}
            </TabsContent>
          ))}
        </div>
      </Tabs>
    </>
  )
}

Settings.layout = (page: React.ReactNode) => (
  <DashboardLayout title="Paramètres">{page}</DashboardLayout>
)
