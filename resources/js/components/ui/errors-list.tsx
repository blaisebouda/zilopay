import { Alert, AlertDescription, AlertTitle } from "./alert";

export function ErrorsList({
  title,
  errors,
}: {
  title?: string;
  errors?: string[];
}) {
  return (
    <Alert className="mb-2" variant="destructive">
      {title && <AlertTitle>{title}</AlertTitle>}
      <AlertDescription>
        <ul>
          {errors?.map((error, index) => (
            <li key={index}>{error}</li>
          ))}
        </ul>
      </AlertDescription>
    </Alert>
  );
}

export function Error({ title }: { title?: string }) {
  return (
    <Alert className="mb-2" variant="destructive">
      <AlertTitle>{title || "Une erreur est survenue"}</AlertTitle>
    </Alert>
  );
}
