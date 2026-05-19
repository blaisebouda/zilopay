import api from "@/lib/api";
import { useEffect, useCallback } from "react";
import { useFetch } from "./use-fetch";

export function useGet<T>(url: string, auto = true) {
  const { result, loading, error, execute } = useFetch<T>();

  const fetchData = useCallback(
    () =>
      execute(async () => {
        const res = await api.get<T>(url);
        return res.data;
      }),
    [url, execute],
  );

  useEffect(() => {
    if (auto) fetchData();
  }, [auto, fetchData]);

  return { result, loading, error, refetch: fetchData };
}
