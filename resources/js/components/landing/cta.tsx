import { ChevronRight } from "lucide-react";
import { Reveal } from "../ui/landing";

export default function CTA() {
    return (
        <section id="cta" className="bg-[url('/images/cta-bg.jpg')] bg-cover bg-center text-white mt-8">
            <Reveal>
                <div className="mx-auto flex max-w-[1040px]  flex-col items-center gap-6 px-4 py-12 text-center md:py-30">
                    <h1 className=" text-3xl md:text-5xl font-serif">Passez à une gestion plus simple de vos paiements</h1>
                    <p className="text-lg max-w-[600px]">Commencez à créer vos liens de paiement et découvrez les outils ZiloPay pour suivrepvos transactions.</p>
                    <div className="flex flex-col gap-4 md:flex-row w-full md:w-max">
                        <a href="/register">
                            <button
                                className="w-full justify-center group flex items-center gap-2 rounded-full bg-white px-4 py-3 text-white  shadow-[4px_4px_8px_0px_rgba(0,0,0,0.15)]"
                            >
                                <span className="font-semibold text-black">Creer mon compte</span>
                                <span
                                    className="flex size-5 items-center justify-center rounded-full bg-white transition-transform duration-200 ease-in-out group-hover:translate-x-0.5"
                                >
                                    <ChevronRight className="h-4 w-4 text-black" />
                                </span>
                            </button>
                        </a>
                        <a href="/login">
                            <button
                                className="w-full justify-center group flex items-center gap-2 rounded-full bg-white/20 px-4 py-3 text-white border border-white shadow-[4px_4px_8px_0px_rgba(0,0,0,0.15)]"
                            >
                                <span className="font-semibold text-white"> Se connecter</span>
                                <span
                                    className="flex size-5 items-center justify-center rounded-full bg-white transition-transform duration-200 ease-in-out group-hover:translate-x-0.5"
                                >
                                    <ChevronRight className="h-4 w-4 text-black" />
                                </span>
                            </button>
                        </a>
                    </div>
                </div>
            </Reveal>

        </section>
    )
}