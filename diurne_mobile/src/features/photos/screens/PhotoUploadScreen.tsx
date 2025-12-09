import React, { useState } from 'react';
import { View, StyleSheet, ScrollView, Image } from 'react-native';
import { TextInput, Button, Card, Text, SegmentedButtons } from 'react-native-paper';

export const PhotoUploadScreen = () => {
    const [rn, setRn] = useState('');
    const [type, setType] = useState('Production');

    return (
        <ScrollView contentContainerStyle={styles.container}>
            <Card style={styles.card}>
                <Card.Title title="Saisie Photo" />
                <Card.Content>
                    <TextInput mode="outlined" label="Numéro RN" value={rn} onChangeText={setRn} right={<TextInput.Icon icon="barcode-scan" />} />

                    <Text variant="titleMedium" style={{ marginTop: 15, marginBottom: 5 }}>Type de photo</Text>
                    <SegmentedButtons
                        value={type}
                        onValueChange={setType}
                        buttons={[
                            { value: 'Production', label: 'Prod' },
                            { value: 'Finition', label: 'Fin' },
                            { value: 'Drone', label: 'Drone' },
                        ]}
                        style={styles.segmented}
                    />
                    <SegmentedButtons
                        value={type}
                        onValueChange={setType}
                        buttons={[
                            { value: 'Vignette', label: 'Vignette' },
                            { value: 'Détail', label: 'Détail' },
                        ]}
                        style={styles.segmented}
                    />

                    <View style={styles.actions}>
                        <Button mode="contained" icon="camera" style={styles.btn}>Prendre Photo</Button>
                        <Button mode="outlined" icon="image" style={styles.btn}>Galerie</Button>
                    </View>
                </Card.Content>
            </Card>

            <View style={styles.preview}>
                <Text style={{ textAlign: 'center', color: '#888' }}>Aperçu (Aucune photo)</Text>
            </View>
        </ScrollView>
    );
};

const styles = StyleSheet.create({
    container: { padding: 16 },
    card: { marginBottom: 20 },
    segmented: { marginBottom: 10 },
    actions: { flexDirection: 'row', gap: 10, marginTop: 20 },
    btn: { flex: 1 },
    preview: { height: 200, backgroundColor: '#eee', justifyContent: 'center', alignItems: 'center', borderRadius: 8 }
});
