import { getDatabase } from '../database/Database';

export type QueueStatus = 'PENDING' | 'SYNCED' | 'ERROR';

export const addToQueue = async (actionType: string, payload: any) => {
    const db = getDatabase();
    const jsonPayload = JSON.stringify(payload);
    try {
        await db.runAsync(
            'INSERT INTO action_queue (action_type, payload, status) VALUES (?, ?, ?)',
            [actionType, jsonPayload, 'PENDING']
        );
        console.log(`Action ${actionType} added to queue`);
    } catch (error) {
        console.error('Failed to add to queue', error);
    }
};

export const getPendingActions = async () => {
    const db = getDatabase();
    return await db.getAllAsync('SELECT * FROM action_queue WHERE status = ?', ['PENDING']);
};
