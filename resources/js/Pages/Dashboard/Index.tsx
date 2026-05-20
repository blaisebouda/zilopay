import { Button } from "@/components/ui/button"
import DashboardLayout from "@/Layouts/DashboardLayout"

export default function Dashboard() {
  return (
    <div>
      <Button>Button</Button>
      <h1 className="font-semibold font-sans">Hello Dashboard</h1>
    </div>
  )
}

Dashboard.layout = (page: React.ReactNode) => (
  <DashboardLayout title="Tableau de bord">{page}</DashboardLayout>
)
