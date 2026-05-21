"use client"

//import { SimpleSelect } from "@/components/form/select";
import { SectionHeader } from "@/components/sections/section-header"
// import {
//   SectionTableHeader,
//   SectionTableHeaderLeft,
//   SectionTableHeaderRight,
// } from "@/components/sections/section-table-header";
//import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card"
import ENDPOINTS from "@/constants/endpoints"
import { useGet } from "@/hooks/use-get"
import type { Transaction } from "@/types"
//import { RotateCcw } from "lucide-react";
import { useState } from "react"
import TransactionTable from "../../components/shares/transaction-table"

const transactionStatusOptions = [
  { value: "all", label: "Toutes les statuts" },
  { value: "pending", label: "En attente" },
  { value: "completed", label: "Terminées" },
]

const transactionTypeOptions = [
  { value: "all", label: "Toutes les types" },
  { value: "deposit", label: "Dépôt" },
  { value: "withdrawal", label: "Retrait" },
]

const operationTypeOptions = [
  { value: "all", label: "Toutes les opérations" },
  { value: "moov", label: "Moov" },
  { value: "orange", label: "Orange" },
]

export default function Transaction() {
  const { result, loading } = useGet<Transaction[]>(
    ENDPOINTS.TRANSACTIONS.history
  )

  const [filters, setFilters] = useState({
    status: "all",
    type: "all",
    operation: "all",
  })

  const handleStatusChange = (value: string) => {
    setFilters((prev: typeof filters) => ({ ...prev, status: value }))
  }

  const handleTypeChange = (value: string) => {
    setFilters((prev: typeof filters) => ({ ...prev, type: value }))
  }

  const handleOperationChange = (value: string) => {
    setFilters((prev: typeof filters) => ({ ...prev, operation: value }))
  }

  const resetAllFilters = () => {
    setFilters({
      status: "all",
      type: "all",
      operation: "all",
    })
  }

  const hasActiveFilters =
    filters.status !== "all" ||
    filters.type !== "all" ||
    filters.operation !== "all"

  return (
    <>
      <SectionHeader
        title="Historique des transactions"
        description="Suivi des transactions en cours et terminées"
      >
        {/* <Button>
          <Download /> Exporter en CSV
        </Button> */}
      </SectionHeader>
      <Card className="py-0 gap-0">
        {/* <SectionTableHeader>
          <SectionTableHeaderLeft>
            <SimpleSelect
              options={transactionStatusOptions}
              placeholder="Filtrer par statut"
              onValueChange={handleStatusChange}
              value={filters.status}
            />
            <SimpleSelect
              options={transactionTypeOptions}
              placeholder="Filtrer par type"
              onValueChange={handleTypeChange}
              value={filters.type}
            />
            <SimpleSelect
              options={operationTypeOptions}
              placeholder="Filtrer par opération"
              onValueChange={handleOperationChange}
              value={filters.operation}
            />
          </SectionTableHeaderLeft>

          <SectionTableHeaderRight>
            {hasActiveFilters && (
              <Button
                className="text-primary"
                variant="ghost"
                onClick={resetAllFilters}
              >
                <RotateCcw className="h-4 w-4 mr-2" />
                Annuler le filtre
              </Button>
            )}
          </SectionTableHeaderRight>
        </SectionTableHeader> */}

        <TransactionTable transactions={result} loading={loading} />
      </Card>
    </>
  )
}
