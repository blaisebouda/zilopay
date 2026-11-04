import { Section, FaqAccordions, SectionTitle } from "../ui/landing";

const faqs = [
    {
        question: "Dois-je avoir un site internet pour utiliser un lien de paiement ?",
        answer: "Non. Vous pouvez partager un lien de paiement avec votre client sans disposer d’une boutique en ligne.",
    },
    {
        question: "Comment partager un lien avec mon client ?",
        answer: "Copiez le lien généré par ZiloPay et envoyez-le par le canal de votre choix, par exemple par e-mail ou messagerie.",
    },
    {
        question: "Quels moyens de paiement sont proposés ?",
        answer: "Les moyens disponibles dépendent de la configuration de la plateforme et du paiement. ZiloPay prend en charge des catégories telles que le mobile money, la carte, le virement bancaire et le paiement en espèces. Consultez les options présentées au moment du paiement.",
    },
    {
        question: "Puis-je intégrer ZiloPay à mon application ?",
        answer: "Oui. L’API permet d’intégrer la création et le suivi des paiements à une application. La documentation technique détaille les endpoints et les paramètres disponibles.",
    },
    {
        question: "Quelles devises sont prises en charge ?",
        answer: "La plateforme prévoit notamment le franc CFA BCEAO (XOF), l’euro (EUR) et le dollar américain (USD). Les devises proposées pour une transaction dépendent de la configuration de votre compte.",
    },
];

export default function Faqs() {
    return (
        <Section id="faq" aria-label="Foire aux questions" >
            <div className="mx-auto w-max">

                <SectionTitle
                    description="Voir les questions que les gens posent"
                >
                    FAQ (Questions fréquentes)
                </SectionTitle>

                <div className="mt-10 ">
                    <FaqAccordions
                        className="flex flex-col gap-4 max-w-3xl"
                        faqs={faqs}
                    />
                </div>
            </div>

        </Section>
    );
}