import React from 'react';
import { View, StyleSheet, Alert } from 'react-native';
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
            <Text variant="headlineMedium" style={styles.title}>Diurne Mobile</Text>
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
        </View>
    );
};

const styles = StyleSheet.create({
    container: { flex: 1, justifyContent: 'center', padding: 20 },
    title: { textAlign: 'center', marginBottom: 30 },
    input: { marginBottom: 15 },
    button: { marginTop: 10 },
});
