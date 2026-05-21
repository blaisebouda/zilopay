import DashboardLayout from "@/Layouts/DashboardLayout"
import UserDashboard from "./partial/user-dashboard"

export default function Dashboard() {
  return <UserDashboard />
}

Dashboard.layout = (page: React.ReactNode) => (
  <DashboardLayout title="Tableau de bord">{page}</DashboardLayout>
)
