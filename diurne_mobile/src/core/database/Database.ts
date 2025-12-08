import * as SQLite from 'expo-sqlite';

const db = SQLite.openDatabaseSync('diurne.db');

export const initDatabase = async () => {
    try {
        await db.execAsync(`
      PRAGMA journal_mode = WAL;
      CREATE TABLE IF NOT EXISTS carpets (
        id INTEGER PRIMARY KEY NOT NULL,
        rn TEXT UNIQUE NOT NULL,
        reference TEXT,
        status TEXT,
        location_id INTEGER,
        updated_at TEXT,
        json_blob TEXT
      );
      CREATE TABLE IF NOT EXISTS locations (
        id INTEGER PRIMARY KEY NOT NULL,
        name TEXT,
        type TEXT
      );
      CREATE TABLE IF NOT EXISTS action_queue (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        action_type TEXT NOT NULL,
        payload TEXT NOT NULL,
        status TEXT DEFAULT 'PENDING',
        created_at TEXT DEFAULT CURRENT_TIMESTAMP,
        retry_count INTEGER DEFAULT 0
      );
    `);
        console.log('Database initialized successfully');
    } catch (error) {
        console.error('Database initialization failed', error);
    }
};

export const getDatabase = () => db;
