import { Head } from "@inertiajs/react"
import {
    ArrowDown,
    ArrowRight,
    Bell,
    Check,
    ChevronDown,
    Code2,
    CreditCard,
    Link2,
    Menu,
    ShieldCheck,
    Smartphone,
    Store,
    WalletCards,
    X,
} from "lucide-react"
import { useState } from "react"

const navigation = [
    { label: "Fonctionnalités", href: "#fonctionnalites" },
    { label: "Comment ça marche", href: "#comment-ca-marche" },
    { label: "Pour qui ?", href: "#pour-qui" },
    { label: "FAQ", href: "#faq" },
]

const benefits = [
    {
        icon: Link2,
        title: "Un lien, un paiement",
        description:
            "Créez un lien de paiement et partagez-le par message, e-mail ou sur vos réseaux sociaux.",
        detail: "Pas besoin de boutique en ligne",
        color: "bg-blue-50 text-blue-700",
    },
    {
        icon: WalletCards,
        title: "Un suivi plus lisible",
        description:
            "Retrouvez vos transactions et consultez leur statut depuis vos outils de gestion ZiloPay.",
        detail: "Vos paiements au même endroit",
        color: "bg-emerald-50 text-emerald-700",
    },
    {
        icon: Code2,
        title: "Des intégrations utiles",
        description:
            "Connectez votre application à l’API et recevez les événements de paiement avec les webhooks.",
        detail: "API et webhooks disponibles",
        color: "bg-violet-50 text-violet-700",
    },
]

const steps = [
    {
        number: "01",
        title: "Créez votre paiement",
        description: "Renseignez le montant et les informations utiles à la transaction.",
    },
    {
        number: "02",
        title: "Partagez le lien",
        description: "Envoyez le lien à votre client, sur le canal que vous utilisez déjà.",
    },
    {
        number: "03",
        title: "Votre client règle",
        description: "Il ouvre la page de paiement et suit les instructions proposées.",
    },
    {
        number: "04",
        title: "Suivez la transaction",
        description: "Consultez son statut depuis vos outils ZiloPay.",
    },
]

const audiences = [
    {
        icon: Store,
        title: "Commerçants et boutiques",
        description: "Envoyez un lien pour régler une commande à distance.",
    },
    {
        icon: Smartphone,
        title: "Indépendants et prestataires",
        description: "Facilitez le règlement d’une prestation ou d’une facture.",
    },
    {
        icon: Code2,
        title: "Entrepreneurs du numérique",
        description: "Intégrez les paiements à vos propres parcours avec l’API.",
    },
]

const faqs = [
    {
        question: "Dois-je avoir un site internet pour utiliser un lien de paiement ?",
        answer:
            "Non. Vous pouvez partager un lien de paiement avec votre client sans disposer d’une boutique en ligne.",
    },
    {
        question: "Comment partager un lien avec mon client ?",
        answer:
            "Copiez le lien généré par ZiloPay et envoyez-le par le canal de votre choix, par exemple par e-mail ou messagerie.",
    },
    {
        question: "Quels moyens de paiement sont proposés ?",
        answer:
            "Les moyens disponibles dépendent de la configuration de la plateforme et du paiement. ZiloPay prend en charge des catégories telles que le mobile money, la carte, le virement bancaire et le paiement en espèces. Consultez les options présentées au moment du paiement.",
    },
    {
        question: "Puis-je intégrer ZiloPay à mon application ?",
        answer:
            "Oui. L’API permet d’intégrer la création et le suivi des paiements à une application. La documentation technique détaille les endpoints et les paramètres disponibles.",
    },
    {
        question: "Quelles devises sont prises en charge ?",
        answer:
            "La plateforme prévoit notamment le franc CFA BCEAO (XOF), l’euro (EUR) et le dollar américain (USD). Les devises proposées pour une transaction dépendent de la configuration de votre compte.",
    },
]

