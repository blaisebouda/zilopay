import DashboardLayout from "@/Layouts/DashboardLayout"
import Vault from "./vault"

export default function Wallets() {
  return (
    <>
      <Vault />
    </>
  )
}

Wallets.layout = (page: React.ReactNode) => (
  <DashboardLayout title="Coffres">{page}</DashboardLayout>
)
