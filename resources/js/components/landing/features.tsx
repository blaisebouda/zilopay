import { BellDot, Cable, ChartPie, Link } from "lucide-react";
import { Section, SectionTitle } from "../ui/landing";

const features = [
    {
        icon: <Cable className="h-4 w-4" />,
        title: "Une intégration via API",
        label: "Des intégrations pour votre activité",
        description:
            "Connectez votre application à ZiloPay avec l’API et recevez des notifications des paiements grâce aux webhooks.",
    },
    {
        icon: <Link className="h-4 w-4" />,
        title: "Un lien, un paiement",
        label: "Simple et rapide",
        description:
            "Générez des liens de paiement et partagez-les par le canal que vous utilisez déjà.",
    },
    {
        icon: <BellDot className="h-4 w-4" />,
        title: "Un webhooks pour vous",
        label: "Des notifications de paiement",
        description:
            "Les webhooks vous permettent de recevoir des événements liés aux paiements et de connecter leur suivi à vos outils.",
    },
    {
        icon: <ChartPie className="h-4 w-4" />,
        title: "Dashboard de suivi",
        label: "Un suivi plus lisible",
        description:
            "Retrouvez vos transactions et consultez leur statut depuis les outils de gestion ZiloPay.",
    },

]

export default function Features() {
    return (
        <Section id="features" className="">
            <SectionTitle
                description="Fonctionnalités"
            >
                Les paiements de votre activité, <br />
                plus simples à gérer
            </SectionTitle>
            <div className="ml-auto mt-8 w-full text-xl text-muted-foreground max-w-[400px]">
                <span className="font-bold">
                    Une expérience claire pour vous comme pour vos clients,
                </span>{" "}
                sans avoir à créer une boutique en ligne complète.
            </div>

            <div className="mt-10 grid grid-cols-1 md:grid-cols-2">
                {features.map((feature, index) => (
                    <FeatureCard
                        key={index}
                        title={feature.title}
                        label={feature.label}
                        description={feature.description}
                        icon={feature.icon}
                    />
                ))}
            </div>

        </Section>
    )
}

function FeatureCard({
    title,
    label,
    description,
    icon,
}: {
    title: string;
    label: string;
    description: string;
    icon: React.ReactNode;
}) {
    return (
        <div className="flex flex-col gap-4 p-6 bg-[#222222]/2 border hover:bg-[#222222]/10 transition-all duration-300 ">
            <div>
                <div className="flex items-center justify-center border rounded-xl text-foreground size-10 bg-[#222222]/10">{icon}</div>
                <h3 className="text-2xl font-serif mt-4">{title}</h3>
                <p className="text-muted-foreground">{label}</p>
            </div>
            <p className="text-muted-foreground mt-8 mb-auto">{description}</p>
        </div>
    )
}