function Brand({ light = false }: { light?: boolean }) {
    return (
        <a
            href="/"
            className="inline-flex items-center gap-2.5"
            aria-label="ZiloPay, accueil"
        >
            <img
                src={light ? "/images/logo-white.png" : "/images/logo.png"}
                alt="ZiloPay"
                className="h-9 w-auto object-contain"
            />
        </a>
    )
}

function PaymentPreview() {
    return (
        <div className="relative mx-auto w-full max-w-[520px]">
            <div className="absolute -right-5 top-8 h-40 w-40 rounded-full bg-sky-200/60 blur-3xl" />
            <div className="absolute -bottom-4 -left-4 h-44 w-44 rounded-full bg-blue-200/70 blur-3xl" />
            <div className="relative rounded-[2rem] border border-white/80 bg-white/75 p-3 shadow-[0_35px_100px_-45px_rgba(23,79,140,0.45)] backdrop-blur">
                <div className="rounded-[1.5rem] border border-slate-100 bg-white p-5 sm:p-7">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center gap-3">
                            <div className="grid size-11 place-items-center rounded-2xl bg-blue-50">
                                <img
                                    src="/images/logo_icon.png"
                                    alt=""
                                    className="size-7 object-contain"
                                />
                            </div>
                            <div>
                                <p className="text-sm font-semibold text-slate-900">
                                    Espace marchand
                                </p>
                                <p className="text-xs text-slate-500">Aujourd’hui</p>
                            </div>
                        </div>
                        <button
                            type="button"
                            aria-label="Notifications"
                            className="grid size-10 place-items-center rounded-full border border-slate-100 text-slate-500"
                        >
                            <Bell className="size-4" />
                        </button>
                    </div>

                    <div className="mt-7 rounded-2xl bg-[#f5f8fc] p-5 sm:p-6">
                        <div className="flex items-center justify-between">
                            <p className="text-sm font-medium text-slate-600">
                                Paiement reçu
                            </p>
                            <span className="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                <span className="size-1.5 rounded-full bg-emerald-500" />
                                Confirmé
                            </span>
                        </div>
                        <p className="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                            25 000{" "}
                            <span className="text-xl font-semibold text-slate-500">
                                XOF
                            </span>
                        </p>
                        <div className="mt-5 flex items-center gap-3 border-t border-slate-200/80 pt-4">
                            <div className="grid size-10 place-items-center rounded-xl bg-white text-blue-700 shadow-sm">
                                <CreditCard className="size-5" />
                            </div>
                            <div className="min-w-0 flex-1">
                                <p className="truncate text-sm font-medium text-slate-800">
                                    Commande #ZP-2048
                                </p>
                                <p className="text-xs text-slate-500">Lien de paiement</p>
                            </div>
                            <Check className="size-5 text-emerald-600" />
                        </div>
                    </div>

                    <div className="mt-4 flex items-center justify-between rounded-2xl border border-slate-100 p-4">
                        <div className="flex items-center gap-3">
                            <div className="grid size-10 place-items-center rounded-xl bg-blue-50 text-blue-700">
                                <Link2 className="size-5" />
                            </div>
                            <div>
                                <p className="text-sm font-semibold text-slate-800">
                                    Votre lien est prêt
                                </p>
                                <p className="text-xs text-slate-500">
                                    Partagez-le avec votre client
                                </p>
                            </div>
                        </div>
                        <ArrowRight className="size-4 text-slate-400" />
                    </div>
                </div>
            </div>
            <div className="absolute -bottom-7 right-4 flex items-center gap-3 rounded-2xl border border-slate-100 bg-white px-4 py-3 shadow-xl shadow-blue-950/10 sm:right-[-1.5rem]">
                <div className="grid size-10 place-items-center rounded-xl bg-emerald-50 text-emerald-700">
                    <ShieldCheck className="size-5" />
                </div>
                <div>
                    <p className="text-sm font-semibold text-slate-900">
                        Suivi en temps réel
                    </p>
                    <p className="text-xs text-slate-500">Statut de transaction</p>
                </div>
            </div>
        </div>
    )
}

