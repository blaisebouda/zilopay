import type { Withdraw } from "@/types";

export const withdraws: Withdraw[] = [
  {
    ref: "WTH-2024-001",
    amount: "50,000 CFA",
    status: "Succès",
    statusColor: "success",
    date: "Oct 24, 16:30",
    operator: "Orange",
    target: "Compte bancaire (1234..)",
  },
  {
    ref: "WTH-2024-002",
    amount: "25,000 CFA",
    status: "En attente",
    statusColor: "warning",
    date: "Oct 24, 14:15",
    operator: "Moov",
    target: "Mobile Money(+22656...)",
  },
  {
    ref: "WTH-2024-003",
    amount: "100,000 CFA",
    status: "Rejeté",
    statusColor: "failed",
    date: "Oct 23, 11:45",
    operator: "Wave",
    target: "Compte bancaire (1234..)",
  },
  {
    ref: "WTH-2024-004",
    amount: "75,000 CFA",
    status: "Succès",
    statusColor: "success",
    date: "Oct 23, 09:20",
    operator: "Zilopay",
    target: "Compte bancaire (1234..)",
  },
  {
    ref: "WTH-2024-005",
    amount: "30,000 CFA",
    status: "En traitement",
    statusColor: "warning",
    date: "Oct 22, 17:00",
    operator: "Orange",
    target: "Compte bancaire (1234..)",
  },
];
