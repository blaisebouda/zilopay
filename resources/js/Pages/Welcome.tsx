import { Button } from "@/components/ui/button"
import { ROUTE } from "@/constants/route"
import { useAppNavigation } from "@/hooks/use-app-navigation"

export default function Welcome() {
    const { goTo } = useAppNavigation()

    return (
        <div className="flex flex-col items-center justify-center min-h-screen py-2 bg-linear-to-t from-primary/30 to-white">
            <img src="/images/logo.png" alt="Zilopay Logo" width={200} />
            <h1 className="text-4xl font-bold mb-2">Une nouvelle façon de gérer votre argent.</h1>
            <p className="text-lg mb-8">Zilopay vous permet de gérer facilement vos finances et de faire des paiements en toute sécurité.</p>
            <div className="flex gap-4">
                <Button onClick={() => goTo(ROUTE.MERCHANT_CREATE)}>
                    Devenir un marchand
                </Button>
                <Button onClick={() => goTo('login')}>
                    Se connecter
                </Button>
            </div>

        </div>
    )
}