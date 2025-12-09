import { getDatabase } from '../../../core/database/Database'; // Assuming Database export
import { addToQueue } from '../../../core/offline/SyncQueue';

export interface ScannedItem {
    rn: string;
    status: 'FOUND' | 'UNKNOWN' | 'ERROR';
    timestamp: number;
}

export const InventoryService = {
    async checkItemExistence(rn: string): Promise<boolean> {
        const db = getDatabase();
        // Check if item exists in local cache
        const result = await db.getFirstAsync('SELECT id FROM carpets WHERE rn = ?', [rn]);
        return !!result;
    },

    async saveInventorySession(locationId: number, items: ScannedItem[]) {
        // Save the entire session to the sync queue
        const payload = {
            location_id: locationId,
            items: items.map(i => ({ rn: i.rn, status: i.status })),
            date: new Date().toISOString(),
        };

        await addToQueue('INVENTORY_SESSION', payload);
    }
};
