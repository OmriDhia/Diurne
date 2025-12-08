// Use Vite environment variables for the API / file host when available.
// Vite exposes variables prefixed with VITE_ via import.meta.env at build time.
const DEFAULT_LOCAL_API = 'http://localhost:8741';

export const API_URL = import.meta.env.VITE_API_URL || DEFAULT_LOCAL_API;

// FILE_URL is the base used to build absolute paths to uploaded attachments/public assets.
// If VITE_FILE_URL is not provided, fall back to API_URL.
export const FILE_URL = import.meta.env.VITE_FILE_URL || API_URL;

export const TINYMCE_API_KEY = import.meta.env.VITE_TINYMCE_API_KEY || '7e991iyw49f6bj4vd4b48zzmyck6nryo9rl5yg8uwhoi4wz7';
