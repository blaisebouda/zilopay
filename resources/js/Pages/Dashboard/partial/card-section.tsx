import { ActionButton } from "@/components/ui/action-btn"
import { Button } from "@/components/ui/button"
import { EyeClosed, Lock, Newspaper, Plus } from "lucide-react"
import { useState } from "react"

export function CardSection() {
  return (
    <div className="bg-card p-4 rounded-2xl">
      <div className="flex items-center justify-between pb-4">
        <h2 className="text-xl font-bold">Mes Cards</h2>
        <Button variant="outline" size="sm">
          <Plus /> Ajouter
        </Button>
      </div>
      <Card />
      <div className="grid grid-cols-3 gap-2 mt-6">
        <ActionButton icon={<Plus />} onClick={() => {}}>
          Recharger
        </ActionButton>
        <ActionButton icon={<Lock />} onClick={() => {}}>
          Bloquer
        </ActionButton>
        <ActionButton icon={<Newspaper />} onClick={() => {}}>
          Paiement
        </ActionButton>
      </div>
    </div>
  )
}

function Card() {
  const [isVisible, setIsVisible] = useState(false)

  return (
    <div className="bg-primary text-white p-4 rounded-xl">
      <div className="flex items-center justify-between">
        <img src="/images/visa.svg" alt="Visa" />
        <img src="/images/logo-white.png" className="h-6" alt="Zilopay Logo" />
      </div>
      <img src="/images/card-puce.png" className="w-10 mt-6" alt="Card Puce" />
      <h1 className="font-mono text-2xl flex items-center gap-2">
        <span> {isVisible ? "4349 3232 8080 " : "**** **** ****"} 1234</span>
        <span>
          <EyeClosed
            className="cursor-pointer"
            onClick={() => setIsVisible(!isVisible)}
          />
        </span>
      </h1>
      <div className="flex items-center justify-between mt-2">
        <div className="leading-tight">
          <p className="text-sm">Titulaire</p>
          <p className="font-semibold">Jules Kouame</p>
        </div>
        <div className="leading-tight">
          <p className="text-sm">Expire</p>
          <p className="font-semibold">01/25</p>
        </div>
      </div>
    </div>
  )
}
