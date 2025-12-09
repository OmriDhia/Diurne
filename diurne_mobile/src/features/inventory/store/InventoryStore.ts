import { create } from 'zustand';
import { InventoryService, ScannedItem } from '../services/InventoryService';

interface InventoryState {
    currentLocationId: number | null;
    scannedItems: ScannedItem[];
    isScanning: boolean;

    setLocation: (id: number) => void;
    addItem: (rn: string) => Promise<void>;
    removeItem: (rn: string) => void;
    resetSession: () => void;
    saveSession: () => Promise<void>;
}

export const useInventoryStore = create<InventoryState>((set, get) => ({
    currentLocationId: null,
    scannedItems: [],
    isScanning: false,

    setLocation: (id: number) => set({ currentLocationId: id }),

    addItem: async (rn: string) => {
        const { scannedItems } = get();
        // Avoid duplicates
        if (scannedItems.find(i => i.rn === rn)) return; // Or alert user

        // Check local DB
        const exists = await InventoryService.checkItemExistence(rn);

        const newItem: ScannedItem = {
            rn,
            status: exists ? 'FOUND' : 'UNKNOWN',
            timestamp: Date.now(),
        };

        set({ scannedItems: [newItem, ...scannedItems] });
    },

    removeItem: (rn: string) => {
        set(state => ({
            scannedItems: state.scannedItems.filter(i => i.rn !== rn)
        }));
    },

    resetSession: () => set({ currentLocationId: null, scannedItems: [] }),

    saveSession: async () => {
        const { currentLocationId, scannedItems } = get();
        if (!currentLocationId || scannedItems.length === 0) return;

        await InventoryService.saveInventorySession(currentLocationId, scannedItems);
        set({ currentLocationId: null, scannedItems: [] }); // Reset after save
    }
}));
