import Features from "@/components/landing/features"
import Header from "@/components/landing/header"
import Hero from "@/components/landing/hero"
import { Button } from "@/components/ui/button"
import { ROUTE } from "@/constants/route"
import { useAppNavigation } from "@/hooks/use-app-navigation"
import { Head } from "@inertiajs/react"

export default function Welcome() {


    return (
        <>
            <Head title="ZiloPay — Encaissez vos paiements en toute simplicité">
                <meta
                    head-key="description"
                    name="description"
                    content="Créez des liens de paiement, partagez-les avec vos clients et suivez vos transactions depuis une plateforme conçue pour les professionnels."
                />
            </Head>
            <Header />
            <main>
                <Hero />
                <Features />
            </main>
        </>
    )
}