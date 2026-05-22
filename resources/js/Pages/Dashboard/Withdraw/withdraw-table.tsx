import { DataTable } from "@/components/table/data-table";
import { Badge } from "@/components/ui/badge";
import type { Withdraw } from "@/types";
import type { ColumnDef } from "@tanstack/react-table";

const columns: ColumnDef<Withdraw>[] = [
  {
    accessorKey: "target",
    header: "Destination",
  },
  {
    accessorKey: "ref",
    header: "Référence",
    cell: ({ row }) => {
      return (
        <span className="font-mono text-sm bg-gray-100 px-2 py-1 rounded">
          {row.original.ref}
        </span>
      );
    },
  },
  {
    accessorKey: "operator",
    header: "Opérateur",
  },

  {
    accessorKey: "amount",
    header: "Montant",
    cell: ({ row }) => {
      const amount = row.getValue("amount") as string;
      return <span className="font-bold text-rose-600">-{amount}</span>;
    },
  },
  {
    accessorKey: "status",
    header: "Statut",
    cell: ({ row }) => {
      const withdraw = row.original;
      return (
        <Badge
          variant={
            withdraw.statusColor as
              | "default"
              | "secondary"
              | "destructive"
              | "outline"
              | "success"
              | "failed"
              | "warning"
          }
        >
          {withdraw.status}
        </Badge>
      );
    },
  },
  {
    accessorKey: "date",
    header: "Date",
    cell: ({ row }) => {
      return <div className="text-sm">{row.original.date}</div>;
    },
  },
];

export default function WithdrawTable({
  withdraws,
}: {
  withdraws: Withdraw[];
}) {
  return (
    <>
      <DataTable data={withdraws} columns={columns} />
    </>
  );
}
