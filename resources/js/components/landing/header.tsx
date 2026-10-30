import { useState } from "react";
import { CtbBtn } from "../ui/landing";

export default function Header() {
    const [isMenuOpen, setIsMenuOpen] = useState(false);

    const toggleMenu = () => setIsMenuOpen(!isMenuOpen);
    const closeMenu = () => setIsMenuOpen(false);
    return (
        <header
            className="fixed z-50 top-0 left-0 right-0 p-2"
            role="banner"
            aria-label="En-tête de page ZiloPay"
        >
            <div className="bg-white/60 backdrop-blur-sm rounded-full px-2 w-full max-w-5xl mx-auto h-[60px] flex items-center justify-between font-sans">
                <a
                    className="flex items-center gap-2 pl-2"
                    href="#"
                    aria-label="Accueil ZiloPay"
                >
                    <img
                        src="/images/logo.png"

                        width={150}
                        alt="ZiloPay"
                    />
                </a>
                <nav
                    className="hidden md:block"
                    role="navigation"
                    aria-label="Navigation principale"
                >
                    <ul className="flex gap-4 lg:gap-6 [&_li:hover]:text-foreground font-medium transition-all duration-300">
                        <li className="flex items-center gap-2  transition duration-300 ease-in-out">

                            <a
                                className="font-bold"
                                href="#"
                                aria-current="page"
                            >
                                Accueil
                            </a>
                        </li>

                        <li className="flex items-center gap-2 transition text-muted-foreground  duration-300 ease-in-out">
                            <span
                                className="w-[6px] h-[6px] rounded-full bg-primary hidden"
                                aria-hidden="true"
                            ></span>
                            <a href="#features">Fonctionnalités</a>
                        </li>
                        <li className="flex items-center gap-2 transition text-muted-foreground  duration-300 ease-in-out">
                            <span
                                className="w-[6px] h-[6px] rounded-full bg-primary hidden"
                                aria-hidden="true"
                            ></span>
                            <a href="#how-it-works">Comment ça marche ?</a>
                        </li>

                        <li className="flex items-center gap-2 transition text-muted-foreground  duration-300 ease-in-out">
                            <span
                                className="w-[6px] h-[6px] rounded-full bg-primary hidden"
                                aria-hidden="true"
                            ></span>
                            <a href="#faq">FAQ</a>
                        </li>
                    </ul>
                </nav>
                <div className="flex items-center gap-2">
                    <a href="#cta">
                        <CtbBtn
                            title="Se connecter"
                            href="/login"
                            aria-label="Se connecter à ZiloPay"
                        />
                    </a>
                    <button
                        className="md:hidden p-2 rounded-full hover:bg-white/20 transition-colors"
                        aria-label={
                            isMenuOpen ? "Fermer le menu" : "Ouvrir le menu"
                        }
                        aria-expanded={isMenuOpen}
                        aria-controls="mobile-menu"
                        onClick={toggleMenu}
                    >
                        <svg
                            className="w-6 h-6 text-foreground"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            {isMenuOpen ? (
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            ) : (
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            )}
                        </svg>
                    </button>

                    <div
                        id="mobile-menu"
                        className={`absolute top-full right-0 mt-2 w-[200px] bg-white/95 backdrop-blur-md rounded-2xl shadow-lg border border-white/20 overflow-hidden transition-all duration-300 ease-out md:hidden ${isMenuOpen
                            ? "opacity-100 translate-y-0 pointer-events-auto"
                            : "opacity-0 -translate-y-2 pointer-events-none"
                            }`}
                        role="navigation"
                        aria-label="Navigation mobile"
                    >
                        <ul className="flex flex-col p-2">
                            <li>
                                <a
                                    className="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 bg-primary/10 text-foreground font-bold"
                                    href="#"
                                    aria-current="page"
                                    onClick={closeMenu}
                                >
                                    <span
                                        className="w-2 h-2 rounded-full bg-primary block"
                                        aria-hidden="true"
                                    ></span>
                                    Accueil
                                </a>
                            </li>

                            <li>
                                <a
                                    className="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-muted-foreground  hover:bg-white/50 hover:text-foreground"
                                    href="#features"
                                    onClick={closeMenu}
                                >
                                    <span
                                        className="w-2 h-2 rounded-full bg-primary hidden"
                                        aria-hidden="true"
                                    ></span>
                                    Fonctionnalités
                                </a>
                            </li>
                            <li>
                                <a
                                    className="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-muted-foreground  hover:bg-white/50 hover:text-foreground"
                                    href="#how-it-works"
                                    onClick={closeMenu}
                                >
                                    <span
                                        className="w-2 h-2 rounded-full bg-primary hidden"
                                        aria-hidden="true"
                                    ></span>
                                    Comment ça marche ?
                                </a>
                            </li>
                            <li>
                                <a
                                    className="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-muted-foreground  hover:bg-white/50 hover:text-foreground"
                                    href="#faq"
                                    onClick={closeMenu}
                                >
                                    <span
                                        className="w-2 h-2 rounded-full bg-primary hidden"
                                        aria-hidden="true"
                                    ></span>
                                    FAQ
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            {isMenuOpen && (
                <div
                    className="fixed inset-0 bg-black/20 backdrop-blur-sm z-[-1] sm:hidden"
                    onClick={closeMenu}
                    aria-hidden="true"
                ></div>
            )}
        </header>
    );
}