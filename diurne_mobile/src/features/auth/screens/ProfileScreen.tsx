import { getDatabase } from '../../../core/database/Database';
import { Alert, ScrollView,View } from 'react-native';
import { useAuthStore } from '../store/AuthStore';
import { Avatar, Text } from 'react-native-paper';
import { List, Divider } from 'react-native-paper';
import { Button } from 'react-native-paper';
import { StyleSheet } from 'react-native';

export const ProfileScreen = () => {
    const { user, signOut } = useAuthStore();

    const checkLocalDB = async () => {
        try {
            const db = getDatabase();
            const users = await db.getAllAsync('SELECT * FROM users');
            Alert.alert('Local Database (Users)', JSON.stringify(users, null, 2));
            console.log('[DEBUG] Local Users:', JSON.stringify(users, null, 2));
        } catch (e) {
            Alert.alert('Error', 'Failed to read database');
        }
    };

    return (
        <ScrollView style={styles.container}>
            <View style={styles.header}>
                {user?.picture ? (
                    <Avatar.Image size={80} source={{ uri: user.picture }} />
                ) : (
                    <Avatar.Text 
                        size={80} 
                        label={user?.name ? user.name.charAt(0).toUpperCase() : 'U'} 
                    />
                )}
                <Text variant="headlineMedium" style={styles.name}>{user?.name || 'Utilisateur'}</Text>
                <Text variant="bodyMedium" style={styles.email}>{user?.email}</Text>
            </View>

            <Divider style={styles.divider} />

            <List.Section>
                <List.Subheader>Compte</List.Subheader>
                <List.Item title="Paramètres" left={() => <List.Icon icon="cog" />} />
                <List.Item title="Aide & Support" left={() => <List.Icon icon="help-circle" />} />
            </List.Section>

             <List.Section>
                <List.Subheader>Outils de Développement</List.Subheader>
                <List.Item 
                    title="Voir Données Locales (Debug)" 
                    description="Affiche le contenu de la table 'users'"
                    left={() => <List.Icon icon="database" />} 
                    onPress={checkLocalDB}
                />
            </List.Section>

            <View style={styles.footer}>
                <Button mode="contained" buttonColor="#FF5252" onPress={signOut} icon="logout">
                    Se déconnecter
                </Button>
            </View>
        </ScrollView>
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
