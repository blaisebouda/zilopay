import { create } from "zustand";

// === Le Global type ===
interface Global {
  wallet: Wallet | null;
  user: User | null;
}

// === Helpers pour le typage fort ===
type GlobalKey = keyof Global;
type GlobalValue<K extends GlobalKey = GlobalKey> = Global[K];

// === Le Store ===
interface GlobalState {
  data: Global;

  // Set une clé spécifique avec type safety
  setData: <K extends GlobalKey>(key: K, value: GlobalValue<K>) => void;

  // Get une valeur typée
  getData: <K extends GlobalKey>(key: K) => GlobalValue<K>;

  // Clear tout ou une clé spécifique
  clear: () => void;
  clearKey: <K extends GlobalKey>(key: K) => void;

  // Reset à l'état initial
  reset: () => void;
}

// === État initial ===
const initialState: Global = {
  wallet: null,
  user: null,
};

// === Création du store ===
export const useGlobalStore = create<GlobalState>((set, get) => ({
  data: initialState,

  // Set une clé spécifique - 100% type safe
  setData: <K extends GlobalKey>(key: K, value: GlobalValue<K>) => {
    set((state) => ({
      data: {
        ...state.data,
        [key]: value,
      },
    }));
  },

  // Get avec type inference
  getData: <K extends GlobalKey>(key: K) => {
    return get().data[key];
  },

  // Clear tout
  clear: () => {
    set({ data: initialState });
  },

  // Clear une clé spécifique
  clearKey: <K extends GlobalKey>(key: K) => {
    set((state) => ({
      data: {
        ...state.data,
        [key]: initialState[key],
      },
    }));
  },

  // Reset = alias de clear
  reset: () => {
    set({ data: initialState });
  },
}));
