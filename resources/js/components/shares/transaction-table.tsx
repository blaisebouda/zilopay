import { DataTable } from "@/components/table/data-table";
import { Badge } from "@/components/ui/badge";
import type { Transaction } from "@/types";
import type { ColumnDef } from "@tanstack/react-table";
import { ArrowDownLeft, ArrowUpRight, Repeat } from "lucide-react";

const TransactionIcon = ({ transaction }: { transaction: Transaction }) => {
  const colors = (transaction: Transaction) => {
    if (transaction.is_deposit) {
      return "bg-emerald-100 text-emerald-600";
    } else if (transaction.is_transfer) {
      return "bg-blue-100 text-blue-600";
    } else {
      return "bg-rose-100 text-rose-600";
    }
  };

  return (
    <div className="flex items-center gap-3">
      <div
        className={`rounded-full size-10 flex justify-center items-center ${colors(transaction)}`}
      >
        {transaction.is_deposit ? (
          <ArrowDownLeft size={20} />
        ) : transaction.is_transfer ? (
          <Repeat size={20} />
        ) : (
          <ArrowUpRight size={20} />
        )}
      </div>
      <div>
        <h2 className="font-medium">{transaction.type}</h2>
        <p className="text-muted-foreground">{transaction.target}</p>
      </div>
    </div>
  );
};

const columns: ColumnDef<Transaction>[] = [
  {
    accessorKey: "type",
    header: "Transaction",
    cell: ({ row }) => {
      return <TransactionIcon transaction={row.original} />;
    },
  },
  {
    accessorKey: "operator",
    header: "Opérateur",
  },
  {
    accessorKey: "ref",
    header: "Référence",
  },
  {
    accessorKey: "amount",
    header: "Montant",
    cell: ({ row }) => {
      const amount = row.getValue("amount") as string;
      return (
        <span
          className={`font-bold ${amount.startsWith("+") ? "text-emerald-500" : amount.startsWith("-") ? "text-foreground" : "text-rose-500"}`}
        >
          {amount}
        </span>
      );
    },
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
  transactions: Transaction[] | null;
  loading?: boolean;
}) {
  return (
    <>
      <DataTable loading={loading} data={transactions} columns={columns} />
    </>
  );
}
