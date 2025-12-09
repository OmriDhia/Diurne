import React, { useState, useEffect } from 'react';
import { View, StyleSheet, FlatList, Alert } from 'react-native';
import { TextInput, Button, Card, Text, IconButton, Portal, Modal, SegmentedButtons } from 'react-native-paper';
import { getDatabase } from '../../../core/database/Database';

export const AdminUserScreen = () => {
    const [users, setUsers] = useState<any[]>([]);
    const [query, setQuery] = useState('');
    const [visible, setVisible] = useState(false);
    const [editingId, setEditingId] = useState<number | null>(null);

    // Form State
    const [email, setEmail] = useState('');
    const [role, setRole] = useState('ADMIN');

    const db = getDatabase();

    const loadUsers = async () => {
        try {
            const sql = query ? 'SELECT * FROM users WHERE email LIKE ?' : 'SELECT * FROM users';
            const params = query ? [`%${query}%`] : [];
            const data = await db.getAllAsync(sql, params);
            setUsers(data);
        } catch (e) {
            console.error(e);
        }
    };

    useEffect(() => { loadUsers(); }, [query]);

    const handleSave = async () => {
        if (!email) return;
        try {
            if (editingId) {
                await db.runAsync('UPDATE users SET email = ?, role = ? WHERE id = ?', [email, role, editingId]);
            } else {
                await db.runAsync('INSERT INTO users (email, role) VALUES (?, ?)', [email, role]);
            }
            setVisible(false);
            loadUsers();
            resetForm();
        } catch (e) {
            Alert.alert('Erreur', 'Impossible de sauvegarder');
        }
    };

    const handleDelete = async (id: number) => {
        Alert.alert('Confirmer', 'Supprimer cet utilisateur ?', [
            { text: 'Annuler', style: 'cancel' },
            {
                text: 'Supprimer', style: 'destructive',
                onPress: async () => {
                    await db.runAsync('DELETE FROM users WHERE id = ?', [id]);
                    loadUsers();
                }
            }
        ]);
    };

    const openEdit = (user: any) => {
        setEditingId(user.id);
        setEmail(user.email);
        setRole(user.role);
        setVisible(true);
    };

    const resetForm = () => {
        setEditingId(null);
        setEmail('');
        setRole('ADMIN');
    };

    return (
        <View style={styles.container}>
            <View style={styles.searchRow}>
                <TextInput
                    mode="outlined"
                    label="Rechercher (Email)"
                    style={styles.search}
                    value={query}
                    onChangeText={setQuery}
                    right={<TextInput.Icon icon="magnify" />}
                />
                <Button mode="contained" icon="plus" onPress={() => { resetForm(); setVisible(true); }}>Nouveau</Button>
            </View>

            <FlatList
                data={users}
                keyExtractor={(item) => item.id.toString()}
                renderItem={({ item }) => (
                    <Card style={styles.card}>
                        <Card.Title
                            title={item.email}
                            subtitle={`Rôle: ${item.role}`}
                            right={(props) => (
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
                    <TextInput label="Email" value={email} onChangeText={setEmail} style={{ marginBottom: 15 }} autoCapitalize="none" keyboardType="email-address" />

                    <Text style={{ marginBottom: 5 }}>Rôle</Text>
                    <SegmentedButtons
                        value={role}
                        onValueChange={setRole}
                        buttons={[
                            { value: 'ADMIN', label: 'Admin' },
                            { value: 'WORKSHOP', label: 'Atelier' },
                            { value: 'INTERN', label: 'Stage' },
                            { value: 'PHOTO', label: 'Photo' },
                        ]}
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
