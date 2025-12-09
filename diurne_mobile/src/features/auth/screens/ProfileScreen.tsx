import React from 'react';
import { View, StyleSheet } from 'react-native';
import { Button, Text, Avatar, List, Divider } from 'react-native-paper';
import { useAuthStore } from '../store/AuthStore';

export const ProfileScreen = () => {
    const { user, signOut } = useAuthStore();

    return (
        <View style={styles.container}>
            <View style={styles.header}>
                <Avatar.Text size={80} label={user?.email?.substring(0, 2).toUpperCase() || 'US'} />
                <Text variant="headlineMedium" style={styles.name}>{user?.name || 'Utilisateur'}</Text>
                <Text variant="bodyMedium" style={styles.email}>{user?.email}</Text>
            </View>

            <Divider style={styles.divider} />

            <List.Section>
                <List.Subheader>Compte</List.Subheader>
                <List.Item title="Paramètres" left={() => <List.Icon icon="cog" />} />
                <List.Item title="Aide & Support" left={() => <List.Icon icon="help-circle" />} />
            </List.Section>

            <View style={styles.footer}>
                <Button mode="contained" buttonColor="#FF5252" onPress={signOut} icon="logout">
                    Se déconnecter
                </Button>
            </View>
        </View>
    );
};

const styles = StyleSheet.create({
    container: { flex: 1, backgroundColor: 'white' },
    header: { alignItems: 'center', padding: 30 },
    name: { marginTop: 15, fontWeight: 'bold' },
    email: { color: '#666' },
    divider: { marginVertical: 10 },
    footer: { padding: 20, marginTop: 'auto' }
});
