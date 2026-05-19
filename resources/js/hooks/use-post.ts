import api from "@/lib/api";
import { useFetch } from "./use-fetch";

/**
 * Hook POST
 */
export function usePost<T, B = unknown>(url: string) {
  const { result, loading, error, execute } = useFetch<T>();

  const post = (body: B) =>
    execute(async () => {
      const res = await api.post<T>(url, body);
      return res.data;
    });

  return { result, loading, error, post };
}
