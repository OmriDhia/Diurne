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

import { ProfileScreen } from '../features/auth/screens/ProfileScreen';
import { ProgressReportScreen } from '../features/workshops/screens/ProgressReportScreen';
import { PhotoUploadScreen } from '../features/photos/screens/PhotoUploadScreen';
import { ParametersMenuScreen } from '../features/admin/screens/ParametersMenuScreen';
import { AdminUserScreen } from '../features/admin/screens/AdminUserScreen';
import { AdminWorkshopScreen } from '../features/admin/screens/AdminWorkshopScreen';
import { AdminTypeScreen } from '../features/admin/screens/AdminTypeScreen';

import { IconButton } from 'react-native-paper';
import { Image, TouchableOpacity } from 'react-native';
import { useNavigation } from '@react-navigation/native';

export const RootNavigator = () => {
  const { token, restoreToken, isLoading } = useAuthStore();
  const navigation = useNavigation<any>();

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
      {token ? (
        <>
          <Stack.Screen
            name="Dashboard"
            component={DashboardScreen}
            options={{
              headerTitle: '',
              headerLeft: () => (
                <Image
                  source={require('../assets/logo.png')}
                  style={{ width: 120, height: 40, marginLeft: 10, tintColor: 'black' }}
                  resizeMode="contain"
                />
              ),
              headerRight: () => (
                <TouchableOpacity onPress={() => navigation.navigate('Profile')}>
                  <IconButton icon="account-circle" size={30} />
                </TouchableOpacity>
              )
            }}
          />
          <Stack.Screen name="Inventory" component={InventoryScreen} />
          <Stack.Screen name="StockMovement" component={StockMovementScreen} />
          <Stack.Screen name="ProductSearch" component={ProductSearchScreen} />
          <Stack.Screen name="ProgressReport" component={ProgressReportScreen} />
          <Stack.Screen name="PhotoUpload" component={PhotoUploadScreen} />
          <Stack.Screen name="Parameters" component={ParametersMenuScreen} />

          <Stack.Screen name="AdminUser" component={AdminUserScreen} />
          <Stack.Screen name="AdminWorkshop" component={AdminWorkshopScreen} />
          <Stack.Screen name="AdminType" component={AdminTypeScreen} />

          <Stack.Screen name="Profile" component={ProfileScreen} options={{ presentation: 'modal', title: 'Mon Profil' }} />
        </>
      ) : (
        <Stack.Screen name="Login" component={LoginScreen} options={{ headerShown: false }} />
      )}
    </Stack.Navigator>
  );
};
