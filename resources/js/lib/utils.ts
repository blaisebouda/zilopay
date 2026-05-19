import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function formatNumberWithCurrency(num: number, currency = "XOF") {
    return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: currency,
    }).format(num);
}

export function extractFirstLetter(word: string): string {
    return word.charAt(0).toUpperCase();
}

export function formatInitials(fullName: string, maxInitials = 2): string {
    if (!fullName?.trim()) {
        return "";
    }

    const words = fullName.trim().split(/\s+/);
    const initialsToTake = Math.min(words.length, maxInitials);

    return words.slice(0, initialsToTake).map(extractFirstLetter).join("");
}

export function debounce<T>(func: (...args: T[]) => void, delay = 500) {
    let debounceTimer: ReturnType<typeof setTimeout> | null = null;

    return (...args: T[]) => {
        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }

        debounceTimer = setTimeout(() => {
            func(...args);
        }, delay);
    };
}

export function Endpoint(baseUrl: string) {
    const from = (path: string | Array<string | number>) => {
        if (Array.isArray(path)) {
            return `${baseUrl}/${path.join("/")}`;
        }
        return `${baseUrl}/${path}`;
    };
    return {
        base: baseUrl,
        from,
    };
}

export function wait(ms: number): Promise<void> {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

export function waitFor(
    conditionFunc: () => boolean,
    interval = 100,
    timeout = 5000,
): Promise<void> {
    return new Promise((resolve, reject) => {
        const startTime = Date.now();
        const intervalId = setInterval(() => {
            if (conditionFunc()) {
                clearInterval(intervalId);
                resolve();
            } else if (Date.now() - startTime > timeout) {
                clearInterval(intervalId);
                reject(new Error("Timeout exceeded"));
            }
        }, interval);
    });
}

export function getIdentifer(user: User): string {
    return user?.email || user?.phone_number || "";
}

export function int(value?: string): number {
    return value ? parseInt(value) : 0;
}