export default function Welcome() {
    const [menuOpen, setMenuOpen] = useState(false)

    return (
        <>
            <Head title="ZiloPay — Encaissez vos paiements en toute simplicité">
                <meta
                    head-key="description"
                    name="description"
                    content="Créez des liens de paiement, partagez-les avec vos clients et suivez vos transactions depuis une plateforme conçue pour les professionnels."
                />
            </Head>

            <div className="min-h-screen overflow-hidden bg-white text-slate-900">
                <header className="relative z-20 border-b border-slate-100 bg-white/90 backdrop-blur">
                    <div className="mx-auto flex h-[76px] max-w-7xl items-center justify-between px-5 sm:px-8">
                        <Brand />
                        <nav
                            aria-label="Navigation principale"
                            className="hidden items-center gap-8 lg:flex"
                        >
                            {navigation.map((item) => (
                                <a
                                    key={item.href}
                                    href={item.href}
                                    className="text-sm font-medium text-slate-600 transition hover:text-blue-700"
                                >
                                    {item.label}
                                </a>
                            ))}
                        </nav>
                        <div className="hidden items-center gap-3 lg:flex">
                            <a
                                href="/login"
                                className="px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:text-blue-700"
                            >
                                Se connecter
                            </a>
                            <a
                                href="/register"
                                className="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-700/15 transition hover:bg-blue-800"
                            >
                                Créer mon compte <ArrowRight className="size-4" />
                            </a>
                        </div>
                        <button
                            type="button"
                            aria-label={menuOpen ? "Fermer le menu" : "Ouvrir le menu"}
                            aria-expanded={menuOpen}
                            onClick={() => setMenuOpen((open) => !open)}
                            className="grid size-10 place-items-center rounded-xl border border-slate-200 text-slate-700 lg:hidden"
                        >
                            {menuOpen ? <X className="size-5" /> : <Menu className="size-5" />}
                        </button>
                    </div>
                    {menuOpen && (
                        <nav
                            aria-label="Navigation mobile"
                            className="absolute left-0 right-0 top-full border-b border-slate-100 bg-white px-5 py-5 shadow-lg lg:hidden"
                        >
                            <div className="mx-auto flex max-w-7xl flex-col gap-1">
                                {navigation.map((item) => (
                                    <a
                                        key={item.href}
                                        href={item.href}
                                        onClick={() => setMenuOpen(false)}
                                        className="rounded-lg px-3 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50"
                                    >
                                        {item.label}
                                    </a>
                                ))}
                                <a
                                    href="/login"
                                    className="rounded-lg px-3 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50"
                                >
                                    Se connecter
                                </a>
                                <a
                                    href="/register"
                                    className="mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white"
                                >
                                    Créer mon compte <ArrowRight className="size-4" />
                                </a>
                            </div>
                        </nav>
                    )}
                </header>

                <main>
                    <section className="relative isolate">
                        <div className="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_74%_38%,rgba(219,237,255,0.78),transparent_43%),linear-gradient(180deg,#fff_0%,#f8fbff_100%)]" />
                        <div className="mx-auto grid min-h-[650px] max-w-7xl items-center gap-14 px-5 py-20 sm:px-8 lg:grid-cols-[1.02fr_0.98fr] lg:gap-8 lg:py-24">
                            <div className="max-w-2xl">
                                <div className="mb-7 inline-flex items-center gap-2 rounded-full border border-blue-100 bg-white/80 px-3.5 py-2 text-sm font-medium text-blue-800 shadow-sm">
                                    <span className="size-2 rounded-full bg-emerald-500" />
                                    Le paiement pensé pour votre activité
                                </div>
                                <h1 className="text-4xl font-bold leading-[1.12] tracking-[-0.04em] text-slate-950 sm:text-5xl lg:text-[3.7rem]">
                                    Les paiements de votre activité,{" "}
                                    <span className="text-blue-700">
                                        plus simples à gérer.
                                    </span>
                                </h1>
                                <p className="mt-6 max-w-xl text-lg leading-8 text-slate-600">
                                    Créez un lien de paiement, partagez-le avec votre client et
                                    suivez la transaction depuis votre espace ZiloPay. Une
                                    expérience claire, sans avoir à créer une boutique en ligne
                                    complète.
                                </p>
                                <div className="mt-9 flex flex-col gap-3 sm:flex-row">
                                    <a
                                        href="/register"
                                        className="inline-flex min-h-14 items-center justify-center gap-2 rounded-xl bg-blue-700 px-6 text-base font-semibold text-white shadow-xl shadow-blue-700/20 transition hover:-translate-y-0.5 hover:bg-blue-800"
                                    >
                                        Commencer avec ZiloPay
                                        <ArrowRight className="size-4" />
                                    </a>
                                    <a
                                        href="#comment-ca-marche"
                                        className="inline-flex min-h-14 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 text-base font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-700"
                                    >
                                        Découvrir la plateforme
                                        <ArrowDown className="size-4" />
                                    </a>
                                </div>
                                <p className="mt-6 flex items-center gap-2 text-sm text-slate-500">
                                    <Check className="size-4 text-emerald-600" />
                                    Un paiement à encaisser ? Envoyez simplement votre lien.
                                </p>
                            </div>
                            <div className="px-2 pb-6 pt-2 sm:px-8 lg:px-0">
                                <PaymentPreview />
                            </div>
                        </div>
                    </section>

                    <section
                        id="fonctionnalites"
                        className="scroll-mt-20 border-y border-slate-100 bg-white py-20 sm:py-24"
                    >
                        <div className="mx-auto max-w-7xl px-5 sm:px-8">
                            <div className="mx-auto max-w-2xl text-center">
                                <p className="text-sm font-bold uppercase tracking-[0.16em] text-blue-700">
                                    Une solution qui s’adapte
                                </p>
                                <h2 className="mt-4 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                                    Vendez comme vous le souhaitez
                                </h2>
                                <p className="mt-4 text-lg leading-7 text-slate-600">
                                    En ligne, sur les réseaux sociaux ou directement à vos
                                    clients : organisez l’encaissement et le suivi de vos
                                    paiements simplement.
                                </p>
                            </div>
                            <div className="mt-12 grid gap-5 md:grid-cols-3">
                                {benefits.map(({ icon: Icon, ...benefit }) => (
                                    <article
                                        key={benefit.title}
                                        className="group rounded-2xl border border-slate-200/80 bg-white p-7 transition duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-950/5"
                                    >
                                        <div
                                            className={`grid size-12 place-items-center rounded-2xl ${benefit.color}`}
                                        >
                                            <Icon className="size-6" />
                                        </div>
                                        <h3 className="mt-6 text-xl font-semibold text-slate-950">
                                            {benefit.title}
                                        </h3>
                                        <p className="mt-3 min-h-[72px] leading-7 text-slate-600">
                                            {benefit.description}
                                        </p>
                                        <p className="mt-5 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                            <Check className="size-4 text-emerald-600" />
                                            {benefit.detail}
                                        </p>
                                    </article>
                                ))}
                            </div>
                        </div>
                    </section>

                    <section
                        id="comment-ca-marche"
                        className="scroll-mt-20 bg-[#f6f9fd] py-20 sm:py-24"
                    >
                        <div className="mx-auto grid max-w-7xl gap-12 px-5 sm:px-8 lg:grid-cols-[0.8fr_1.2fr] lg:gap-20">
                            <div className="max-w-lg">
                                <p className="text-sm font-bold uppercase tracking-[0.16em] text-blue-700">
                                    Comment ça marche ?
                                </p>
                                <h2 className="mt-4 text-3xl font-bold leading-tight tracking-tight text-slate-950 sm:text-4xl">
                                    De votre demande au paiement, en quelques étapes.
                                </h2>
                                <p className="mt-5 leading-7 text-slate-600">
                                    Gardez vos habitudes de vente. ZiloPay vous accompagne pour
                                    encaisser et retrouver les informations de vos transactions.
                                </p>
                                <a
                                    href="/register"
                                    className="mt-7 inline-flex items-center gap-2 font-semibold text-blue-700 hover:text-blue-900"
                                >
                                    Créer mon compte <ArrowRight className="size-4" />
                                </a>
                            </div>
                            <div className="grid gap-4 sm:grid-cols-2">
                                {steps.map((step) => (
                                    <article
                                        key={step.number}
                                        className="rounded-2xl border border-slate-200/80 bg-white p-6"
                                    >
                                        <span className="text-sm font-bold tracking-wider text-blue-700">
                                            {step.number}
                                        </span>
                                        <h3 className="mt-5 text-lg font-semibold text-slate-950">
                                            {step.title}
                                        </h3>
                                        <p className="mt-2 leading-6 text-slate-600">
                                            {step.description}
                                        </p>
                                    </article>
                                ))}
                            </div>
                        </div>
                    </section>

                    <section id="pour-qui" className="scroll-mt-20 py-20 sm:py-24">
                        <div className="mx-auto max-w-7xl px-5 sm:px-8">
                            <div className="mx-auto max-w-2xl text-center">
                                <p className="text-sm font-bold uppercase tracking-[0.16em] text-blue-700">
                                    Pensé pour les professionnels
                                </p>
                                <h2 className="mt-4 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                                    À chaque activité, sa façon d’encaisser
                                </h2>
                            </div>
                            <div className="mt-12 grid gap-5 md:grid-cols-3">
                                {audiences.map(({ icon: Icon, ...audience }) => (
                                    <article
                                        key={audience.title}
                                        className="flex gap-4 rounded-2xl border border-slate-200/80 p-6 sm:p-7"
                                    >
                                        <div className="grid size-12 shrink-0 place-items-center rounded-2xl bg-blue-50 text-blue-700">
                                            <Icon className="size-6" />
                                        </div>
                                        <div>
                                            <h3 className="font-semibold text-slate-950">
                                                {audience.title}
                                            </h3>
                                            <p className="mt-2 leading-6 text-slate-600">
                                                {audience.description}
                                            </p>
                                        </div>
                                    </article>
                                ))}
                            </div>
                        </div>
                    </section>

                    <section className="bg-[#0c2340] py-20 text-white sm:py-24">
                        <div className="mx-auto max-w-7xl px-5 sm:px-8">
                            <div className="grid items-center gap-12 lg:grid-cols-[0.8fr_1.2fr]">
                                <div className="max-w-xl">
                                    <p className="text-sm font-bold uppercase tracking-[0.16em] text-sky-300">
                                        Des outils pour aller plus loin
                                    </p>
                                    <h2 className="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">
                                        Une plateforme qui évolue avec votre activité
                                    </h2>
                                    <p className="mt-5 leading-7 text-blue-100/80">
                                        Des liens de paiement à l’intégration technique, retrouvez
                                        les outils pour gérer vos transactions à votre rythme.
                                    </p>
                                </div>
                                <div className="grid gap-4 sm:grid-cols-3">
                                    {[
                                        {
                                            icon: ShieldCheck,
                                            title: "Des liens maîtrisés",
                                            description:
                                                "Protégez vos liens par une signature et une durée de validité définie.",
                                        },
                                        {
                                            icon: Code2,
                                            title: "Une API à intégrer",
                                            description:
                                                "Initiez des paiements et récupérez les informations depuis votre application.",
                                        },
                                        {
                                            icon: Bell,
                                            title: "Des notifications",
                                            description:
                                                "Recevez des événements de paiement avec les webhooks.",
                                        },
                                    ].map(({ icon: Icon, ...feature }) => (
                                        <article
                                            key={feature.title}
                                            className="rounded-2xl border border-white/10 bg-white/[0.06] p-5"
                                        >
                                            <div className="grid size-11 place-items-center rounded-xl bg-blue-400/15 text-sky-300">
                                                <Icon className="size-5" />
                                            </div>
                                            <h3 className="mt-5 font-semibold">{feature.title}</h3>
                                            <p className="mt-2 text-sm leading-6 text-blue-100/75">
                                                {feature.description}
                                            </p>
                                        </article>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </section>

                    <section
                        id="faq"
                        className="scroll-mt-20 bg-[#f6f9fd] py-20 sm:py-24"
                    >
                        <div className="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[0.7fr_1.3fr] lg:gap-20">
                            <div>
                                <p className="text-sm font-bold uppercase tracking-[0.16em] text-blue-700">
                                    FAQ
                                </p>
                                <h2 className="mt-4 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                                    Vous avez des questions ?
                                </h2>
                                <p className="mt-4 leading-7 text-slate-600">
                                    Voici quelques réponses pour vous aider à démarrer avec
                                    ZiloPay.
                                </p>
                            </div>
                            <div className="divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white px-6 sm:px-8">
                                {faqs.map((faq) => (
                                    <details key={faq.question} className="group py-5">
                                        <summary className="flex cursor-pointer list-none items-center justify-between gap-5 font-semibold text-slate-900 marker:hidden">
                                            {faq.question}
                                            <ChevronDown className="size-5 shrink-0 text-slate-400 transition group-open:rotate-180" />
                                        </summary>
                                        <p className="max-w-2xl pr-8 pt-3 leading-7 text-slate-600">
                                            {faq.answer}
                                        </p>
                                    </details>
                                ))}
                            </div>
                        </div>
                    </section>

                    <section className="px-5 py-16 sm:px-8 sm:py-20">
                        <div className="mx-auto flex max-w-7xl flex-col items-start justify-between gap-8 rounded-[2rem] bg-blue-700 px-7 py-10 text-white sm:px-12 sm:py-12 lg:flex-row lg:items-center">
                            <div className="max-w-2xl">
                                <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">
                                    Passez à une gestion plus simple de vos paiements
                                </h2>
                                <p className="mt-4 leading-7 text-blue-100">
                                    Commencez à créer vos liens de paiement et découvrez les
                                    outils ZiloPay pour suivre vos transactions.
                                </p>
                            </div>
                            <div className="flex w-full shrink-0 flex-col gap-3 sm:w-auto sm:flex-row">
                                <a
                                    href="/register"
                                    className="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-white px-5 font-semibold text-blue-800 transition hover:bg-blue-50"
                                >
                                    Créer mon compte <ArrowRight className="size-4" />
                                </a>
                                <a
                                    href="/login"
                                    className="inline-flex min-h-12 items-center justify-center rounded-xl border border-white/30 px-5 font-semibold text-white transition hover:bg-white/10"
                                >
                                    Se connecter
                                </a>
                            </div>
                        </div>
                    </section>
                </main>

                <footer className="border-t border-slate-100 bg-white">
                    <div className="mx-auto flex max-w-7xl flex-col gap-8 px-5 py-10 sm:px-8 md:flex-row md:items-center md:justify-between">
                        <div>
                            <Brand />
                            <p className="mt-3 max-w-sm text-sm leading-6 text-slate-500">
                                Des paiements plus simples pour votre activité.
                            </p>
                        </div>
                        <nav
                            aria-label="Liens de pied de page"
                            className="flex flex-wrap gap-x-6 gap-y-3 text-sm font-medium text-slate-600"
                        >
                            <a href="#fonctionnalites" className="hover:text-blue-700">
                                Fonctionnalités
                            </a>
                            <a href="/api/documentation" className="hover:text-blue-700">
                                Documentation API
                            </a>
                        </nav>
                        <p className="text-sm text-slate-500">
                            © {new Date().getFullYear()} ZiloPay. Tous droits réservés.
                        </p>
                    </div>
                </footer>
            </div>
        </>
    )
}
