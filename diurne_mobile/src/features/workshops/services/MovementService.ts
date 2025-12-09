import { addToQueue } from '../../../core/offline/SyncQueue';
import { getDatabase } from '../../../core/database/Database';

export const MovementService = {
    async moveStock(rn: string, toLocationId: number | string, type: 'IN' | 'OUT' | 'MOVE') {
        // 1. Optimistic Update (Local DB)
        const db = getDatabase();
        try {
            // Update local cache if item exists
            await db.runAsync(
                'UPDATE carpets SET location_id = ?, updated_at = ? WHERE rn = ?',
                [toLocationId, new Date().toISOString(), rn]
            );
        } catch (e) {
            console.warn('Local update failed, maybe item not in local DB', e);
        }

        // 2. Queue Action
        const payload = {
            rn,
            to_location_id: toLocationId,
            type,
            date: new Date().toISOString(),
        };

        await addToQueue('MOVE_STOCK', payload);
    }
};
