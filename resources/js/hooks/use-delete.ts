import api from "@/lib/api";
import { useFetch } from "./use-fetch";

/**
 * Hook DELETE
 */
export function useDelete<T>(url: string) {
  const { result, loading, error, execute } = useFetch<T>();

  const remove = () =>
    execute(async () => {
      const res = await api.delete<T>(url);
      return res.data;
    });

  return { result, loading, error, remove };
}
