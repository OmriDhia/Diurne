import React, { useState } from 'react';
import { View, StyleSheet, ScrollView } from 'react-native';
import { TextInput, Button, Card, Text, Chip, Divider } from 'react-native-paper';

export const ProgressReportScreen = () => {
    const [rn, setRn] = useState('');
    const [status, setStatus] = useState('Préparation');

    // Mock events
    const events = [
        { date: '2023-10-01', status: 'Lancement', user: 'Admin' },
        { date: '2023-11-15', status: 'Tissage', user: 'Chef Atelier' }
    ];

    return (
        <ScrollView contentContainerStyle={styles.container}>
            <Card style={styles.card}>
                <Card.Title title="Saisie PR (Progress Report)" />
                <Card.Content>
                    <TextInput mode="outlined" label="Numéro RN" value={rn} onChangeText={setRn} right={<TextInput.Icon icon="barcode-scan" />} />
                    <View style={styles.statusContainer}>
                        <Text variant="titleMedium" style={{ marginTop: 10 }}>État actuel:</Text>
                        <View style={styles.chips}>
                            {['Préparation', 'Tissage', 'Finition', 'Envoi'].map(s => (
                                <Chip key={s} selected={status === s} onPress={() => setStatus(s)} style={styles.chip}>{s}</Chip>
                            ))}
                        </View>
                    </View>

                    {status === 'Tissage' && (
                        <TextInput mode="outlined" label="Mètres Tissés" keyboardType="numeric" style={{ marginTop: 10 }} />
                    )}

                    <TextInput mode="outlined" label="Commentaire" multiline numberOfLines={3} style={{ marginTop: 10 }} />

                    <Button mode="contained" style={styles.button} icon="plus">Ajouter Événement</Button>
                </Card.Content>
            </Card>

            <Text variant="titleMedium" style={styles.historyTitle}>Historique</Text>
            {events.map((ev, i) => (
                <Card key={i} style={styles.eventCard}>
                    <Card.Title title={ev.status} subtitle={`${ev.date} - ${ev.user}`} />
                </Card>
            ))}
        </ScrollView>
    );
};

const styles = StyleSheet.create({
    container: { padding: 16 },
    card: { marginBottom: 20 },
    statusContainer: { marginVertical: 10 },
    chips: { flexDirection: 'row', flexWrap: 'wrap', gap: 5, marginTop: 5 },
    chip: { marginBottom: 5 },
    button: { marginTop: 15 },
    historyTitle: { marginBottom: 10, fontWeight: 'bold' },
    eventCard: { marginBottom: 10, backgroundColor: '#f5f5f5' }
});
