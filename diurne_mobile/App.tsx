import React from 'react';
import { Provider as PaperProvider } from 'react-native-paper';
import { NavigationContainer } from '@react-navigation/native';
import { DiurneTheme } from './src/core/theme/DiurneTheme';
import { RootNavigator } from './src/navigation/RootNavigator';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import { StatusBar } from 'expo-status-bar';
import { initDatabase } from './src/core/database/Database';
import { View, ActivityIndicator } from 'react-native';
import { ErrorBoundary } from './src/components/ErrorBoundary';
import './src/core/GlobalHandlers'; // Import global handlers for side effects

const queryClient = new QueryClient();

// Separate component for the app logic to ensure ErrorBoundary wraps it all
function MainApp() {
  const [isDbInitialized, setIsDbInitialized] = React.useState(false);
  const [initError, setInitError] = React.useState<Error | null>(null);

  React.useEffect(() => {
    initDatabase()
      .then(() => {
        setIsDbInitialized(true);
      })
      .catch(error => {
        setInitError(error instanceof Error ? error : new Error(String(error)));
      });
  }, []);

  if (initError) {
    // Throwing here will be caught by ErrorBoundary
    throw initError;
  }

  if (!isDbInitialized) {
    return (
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
        <ActivityIndicator size="large" />
      </View>
    );
  }

  return (
    <QueryClientProvider client={queryClient}>
      <PaperProvider theme={DiurneTheme}>
        <NavigationContainer>
          <StatusBar style="auto" />
          <RootNavigator />
        </NavigationContainer>
      </PaperProvider>
    </QueryClientProvider>
  );
}

export default function App() {
  return (
    <SafeAreaProvider>
      <ErrorBoundary>
        <MainApp />
      </ErrorBoundary>
    </SafeAreaProvider>
  );
}
