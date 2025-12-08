import React from 'react';
import { View, StyleSheet, Alert, Image } from 'react-native';
import { Button, Text, TextInput } from 'react-native-paper';
import { useAuthStore } from '../store/AuthStore';

export const LoginScreen = () => {
    const { signIn, isLoading } = useAuthStore();
    const [email, setEmail] = React.useState('');
    const [password, setPassword] = React.useState('');
    const [loading, setLoading] = React.useState(false);

    const handleLogin = async () => {
        if (!email || !password) return;
        setLoading(true);
        try {
            await signIn(email, password);
        } catch (e) {
            Alert.alert('Erreur', 'Identifiants incorrects');
        } finally {
            setLoading(false);
        }
    };

    return (
        <View style={styles.container}>
            <View style={styles.logoContainer}>
                <Image
                    source={require('../../../assets/logo.png')}
                    style={styles.logo}
                    resizeMode="contain"
                />
            </View>
            <TextInput
                label="Email"
                value={email}
                onChangeText={setEmail}
                style={styles.input}
                autoCapitalize="none"
                keyboardType="email-address"
            />
            <TextInput
                label="Password"
                value={password}
                onChangeText={setPassword}
                secureTextEntry
                style={styles.input}
            />
            <Button
                mode="contained"
                onPress={handleLogin}
                style={styles.button}
                loading={loading || isLoading}
                disabled={loading || isLoading}
            >
                Se connecter
            </Button>

            <View style={styles.demoSection}>
                <Text variant="bodySmall" style={styles.demoTitle}>— DEMO ACCOUNTS —</Text>
                <View style={styles.demoButtons}>
                    <Button mode="outlined" compact onPress={() => { setEmail('admin@diurne.com'); setPassword('demo'); }}>Admin</Button>
                    <Button mode="outlined" compact onPress={() => { setEmail('atelier@diurne.com'); setPassword('demo'); }}>Atelier</Button>
                    <Button mode="outlined" compact onPress={() => { setEmail('stage@diurne.com'); setPassword('demo'); }}>Stage</Button>
                </View>
            </View>
        </View>
    );
};

const styles = StyleSheet.create({
    container: { flex: 1, justifyContent: 'center', padding: 20, backgroundColor: 'white' },
    logoContainer: { alignItems: 'center', marginBottom: 40 },
    logo: { width: 180, height: 60, tintColor: 'black' },
    input: { marginBottom: 15 },
    button: { marginTop: 10 },
    demoSection: { marginTop: 40, alignItems: 'center' },
    demoTitle: { color: '#888', marginBottom: 10 },
    demoButtons: { flexDirection: 'row', gap: 10 }
});
