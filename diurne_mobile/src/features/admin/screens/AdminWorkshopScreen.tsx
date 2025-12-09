import React, { useState, useEffect } from 'react';
import { View, StyleSheet, FlatList, Alert } from 'react-native';
import { TextInput, Button, Card, Text, IconButton, Portal, Modal } from 'react-native-paper';
import { getDatabase } from '../../../core/database/Database';

export const AdminWorkshopScreen = () => {
    const [workshops, setWorkshops] = useState<any[]>([]);
    const [query, setQuery] = useState('');
    const [visible, setVisible] = useState(false);
    const [editingId, setEditingId] = useState<number | null>(null);

    const [name, setName] = useState('');
    const [rnTapis, setRnTapis] = useState('');
    const [rnEch, setRnEch] = useState('');

    const db = getDatabase();

    const loadData = async () => {
        try {
            const sql = query ? 'SELECT * FROM workshops WHERE name LIKE ?' : 'SELECT * FROM workshops';
            const params = query ? [`%${query}%`] : [];
            const data = await db.getAllAsync(sql, params);
            setWorkshops(data);
        } catch (e) { console.error(e); }
    };

    useEffect(() => { loadData(); }, [query]);

    const handleSave = async () => {
        if (!name) return;
        try {
            if (editingId) {
                await db.runAsync('UPDATE workshops SET name=?, rn_tapis=?, rn_ech=? WHERE id=?', [name, rnTapis, rnEch, editingId]);
            } else {
                await db.runAsync('INSERT INTO workshops (name, rn_tapis, rn_ech) VALUES (?, ?, ?)', [name, rnTapis, rnEch]);
            }
            setVisible(false);
            loadData();
            resetForm();
        } catch (e) {
            Alert.alert('Erreur', 'Erreur sauvegarde');
        }
    };

    const handleDelete = async (id: number) => {
        Alert.alert('Confirmer', 'Supprimer cet atelier ?', [
            { text: 'Annuler' },
            {
                text: 'Supprimer', style: 'destructive', onPress: async () => {
                    await db.runAsync('DELETE FROM workshops WHERE id = ?', [id]);
                    loadData();
                }
            }
        ]);
    };

    const openEdit = (item: any) => {
        setEditingId(item.id);
        setName(item.name);
        setRnTapis(item.rn_tapis);
        setRnEch(item.rn_ech);
        setVisible(true);
    };

    const resetForm = () => {
        setEditingId(null);
        setName('');
        setRnTapis('');
        setRnEch('');
    };

    return (
        <View style={styles.container}>
            <View style={styles.searchRow}>
                <TextInput mode="outlined" label="Recherche (Nom)" style={styles.search} value={query} onChangeText={setQuery} />
                <Button mode="contained" icon="plus" onPress={() => { resetForm(); setVisible(true); }}>Nouveau</Button>
            </View>

            <FlatList
                data={workshops}
                keyExtractor={(item) => item.id.toString()}
                renderItem={({ item }) => (
                    <Card style={styles.card}>
                        <Card.Title
                            title={item.name}
                            subtitle={`Tapis: ${item.rn_tapis || '-'} | Ech: ${item.rn_ech || '-'}`}
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
                    <Text variant="headlineSmall" style={{ marginBottom: 15 }}>{editingId ? 'Modifier' : 'Nouvel'} Atelier</Text>
                    <TextInput label="Nom de l'atelier" value={name} onChangeText={setName} style={styles.input} />
                    <View style={{ flexDirection: 'row', gap: 10 }}>
                        <TextInput label="RN Tapis (Lettre)" value={rnTapis} onChangeText={setRnTapis} style={[styles.input, { flex: 1 }]} maxLength={1} />
                        <TextInput label="RN Éch (Lettre)" value={rnEch} onChangeText={setRnEch} style={[styles.input, { flex: 1 }]} maxLength={1} />
                    </View>
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
    modal: { backgroundColor: 'white', padding: 20, margin: 20, borderRadius: 8 },
    input: { marginBottom: 15 }
});
