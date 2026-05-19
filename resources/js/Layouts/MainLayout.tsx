import { TooltipProvider } from "@/components/ui/tooltip";
import { Toaster } from "sonner";
import Footer from "./partial/footer";

export function MainLayout({ children }: { children: React.ReactNode }) {
    return (
        <>
            {/* main */}
            <main className="mx-auto size-full max-w-7xl px-4 py-6 sm:px-6">
                <TooltipProvider>
                    {children}
                    <Toaster />
                </TooltipProvider>
            </main>
            <Footer />
        </>
    );
}
