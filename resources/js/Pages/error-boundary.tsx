import { Button } from "@/components/ui/button";
import { Link, usePage } from "@inertiajs/react";
import { ArrowLeft } from "lucide-react";

const NotFound = () => {
  return (
    <div className="grid min-h-screen w-full xl:grid-cols-2">
      <div className="flex flex-col p-16">
        {/* Logo */}
        <div className="mb-8 flex items-center justify-center gap-2 xl:justify-start">
          <img
            src="/logo.jpeg"
            className="size-8 rounded-lg border"
            alt="ZiloPay"
          />

          <span className="text-xl font-bold text-primary">ZiloPay</span>
        </div>

        <div className="mt-8 flex flex-1 flex-col items-center justify-center text-center xl:items-start xl:text-start">
          <div className="mb-3 flex items-center gap-3">
            <span className="font-semibold">404</span>
          </div>
          <h1 className="mb-2 text-4xl font-bold">Page non trouvée</h1>
          <p>Oops! La page que vous essayez d'accéder n'existe pas.</p>
          <Link href="/">
            <Button className="h-9 px-4 py-2 mt-8 cursor-pointer">
              <ArrowLeft />
              <span>Retour à l'accueil</span>
            </Button>
          </Link>
        </div>
      </div>
    </div>
  );
};

export default function ErrorBoundary() {
  const page = usePage()

  if (page.props.status === 404) {
    return <NotFound />;
  }

  return (
    <div className="grid min-h-screen w-full xl:grid-cols-2">
      <h1>Oops! Une erreur est survenue</h1>
      {/* <p>{page.props.statusText || page.props.message}</p> */}
      <Link href="/">Retour à l'accueil</Link>
    </div>
  );
}
