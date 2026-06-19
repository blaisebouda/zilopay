import { Button } from "@/components/ui/button";
import { Loader } from "lucide-react";

type LoadingButtonProps = {
  loading?: boolean;
  loadingLabel?: string;
  children: React.ReactNode;
};

export function LoadingButton({
  loading,
  loadingLabel = "Traitement...",
  children,
  ...props
}: LoadingButtonProps & React.ComponentProps<typeof Button>) {
  return (
    <Button disabled={loading} {...props}>
      {loading ? (
        <>
          <Loader /> {loadingLabel}
        </>
      ) : (
        children
      )}
    </Button>
  );
}
