import api from "@/lib/api";
import { useFetch } from "./use-fetch";

/**
 * Hook PUT
 */
export function usePut<T, B = unknown>(url: string) {
  const { result, loading, error, execute } = useFetch<T>();

  const put = (body: B) =>
    execute(async () => {
      const res = await api.put<T>(url, body);
      return res.data;
    });

  return { result, loading, error, put };
}
