// État du loading
export type LoadingState = "idle" | "loading" | "success" | "error";

// Réponse standardisée de l'API
export interface ApiResponse<T> {
  data: T | null;
  error: ApiError | null;
  loading: boolean;
  loadingState: LoadingState;
}

// Structure d'erreur
export interface ApiError {
  message: string;
  code?: number | string;
  details?: unknown;
  response?: {
    message?: string;
    errors?: string[];
  };
}

// Options pour les hooks
export interface UseFetchOptions<TData> {
  immediate?: boolean; // Exécuter immédiatement au montage
  initialData?: TData | null; // Données initiales
  onSuccess?: (data: TData) => void;
  onError?: (error: ApiError) => void;
  transform?: (data: unknown) => TData; // Transformer la réponse
}

// Configuration de la requête
export interface RequestConfig<TParams = unknown> {
  params?: TParams;
  headers?: Record<string, string>;
  signal?: AbortSignal;
}
