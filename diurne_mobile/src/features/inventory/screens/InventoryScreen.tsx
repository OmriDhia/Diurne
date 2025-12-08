import React, { useState, useEffect } from 'react';
import { View, StyleSheet, FlatList, Alert } from 'react-native';
import { Button, Text, Card, Chip, IconButton, Modal, Portal, TextInput } from 'react-native-paper';
import { CameraView, useCameraPermissions } from 'expo-camera';
import { useInventoryStore } from '../store/InventoryStore';

export const InventoryScreen = () => {
    const [permission, requestPermission] = useCameraPermissions();
    const [isCameraVisible, setCameraVisible] = useState(false);
    const [manualInput, setManualInput] = useState('');

    const {
        scannedItems,
        addItem,
        removeItem,
        currentLocationId,
        setLocation,
        saveSession
    } = useInventoryStore();

    useEffect(() => {
        // Mock Location Selection for now - ideally this comes from a previous screen or dropdown
        if (!currentLocationId) {
            setLocation(1); // Default to Location ID 1
        }
    }, []);

    if (!permission) {
        return <View />;
    }

    if (!permission.granted) {
        return (
            <View style={styles.container}>
                <Text style={{ textAlign: 'center' }}>We need your permission to show the camera</Text>
                <Button onPress={requestPermission} mode="contained">Grant Permission</Button>
            </View>
        );
    }

    const handleBarCodeScanned = ({ data }: { data: string }) => {
        addItem(data);
        // Optional: Vibration or Sound
        setCameraVisible(false); // Close camera after single scan for now, or keep open for rapid
    };

    const handleManualAdd = () => {
        if (manualInput) {
            addItem(manualInput);
            setManualInput('');
        }
    };

    return (
        <View style={styles.container}>
            <View style={styles.header}>
                <Text variant="titleMedium">Location: #{currentLocationId || 'Select'}</Text>
                <Button mode="contained" onPress={saveSession} disabled={scannedItems.length === 0}>
                    Terminer & Sync
                </Button>
            </View>

            <View style={styles.actions}>
                <Button icon="camera" mode="outlined" onPress={() => setCameraVisible(true)}>
                    Scanner
                </Button>
                <View style={styles.manualInput}>
                    <TextInput
                        placeholder="RN Number"
                        value={manualInput}
                        onChangeText={setManualInput}
                        style={{ height: 40, flex: 1, backgroundColor: 'white' }}
                        right={<TextInput.Icon icon="plus" onPress={handleManualAdd} />}
                    />
                </View>
            </View>

            <FlatList
                data={scannedItems}
                keyExtractor={(item) => item.rn}
                renderItem={({ item }) => (
                    <Card style={styles.card}>
                        <Card.Title
                            title={`RN: ${item.rn}`}
                            subtitle={new Date(item.timestamp).toLocaleTimeString()}
                            right={(props) => <IconButton {...props} icon="delete" onPress={() => removeItem(item.rn)} />}
                        />
                        <Card.Content>
                            <Chip icon="information" style={{ backgroundColor: item.status === 'FOUND' ? '#E8F5E9' : '#FFEBEE' }}>{item.status}</Chip>
                        </Card.Content>
                    </Card>
                )}
                style={styles.list}
            />

            <Portal>
                <Modal visible={isCameraVisible} onDismiss={() => setCameraVisible(false)} contentContainerStyle={styles.cameraModal}>
                    <CameraView
                        style={styles.camera}
                        onBarcodeScanned={handleBarCodeScanned}
                        barcodeScannerSettings={{
                            barcodeTypes: ["qr", "code128"],
                        }}
                    />
                    <Button mode="contained" onPress={() => setCameraVisible(false)} style={styles.closeCamera}>
                        Fermer
                    </Button>
                </Modal>
            </Portal>
        </View>
    );
};

const styles = StyleSheet.create({
    container: { flex: 1, padding: 10 },
    header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 15 },
    actions: { flexDirection: 'row', marginBottom: 15, gap: 10 },
    manualInput: { flex: 1 },
    list: { flex: 1 },
    card: { marginBottom: 10 },
    cameraModal: { backgroundColor: 'white', padding: 20, margin: 20, height: 400, borderRadius: 10 },
    camera: { flex: 1, marginBottom: 10 },
    closeCamera: { marginTop: 10 }
});
