import { Link } from "@inertiajs/react";
import { ChevronRight } from "lucide-react";

type SectionTitleProps = {
    description: string;
    children: React.ReactNode;
};

export function SectionTitle({
    description,
    children,
}: SectionTitleProps) {
    return (
        <div className=" mx-auto flex flex-col gap-2 ">
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
                className={`relative max-w-[1040px] mx-auto ${containerClassName}`}
            >
                {children}
            </div>
        </section>
    );
}

export function CtbBtn({ title, href }: { title: string; href: string }) {
    return (
        <Link href={href} >
            <button
                className=" group flex items-center gap-2 rounded-full bg-linear-to-r from-primary to-[#008CFF] px-4 py-3 text-white border border-primary shadow-[4px_4px_8px_0px_rgba(0,0,0,0.15)]"
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
