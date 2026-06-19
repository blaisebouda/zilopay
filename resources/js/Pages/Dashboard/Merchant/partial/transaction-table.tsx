import { DataTable } from "@/components/table/data-table";
import { Badge } from "@/components/ui/badge";
import type { ColumnDef } from "@tanstack/react-table";

import type { MerchantTransaction } from "@/types";
import { CardTable } from "@/components/table/card-table";

const columns: ColumnDef<MerchantTransaction>[] = [
  {
    accessorKey: "reference",
    header: "Référence",
    cell: ({ row }) => {
      return <Badge variant="mono">{row.original.reference}</Badge>;
    },
  },
  {
    accessorKey: "client",
    header: "Client",
  },
  {
    accessorKey: "operator",
    header: "Opérateur",
  },
  {
    accessorKey: "amount_label",
    header: "Montant",
  },
  {
    accessorKey: "status",
    header: "Statut",
    cell: ({ row }) => {
      const tx = row.original;
      return <Badge variant={tx.status_color}>{tx.status}</Badge>;
    },
  },
  {
    accessorKey: "date",
    header: "Date",
  },
];

export default function TransactionTable({
  transactions,
  loading = false,
}: {
  transactions: MerchantTransaction[] | null;
  loading?: boolean;
}) {
  return (
    <div>
      <div className="flex items-center justify-between pb-4">
        <h2 className="text-xl font-bold">Transactions Récentes</h2>
        <button className="text-sm text-muted-foreground hover:underline">
          Voir tous
        </button>
      </div>
      <CardTable>
        <DataTable loading={loading} data={transactions} columns={columns} />
      </CardTable>
    </div>
  );
}
