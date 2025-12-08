import React, { useEffect } from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { LoginScreen } from '../features/auth/screens/LoginScreen';
import { DashboardScreen } from '../features/dashboard/screens/DashboardScreen';
import { InventoryScreen } from '../features/inventory/screens/InventoryScreen';
import { StockMovementScreen } from '../features/workshops/screens/StockMovementScreen';
import { ProductSearchScreen } from '../features/products/screens/ProductSearchScreen';
import { useAuthStore } from '../features/auth/store/AuthStore';
import { View, ActivityIndicator } from 'react-native';

const Stack = createNativeStackNavigator();

export const RootNavigator = () => {
  const { token, restoreToken, isLoading } = useAuthStore();

  useEffect(() => {
    restoreToken();
  }, []);

  if (isLoading) {
    return (
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
        <ActivityIndicator size="large" />
      </View>
    );
  }

  return (
    <Stack.Navigator>
      {token == null ? (
        <Stack.Screen name="Login" component={LoginScreen} options={{ headerShown: false }} />
      ) : (
        <>
          <Stack.Screen name="Main" component={DashboardScreen} options={{ title: 'Menu Principal' }} />
          <Stack.Screen name="Inventory" component={InventoryScreen} />
          <Stack.Screen name="StockIn" component={StockMovementScreen} initialParams={{ type: 'IN' }} />
          <Stack.Screen name="StockOut" component={StockMovementScreen} initialParams={{ type: 'OUT' }} />
          <Stack.Screen name="Search" component={ProductSearchScreen} />
        </>
      )}
    </Stack.Navigator>
  );
};
