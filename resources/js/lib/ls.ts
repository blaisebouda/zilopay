interface LSData {
  wallet: Wallet | null;
  user: User | null;
}

// === Helpers pour le typage fort ===
type LSKey = keyof LSData;
type LSValue<K extends LSKey = LSKey> = LSData[K];

class LS {
  static get<K extends LSKey>(key: K): LSValue<K> | null {
    const item = localStorage.getItem(key);
    return item ? JSON.parse(item) : null;
  }

  static set<K extends LSKey>(key: K, value: LSValue<K>) {
    localStorage.setItem(key, JSON.stringify(value));
  }

  static remove(key: string) {
    localStorage.removeItem(key);
  }
}

export default LS;
