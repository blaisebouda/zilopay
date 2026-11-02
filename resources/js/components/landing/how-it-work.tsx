import { Section, SectionTitle } from "../ui/landing";

const steps = [
    {
        title: "Créez un lien de paiement",
        description:
            "Renseignez  les informations utiles à la transaction.",
        icon: "/images/link.svg",
        gradient: "bg-gradient-to-b from-[#008CFF]/20 to-[#ffffff]"
    },
    {
        title: "Partagez le lien",
        description:
            "Envoyez le lien de paiement à votre client.",
        icon: "/images/share.svg",
        gradient: "bg-gradient-to-b from-[#3200FF]/20 to-[#ffffff]"
    },
    {
        title: "Votre client règle son achat",
        description:
            "Il ouvre la page de paiement et suit les instructions proposées.",
        icon: "/images/success_payment.svg",
        gradient: "bg-gradient-to-t from-[#3200FF]/20 to-[#ffffff]"
    },
    {
        title: "Suivez la transaction",
        description:
            "Consultez son statut dans vos outils ZiloPay.",
        icon: "/images/dashboard.svg",
        gradient: "bg-gradient-to-t from-[#008CFF]/20 to-[#ffffff]"
    }

]

export default function HowItWork() {
    return (
        <Section id="how-it-works">
            <SectionTitle
                description="3 étapes pour encaisser"
            >
                Comment ça marche ?
            </SectionTitle>

            <div className="mt-10 grid grid-cols-1 md:grid-cols-2">
                {steps.map((step, index) => (
                    <Step
                        key={index}
                        title={step.title}
                        description={step.description}
                        icon={step.icon}
                        gradient={step.gradient}
                    />
                ))}
            </div>
        </Section>
    )
}

function Step({ title, description, icon, gradient }: { title: string; description: string; icon: string; gradient: string }) {
    return (
        <div className={` flex flex-col items-start gap-8 text-center border p-6 transition-all duration-300 ${gradient}`}>
            <h3 className="text-2xl font-serif">{title}</h3>
            <img className="mx-auto" src={icon} alt="icon" />
            <p className="text-left text-muted-foreground">{description}</p>
        </div>
    )
}
