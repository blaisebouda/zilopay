"use client"

import { SectionHeader } from "@/components/sections/section-header"
import { CardTable } from "@/components/table/card-table"
import DashboardLayout from "@/Layouts/DashboardLayout"
import WithdrawForm from "./partial/withdraw-form"
import { withdraws } from "./withdraw-data"
import WithdrawTable from "./withdraw-table"

export default function Withdraw() {
  return (
    <>
      <SectionHeader
        title="Historique des retraits"
        description="Suivi des retraits en cours et terminés"
      >
        <WithdrawForm />
      </SectionHeader>

      <CardTable>
        <WithdrawTable withdraws={withdraws} />
      </CardTable>
    </>
  )
}

Withdraw.layout = (page: React.ReactNode) => (
  <DashboardLayout title="Retraits">{page}</DashboardLayout>
)
