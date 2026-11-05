import { Link } from "@inertiajs/react";
import { ChevronRight, ChevronDown } from "lucide-react";
import React, { useRef, useEffect, useState, type ReactNode } from "react";
import { } from "lucide-react";


type SectionTitleProps = {
    description: string;
    children: React.ReactNode;
};

export function SectionTitle({
    description,
    children,
}: SectionTitleProps) {
    return (
        <div className="w-full  flex flex-col gap-2 ">
            <p
                className=" font-sans text-muted-foreground"
            >
                {description}
            </p>

            <h1
                className="font-serif text-foreground  text-4xl"
            >
                {children}
            </h1>

        </div>
    );
}

export function Section({
    children,
    id,
    className,
    containerClassName,
}: {
    children: React.ReactNode;
    id: string;
    bg?: string;
    className?: string;
    containerClassName?: string;
}) {
    return (
        <section
            id={id}
            className={`px-4 pt-12 md:pt-30 pb-10 md:pb-30 bg-cover bg-center ${className}`}
        >
            <div
                className={`relative w-full max-w-[1040px] mx-auto ${containerClassName}`}
            >
                {children}
            </div>
        </section>
    );
}

export function CtbBtn({ title, href, className = "" }: { title: string; href: string; className?: string }) {
    return (
        <Link href={href} aria-label={title}>
            <button
                className={`${className} justify-center group flex items-center gap-2 rounded-full bg-linear-to-r from-primary to-[#008CFF] px-2 py-1.5  md:px-4 md:py-3 text-white border border-primary shadow-[4px_4px_8px_0px_rgba(0,0,0,0.15)]`}
            >
                <span className="font-semibold"> {title}</span>
                <span
                    className="flex size-5 items-center justify-center rounded-full bg-white transition-transform duration-200 ease-in-out group-hover:translate-x-0.5"
                >
                    <ChevronRight className="h-4 w-4 text-black" />
                </span>
            </button>
        </Link >
    )
}

type FAQ = {
    question: string;
    answer: string;
};

type FaqAccordionProps = {
    faqs: FAQ[];
    className?: string;
};

export const FaqAccordions: React.FC<FaqAccordionProps> = ({ faqs, className }) => {
    const [openIndex, setOpenIndex] = useState<number | null>(null);

    const toggle = (index: number) => {
        setOpenIndex(openIndex === index ? null : index);
    };

    return (
        <div className={className}>
            {faqs.map((faq, index) => {
                const isOpen = openIndex === index;
                return (
                    <div
                        key={index}
                        className="text-sm lg:text-lg font-sans border p-4 bg-white rounded-xl transition-all duration-300"
                    >
                        <button
                            className=" w-full flex justify-between items-center text-left focus:outline-none"
                            onClick={() => toggle(index)}
                            aria-expanded={isOpen}
                        >
                            <span className=" font-semibold">{faq.question}</span>
                            <ChevronDown
                                className={`w-5 h-5 text-accent-foreground transform transition-transform duration-300 ${isOpen ? "rotate-180" : ""
                                    }`}
                            />
                        </button>

                        <div
                            className={`overflow-hidden transition-[max-height,opacity] duration-500 ease-in-out ${isOpen ? "max-h-40 opacity-100" : "max-h-0 opacity-0"
                                }`}
                        >
                            <p className="mt-3  text-accent-foreground  leading-relaxed">{faq.answer}</p>
                        </div>
                    </div>
                );
            })}
        </div>
    );
};




interface RevealProps {
    children: React.ReactNode;
    delay?: number;
    threshold?: number;
    className?: string;
}

export function Reveal({
    children,
    threshold = 0.5,
    className = "",
}: RevealProps) {
    const [isVisible, setIsVisible] = useState(false);
    const ref = useRef<HTMLDivElement>(null);

    useEffect(() => {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    setIsVisible(true);
                    // Optionnel: arrêter d'observer après l'animation
                    observer.unobserve(entry.target);
                }
            },
            { threshold }
        );

        if (ref.current) {
            observer.observe(ref.current);
        }

        return () => {
            if (ref.current) {
                observer.unobserve(ref.current);
            }
        };
    }, [threshold]);

    const visibleClass = isVisible ? "reveal-visible" : "";

    return (
        <div ref={ref} className={`reveal ${visibleClass} ${className}`.trim()}>
            {children}
        </div>
    );
}

