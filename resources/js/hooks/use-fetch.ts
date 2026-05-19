import type { ApiError } from "@/types/fetch";
import { AxiosError } from "axios";
import { useState, useCallback } from "react";

export interface FetchState<T> {
  result: T | null;
  loading: boolean;
  error: ApiError | null;
}

export function useFetch<T>() {
  const [result, setResult] = useState<T | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<ApiError | null>(null);

  const execute = useCallback(async (callback: () => Promise<T>) => {
    setLoading(true);
    setError(null);
    try {
      const response = (await callback()) as { data: T };
      setResult(response.data);
      return response.data;
    } catch (err) {
      if (err instanceof Error && err.name === "AbortError") return null;

      const isAxiosError = err instanceof AxiosError;
      const apiError: ApiError = {
        message: err instanceof Error ? err.message : "Une erreur est survenue",
        code: (err as { status?: number })?.status,
        details: err,
        response: {
          message: isAxiosError
            ? (err as AxiosError<{ message?: string }>).response?.data?.message
            : undefined,
          errors: isAxiosError
            ? flattenErrors(
                (err as AxiosError<{ errors?: Record<string, string[]> }>)
                  .response?.data?.errors,
              )
            : undefined,
        },
      };
      setError(apiError);
      throw apiError;
    } finally {
      setLoading(false);
    }
  }, []);

  return { result, loading, error, execute };
}

function flattenErrors(errors: Record<string, string[]> | undefined): string[] {
  if (!errors) return [];
  return Object.values(errors).flat();
}
