import { FacebookIcon, InstagramIcon, LinkedInIcon } from "./icons";

const LINK_FACEBOOK = "#";
const LINK_INSTAGRAM = "#";
const LINK_LINKEDIN = "#";


export default function Footer() {
    const currentYear = new Date().getFullYear();

    return (
        <footer className="mt-8 bg-primary font-sans" role="contentinfo">
            <div className="text-white w-full  mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                <div className="grid grid-cols-1 items-center lg:grid-cols-3 gap-10">
                    <div className="space-y-1">
                        <img className="h-8 w-auto" src="/images/logo-white.png" alt="ZiloPay Logo" />
                        <p className="text-white/80 text-sm ml-2 leading-relaxed">
                            Des paiements plus simples pour votre activité.
                        </p>
                    </div>
                    <div className="">
                        <p className=" font-medium text-white/80">
                            © {currentYear} ZiloPay. Tous droits reserves.
                        </p>
                    </div>

                    {/* <div className="space-y-3">
                        <p className="text-lg font-semibold">Navigation</p>
                        <ul className="space-y-2 text-white/80 [&_li:hover]:text-foreground [&_li:hover]:font-medium transition-all duration-300">
                            <li>
                                <a href="#why-LINK">Pourquoi LINK</a>
                            </li>
                            <li>
                                <a href="#features">Fonctionnalites</a>
                            </li>


                            <li>
                                <a href="/policy">Conditions d'Utilisation</a>
                            </li>
                        </ul>
                    </div> */}


                    <div
                        className="flex gap-4"
                        role="group"
                        aria-label="Liens vers les reseaux sociaux"
                    >
                        <SocialLink
                            link={LINK_FACEBOOK}
                            ariaLabel="ZiloPay Facebook "
                        >
                            <FacebookIcon />
                        </SocialLink>

                        <SocialLink
                            link={LINK_INSTAGRAM}
                            ariaLabel="ZiloPay Instagram "
                        >
                            <InstagramIcon />
                        </SocialLink>
                        <SocialLink
                            link={LINK_LINKEDIN}
                            ariaLabel="ZiloPay LinkedIn "
                        >
                            <LinkedInIcon />
                        </SocialLink>
                    </div>
                    {/* <p className=" text-white/80">
                            Contact:{" "}
                            <a
                                className="hover:text-foreground hover:text-medium transition-colors"
                                href={`mailto:${LINK_EMAIL}`}
                            >
                                {LINK_EMAIL}
                            </a>
                        </p> */}

                </div>

            </div>
        </footer>
    );
}

function SocialLink({
    link = "#",
    ariaLabel = "",
    children,
}: {
    link: string;
    ariaLabel: string;
    children: React.ReactNode;
}) {
    return (
        <a
            className="p-2 rounded-full bg-white/10 hover:bg-white/20 transition-colors"
            href={link}
            target="_blank"
            rel="noopener noreferrer"
            aria-label={ariaLabel}
        >
            {children}
        </a>
    );
}