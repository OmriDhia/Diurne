import React, { useState, useEffect } from 'react';
import { View, StyleSheet, FlatList, Alert } from 'react-native';
import { TextInput, Button, Card, Text, IconButton, Portal, Modal, SegmentedButtons } from 'react-native-paper';
import client from '../../../api/client'; // Import Client 
// import { getDatabase } from '../../../core/database/Database'; // Remove Database

export const AdminUserScreen = () => {
    const [users, setUsers] = useState<any[]>([]);
    const [query, setQuery] = useState('');
    const [visible, setVisible] = useState(false);
    const [editingId, setEditingId] = useState<number | null>(null);

    // Form State
    const [email, setEmail] = useState('');
    const [name, setName] = useState('');
    const [password, setPassword] = useState('');
    const [roleId, setRoleId] = useState(4); // Default to 'users' permission (ID 4 in my seed)
    // Wait, I should fetch permissions to show in dropdown or segment.
    // But for now let's map: 
    // Data Seed IDs: 1=admin, 2=interne, 3=workshop, 4=users.
    // UI Values: ADMIN, WORKSHOP, INTERN, PHOTO.
    // Mapping: ADMIN->1, WORKSHOP->3, INTERN->2, PHOTO->? (Lets say 4 'users' for now).

    // I should ideally fetch permissions from API /mobile/permissions to hold real IDs.
    const [permissions, setPermissions] = useState<any[]>([]);

    useEffect(() => {
        loadPermissions();
        loadUsers();
    }, []);

    const loadPermissions = async () => {
        try {
            const response = await client.get('/mobile/permissions');
            setPermissions(response.data);
            // Example response: [{id:1, name:'admin', ...}, ...]
        } catch (e) {
            console.error('Error loading permissions', e);
        }
    };

    const loadUsers = async () => {
        try {
            const response = await client.get('/mobile/users');
            let data = response.data;
            if (query) {
                // Client-side filtering because API doesn't support filter by email yet (except custom search)
                data = data.filter((u: any) => u.email.includes(query) || u.name?.includes(query));
            }
            setUsers(data);
        } catch (e) {
            console.error(e);
        }
    };

    // Reload when query changes
    useEffect(() => { loadUsers(); }, [query]);

    const handleSave = async () => {
        if (!email) return;
        try {
            if (editingId) {
                // Update
                // API expects: { name, email, permissionId, isActive, picture }
                // We keep existing values if not editing them.
                // But simplified form only has Email and Role.
                // We should add Name and Password (for create).
                await client.put(`/mobile/users/${editingId}`, {
                    email,
                    name: name || 'Updated User', // Fallback
                    permissionId: roleId,
                    isActive: true
                });
            } else {
                // Create
                await client.post('/mobile/users', {
                    email,
                    name: name || 'New User',
                    password: password || '12345678', // Default pwd if not set
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
        setRoleId(user.permission.id);
        setVisible(true);
    };

    const resetForm = () => {
        setEditingId(null);
        setEmail('');
        setName('');
        setPassword('');
        setRoleId(4); // Default
    };

    // Helper to map Permission Name to Segment Value
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
                data={users}
                keyExtractor={(item: any) => item.id.toString()}
                renderItem={({ item }: { item: any }) => (
                    <Card style={styles.card}>
                        <Card.Title
                            title={item.email}
                            subtitle={`Nom: ${item.name} | Rôle: ${item.permission?.name}`}
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
                            label: p.name.charAt(0).toUpperCase() + p.name.slice(1) // Capitalize
                        })).slice(0, 4)} // Limit to fit in row? Or allow scroll.
                        style={{ marginBottom: 20 }}
                    />
                    {/* Fallback if no permissions loaded? */}
                    {permissions.length === 0 && <Text>Chargement des rôles...</Text>}

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
