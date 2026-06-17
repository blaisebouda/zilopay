import { Card } from "../ui/card"

export function CardTable({ children }: { children: React.ReactNode }) {
  return <Card className="py-0 gap-0 overflow-hidden w-full">{children}</Card>
}
