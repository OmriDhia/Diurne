import React, { useState, useEffect } from 'react';
import { View, StyleSheet, ScrollView, Alert } from 'react-native';
import { TextInput, Button, Card, Text, IconButton, Divider } from 'react-native-paper';
import { getDatabase } from '../../../core/database/Database';

export const AdminTypeScreen = () => {
    const [prStatuses, setPrStatuses] = useState<any[]>([]);
    const [photoTypes, setPhotoTypes] = useState<any[]>([]);

    // Inputs
    const [newPr, setNewPr] = useState('');
    const [newPhoto, setNewPhoto] = useState('');

    // Editing
    const [editPrId, setEditPrId] = useState<number | null>(null);
    const [editPhotoId, setEditPhotoId] = useState<number | null>(null);
    const [editPrVal, setEditPrVal] = useState('');
    const [editPhotoVal, setEditPhotoVal] = useState('');

    const db = getDatabase();

    const loadData = async () => {
        try {
            setPrStatuses(await db.getAllAsync('SELECT * FROM pr_statuses'));
            setPhotoTypes(await db.getAllAsync('SELECT * FROM photo_types'));
        } catch (e) { }
    };

    useEffect(() => { loadData(); }, []);

    // Helper for generic CRUD
    const handleAdd = async (table: string, val: string, setVal: any) => {
        if (!val) return;
        await db.runAsync(`INSERT INTO ${table} (label) VALUES (?)`, [val]);
        setVal('');
        loadData();
    };

    const handleDelete = async (table: string, id: number) => {
        await db.runAsync(`DELETE FROM ${table} WHERE id = ?`, [id]);
        loadData();
    };

    const handleUpdate = async (table: string, id: number, val: string, setEditId: any) => {
        if (!val) return;
        await db.runAsync(`UPDATE ${table} SET label = ? WHERE id = ?`, [val, id]);
        setEditId(null);
        loadData();
    };

    return (
        <ScrollView style={styles.container}>
            {/* PROGRESS REPORT SECTION */}
            <Text variant="headlineSmall" style={styles.header}>Statuts Progress Report</Text>
            <View style={styles.addCallback}>
                <TextInput value={newPr} onChangeText={setNewPr} label="Nouveau Statut" mode="outlined" style={{ flex: 1 }} />
                <Button mode="contained" onPress={() => handleAdd('pr_statuses', newPr, setNewPr)} icon="plus">Ajouter</Button>
            </View>

            {prStatuses.map(item => (
                <Card key={item.id} style={styles.card}>
                    <Card.Content style={styles.row}>
                        {editPrId === item.id ? (
                            <TextInput value={editPrVal} onChangeText={setEditPrVal} dense style={{ flex: 1 }} />
                        ) : (
                            <Text variant="bodyLarge" style={{ flex: 1 }}>{item.label}</Text>
                        )}

                        <View style={{ flexDirection: 'row' }}>
                            {editPrId === item.id ? (
                                <IconButton icon="check" onPress={() => handleUpdate('pr_statuses', item.id, editPrVal, setEditPrId)} />
                            ) : (
                                <IconButton icon="pencil" onPress={() => { setEditPrId(item.id); setEditPrVal(item.label); }} />
                            )}
                            <IconButton icon="delete" iconColor="red" onPress={() => handleDelete('pr_statuses', item.id)} />
                        </View>
                    </Card.Content>
                </Card>
            ))}

            <Divider style={styles.divider} />

            {/* PHOTO TYPES SECTION */}
            <Text variant="headlineSmall" style={styles.header}>Types de Photo</Text>
            <View style={styles.addCallback}>
                <TextInput value={newPhoto} onChangeText={setNewPhoto} label="Nouveau Type" mode="outlined" style={{ flex: 1 }} />
                <Button mode="contained" onPress={() => handleAdd('photo_types', newPhoto, setNewPhoto)} icon="plus">Ajouter</Button>
            </View>

            {photoTypes.map(item => (
                <Card key={item.id} style={styles.card}>
                    <Card.Content style={styles.row}>
                        {editPhotoId === item.id ? (
                            <TextInput value={editPhotoVal} onChangeText={setEditPhotoVal} dense style={{ flex: 1 }} />
                        ) : (
                            <Text variant="bodyLarge" style={{ flex: 1 }}>{item.label}</Text>
                        )}

                        <View style={{ flexDirection: 'row' }}>
                            {editPhotoId === item.id ? (
                                <IconButton icon="check" onPress={() => handleUpdate('photo_types', item.id, editPhotoVal, setEditPhotoId)} />
                            ) : (
                                <IconButton icon="pencil" onPress={() => { setEditPhotoId(item.id); setEditPhotoVal(item.label); }} />
                            )}
                            <IconButton icon="delete" iconColor="red" onPress={() => handleDelete('photo_types', item.id)} />
                        </View>
                    </Card.Content>
                </Card>
            ))}

            <View style={{ height: 50 }} />
        </ScrollView>
    );
};

const styles = StyleSheet.create({
    container: { flex: 1, padding: 16 },
    header: { marginBottom: 10, fontWeight: 'bold', color: '#666' },
    addCallback: { flexDirection: 'row', gap: 10, marginBottom: 15, alignItems: 'center' },
    card: { marginBottom: 8 },
    row: { flexDirection: 'row', alignItems: 'center' },
    divider: { marginVertical: 30 }
});
