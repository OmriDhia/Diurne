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
      CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        role TEXT NOT NULL
      );
      CREATE TABLE IF NOT EXISTS workshops (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        rn_tapis TEXT,
        rn_ech TEXT
      );
      CREATE TABLE IF NOT EXISTS pr_statuses (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        label TEXT UNIQUE NOT NULL
      );
      CREATE TABLE IF NOT EXISTS photo_types (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        label TEXT UNIQUE NOT NULL
      );

      INSERT OR IGNORE INTO pr_statuses (label) VALUES ('Préparation'), ('Tissage'), ('Finition'), ('Envoi');
      INSERT OR IGNORE INTO photo_types (label) VALUES ('Production'), ('Finition'), ('Drone'), ('Vignette'), ('Détail');
      INSERT OR IGNORE INTO users (email, role) VALUES ('admin@diurne.com', 'ADMIN'), ('atelier@diurne.com', 'WORKSHOP');
    `);
    console.log('Database initialized successfully');
  } catch (error) {
    console.error('Database initialization failed', error);
  }
};

export const getDatabase = () => db;
