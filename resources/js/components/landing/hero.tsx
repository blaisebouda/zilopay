import { CtbBtn } from "../ui/landing";

export default function Hero() {
    return (
        <section
            className="relative"
            aria-label="Section Héro"
        >
            <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-30">
                <div className="text-center flex flex-col items-center justify-center gap-6 max-w-4xl mx-auto">
                    <h1 className="text-4xl font-serif sm:text-5xl lg:text-6xl">
                        Encaissez vos paiements en toute simplicité
                    </h1 >
                    <h3 className="font-serif text-2xl">Une nouvelle façon de gérer votre argent.</h3>
                    <p className="text-muted-foreground max-w-2xl text-lg">
                        Créez des liens de paiement, partagez-les avec vos clients et suivez vos transactions depuis une plateforme conçue pour les professionnels.
                    </p>

                    <CtbBtn title="Commencer avec ZiloPay" href="/register" />


                </div>
            </div>
            <div>
                <img
                    src="/images/hero_image.jpg"
                    alt="ZiloPay"
                    className="w-full h-auto mt-10"
                />
            </div>

        </section>
    )
}
