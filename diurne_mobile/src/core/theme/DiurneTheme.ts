import { MD3LightTheme, configureFonts } from 'react-native-paper';

// Diurne Custom Palette
// Primary: Deep Charcoal / Black for premium feel
// Accent: Gold (e.g., #C5A065)
// Background: Soft White / Paper

export const DiurneTheme = {
    ...MD3LightTheme,
    colors: {
        ...MD3LightTheme.colors,
        primary: '#1E1E1E',   // Dark Gray/Black
        onPrimary: '#FFFFFF',
        secondary: '#C5A065', // Gold
        onSecondary: '#1E1E1E',
        tertiary: '#8E8E93',  // Muted Gray
        background: '#F9F9F9',
        surface: '#FFFFFF',
        onSurface: '#1E1E1E',
        error: '#B00020',
    },
    // We can customize fonts here if needed, keeping default for now
    // fonts: configureFonts({config: ...}),
};
