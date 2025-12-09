import React, { useState } from 'react';
import { View, StyleSheet, Alert } from 'react-native';
import { Text, Button, Card, TextInput, Portal, Modal, HelperText } from 'react-native-paper';
import { CameraView, useCameraPermissions } from 'expo-camera';
import { MovementService } from '../services/MovementService';
import { useNavigation } from '@react-navigation/native';

export const StockMovementScreen = ({ route }: any) => {
    const type = route.params?.type || 'MOVE'; // IN, OUT, MOVE
    const navigation = useNavigation();

    const [step, setStep] = useState<1 | 2>(1); // 1 = Scan Carpet, 2 = Scan Location (or confirm)
    const [rn, setRn] = useState('');
    const [location, setLocation] = useState('');
    const [isCameraVisible, setCameraVisible] = useState(false);
    const [permission, requestPermission] = useCameraPermissions();

    const handleScan = ({ data }: { data: string }) => {
        setCameraVisible(false);
        if (step === 1) {
            setRn(data);
            setStep(2);
        } else {
            setLocation(data);
        }
    };

    const handleConfirm = async () => {
        if (!rn || !location) return;
        try {
            await MovementService.moveStock(rn, location, type);
            Alert.alert('Succès', 'Mouvement enregistré', [
                { text: 'OK', onPress: () => navigation.goBack() }
            ]);
        } catch (e) {
            Alert.alert('Erreur', 'Echec du mouvement');
        }
    };

    if (!permission?.granted) {
        return <Button onPress={requestPermission} style={{ marginTop: 50 }}>Permission Caméra Requise</Button>;
    }

    return (
        <View style={styles.container}>
            <Text variant="headlineSmall" style={styles.title}>
                {type === 'IN' ? 'Entrée Stock' : type === 'OUT' ? 'Sortie Stock' : 'Mouvement'}
            </Text>

            <Card style={styles.card}>
                <Card.Title title="Étape 1: Identifier Tapis" />
                <Card.Content>
                    <TextInput
                        label="Numéro RN"
                        value={rn}
                        onChangeText={setRn}
                        right={<TextInput.Icon icon="camera" onPress={() => { setStep(1); setCameraVisible(true); }} />}
                    />
                </Card.Content>
            </Card>

            {step === 2 && (
                <Card style={styles.card}>
                    <Card.Title title="Étape 2: Destination" />
                    <Card.Content>
                        <TextInput
                            label="Emplacement / Client"
                            value={location}
                            onChangeText={setLocation}
                            right={<TextInput.Icon icon="camera" onPress={() => { setStep(2); setCameraVisible(true); }} />}
                        />
                        <HelperText type="info">Scannez le code emplacement ou saisissez-le.</HelperText>
                    </Card.Content>
                </Card>
            )}

            <Button
                mode="contained"
                style={styles.button}
                disabled={!rn || !location}
                onPress={handleConfirm}
            >
                Valider Mouvement
            </Button>

            <Portal>
                <Modal visible={isCameraVisible} onDismiss={() => setCameraVisible(false)} contentContainerStyle={styles.cameraModal}>
                    <CameraView
                        style={styles.camera}
                        onBarcodeScanned={handleScan}
                        barcodeScannerSettings={{ barcodeTypes: ["qr", "code128"] }}
                    />
                    <Button onPress={() => setCameraVisible(false)}>Fermer</Button>
                </Modal>
            </Portal>
        </View>
    );
};

const styles = StyleSheet.create({
    container: { flex: 1, padding: 20 },
    title: { textAlign: 'center', marginBottom: 20 },
    card: { marginBottom: 20 },
    button: { marginTop: 10, paddingVertical: 5 },
    cameraModal: { backgroundColor: 'white', padding: 20, margin: 20, height: 400 },
    camera: { flex: 1, marginBottom: 10 },
});
