import React, { useState, useEffect } from 'react';
import { View, StyleSheet, FlatList, Alert } from 'react-native';
import { TextInput, Button, Card, Text, IconButton, Portal, Modal, SegmentedButtons } from 'react-native-paper';
import client from '../../../api/client'; // Import Client 
import { getAllUsers, saveUser } from '../../../core/database/Database'; // Import Database

export const AdminUserScreen = () => {
    const [query, setQuery] = useState('');
    const [visible, setVisible] = useState(false);
    const [editingId, setEditingId] = useState<number | null>(null);

    // Form State
    const [email, setEmail] = useState('');
    const [name, setName] = useState('');
    const [password, setPassword] = useState('');
    const [roleId, setRoleId] = useState(4); // Default to 'users' permission

    const [permissions, setPermissions] = useState<any[]>([]);

    const [apiUsers, setApiUsers] = useState<any[]>([]);
    const [localUsers, setLocalUsers] = useState<any[]>([]);

    useEffect(() => {
        loadPermissions();
        loadUsers();
    }, []);

    const loadPermissions = async () => {
        try {
            const response = await client.get('/mobile/permissions');
            const data = response.data.response || [];
            setPermissions(Array.isArray(data) ? data : []);
        } catch (e) {
            console.error('Error loading permissions', e);
            setPermissions([]);
        }
    };

    const [refreshing, setRefreshing] = useState(false);

    const onRefresh = React.useCallback(async () => {
        setRefreshing(true);
        await loadUsers();
        setRefreshing(false);
    }, []);

    const loadUsers = async () => {
        // Load API Users
        try {
            const response = await client.get('/mobile/users');
            const data = response.data.response || [];
            const userList = Array.isArray(data) ? data : [];
            console.log('[DEBUG] API Users Fetched:', userList.length);
            setApiUsers(userList);

            // Sync all fetched users to local DB
            for (const u of userList) {
                console.log('[DEBUG] Saving user:', u.email, u.id);
                await saveUser(u);
            }
        } catch (e) {
            console.error('API Load Failed', e);
            setApiUsers([]);
        }

        // Load Local Users (after sync)
        try {
            const local = await getAllUsers();
            setLocalUsers(local);
        } catch (e) {
             console.error('Local Load Failed', e);
        }
    };

    // Reload when query changes (only filters currently displayed list effectively, or re-fetches if logic changes)
    useEffect(() => { 
        // For local filtering we might need to filter 'localUsers' or 'apiUsers' in render
        loadUsers(); 
    }, [query]);
    // Derived state for display
    const displayedUsers = apiUsers.filter((u: any) => 
        (u.email.toLowerCase().includes(query.toLowerCase())) || (u.name.toLowerCase().includes(query.toLowerCase()))
    );

    const renderLocalUsers = () => (
        <View style={{ marginBottom: 20, padding: 10, backgroundColor: '#f0f0f0', borderRadius: 8 }}>
            <Text variant="titleMedium" style={{ marginBottom: 10 }}>Données Locales (SQLite - {localUsers.length})</Text>
            {localUsers.map((u: any, index: number) => (
                <Text key={index} variant="bodySmall" style={{ fontFamily: 'monospace' }}>
                     {u.id} | {u.name} | {u.email}
                </Text>
            ))}
            {localUsers.length === 0 && <Text variant="bodySmall">Aucune donnée locale.</Text>}
            <Text variant="titleMedium" style={{ marginTop: 15, marginBottom: 5 }}>Utilisateurs API (Online)</Text>
        </View>
    );

    const handleSave = async () => {
        // ... (Save logic remains focused on API for now, logic below)
        if (!email) return;
        try {
            if (editingId) {
                await client.put(`/mobile/users/${editingId}`, {
                    email,
                    name: name || 'Updated User',
                    permissionId: roleId,
                    isActive: true
                });
            } else {
                await client.post('/mobile/users', {
                    email,
                    name: name || 'New User',
                    password: password || '12345678',
                    permissionId: roleId,
                    isActive: true,
                    picture: null
                });
            }
            setVisible(false);
            loadUsers();
            resetForm();
        } catch (e) {
            console.error(e);
            Alert.alert('Erreur', 'Impossible de sauvegarder');
        }
    };

    const handleDelete = async (id: number) => {
        // ... (Delete logic)
        Alert.alert('Confirmer', 'Supprimer cet utilisateur ?', [
            { text: 'Annuler', style: 'cancel' },
            {
                text: 'Supprimer', style: 'destructive',
                onPress: async () => {
                    try {
                        await client.delete(`/mobile/users/${id}`);
                        loadUsers();
                    } catch (e) {
                        Alert.alert('Erreur', 'Impossible de supprimer');
                    }
                }
            }
        ]);
    };

    const openEdit = (user: any) => {
        setEditingId(user.id);
        setEmail(user.email);
        setName(user.name);
        setRoleId(user.permission?.id || user.permission_id || 4);
        setVisible(true);
    };

    const resetForm = () => {
        setEditingId(null);
        setEmail('');
        setName('');
        setPassword('');
        setRoleId(4);
    };

    return (
        <View style={styles.container}>
            <View style={styles.searchRow}>
                <TextInput
                    mode="outlined"
                    label="Rechercher"
                    style={styles.search}
                    value={query}
                    onChangeText={setQuery}
                    right={<TextInput.Icon icon="magnify" />}
                />
                <Button mode="contained" icon="plus" onPress={() => { resetForm(); setVisible(true); }}>Nouveau</Button>
            </View>

            <FlatList
                data={displayedUsers}
                refreshing={refreshing}
                onRefresh={onRefresh}
                ListHeaderComponent={renderLocalUsers}
                keyExtractor={(item: any) => (item.id || Math.random()).toString()}
                renderItem={({ item }: { item: any }) => (
                    <Card style={styles.card}>
                        <Card.Title
                            title={item.email}
                            subtitle={`Nom: ${item.name || 'N/A'} | Role: ${item.role || item.permission?.name || item.permission_id}`}
                            right={(props: any) => (
                                <View style={{ flexDirection: 'row' }}>
                                    <IconButton {...props} icon="pencil" onPress={() => openEdit(item)} />
                                    <IconButton {...props} icon="delete" iconColor="red" onPress={() => handleDelete(item.id)} />
                                </View>
                            )}
                        />
                    </Card>
                )}
            />

            <Portal>
                <Modal visible={visible} onDismiss={() => setVisible(false)} contentContainerStyle={styles.modal}>
                    <Text variant="headlineSmall" style={{ marginBottom: 15 }}>{editingId ? 'Modifier' : 'Nouvel'} Utilisateur</Text>

                    <TextInput label="Nom" value={name} onChangeText={setName} style={{ marginBottom: 10 }} />
                    <TextInput label="Email" value={email} onChangeText={setEmail} style={{ marginBottom: 10 }} autoCapitalize="none" keyboardType="email-address" />
                    {!editingId && (
                        <TextInput label="Mot de passe" value={password} onChangeText={setPassword} style={{ marginBottom: 15 }} secureTextEntry />
                    )}

                    <Text style={{ marginBottom: 5 }}>Rôle (Permission)</Text>
                    <SegmentedButtons
                        value={roleId.toString()}
                        onValueChange={(val: string) => setRoleId(parseInt(val))}
                        buttons={permissions.map((p: any) => ({
                            value: p.id.toString(),
                            label: p.name ? p.name.charAt(0).toUpperCase() + p.name.slice(1) : 'Role'
                        })).slice(0, 4)} 
                        style={{ marginBottom: 20 }}
                    />
                     
                    <Button mode="contained" onPress={handleSave}>Enregistrer</Button>
                </Modal>
            </Portal>
        </View>
    );
};

const styles = StyleSheet.create({
    container: { flex: 1, padding: 16 },
    searchRow: { flexDirection: 'row', gap: 10, marginBottom: 15, alignItems: 'center' },
    search: { flex: 1 },
    card: { marginBottom: 10 },
    modal: { backgroundColor: 'white', padding: 20, margin: 20, borderRadius: 8 }
});
