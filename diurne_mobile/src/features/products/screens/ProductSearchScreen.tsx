import React, { useState } from 'react';
import { View, StyleSheet, FlatList } from 'react-native';
import { TextInput, Card, Text, Chip, ActivityIndicator, SegmentedButtons } from 'react-native-paper';
import { getDatabase } from '../../../core/database/Database';

export const ProductSearchScreen = () => {
    const [query, setQuery] = useState('');
    const [results, setResults] = useState<any[]>([]);
    const [loading, setLoading] = useState(false);
    const [mode, setMode] = useState('EMPL'); // EMPL, MVT, PROD, PHOTO

    const handleSearch = async () => {
        if (!query) return;
        setLoading(true);
        try {
            const db = getDatabase();
            // Placeholder: Logic would differ based on 'mode'
            const data = await db.getAllAsync(
                'SELECT * FROM carpets WHERE rn LIKE ? LIMIT 20',
                [`%${query}%`]
            );
            setResults(data);
        } catch (e) {
            console.error(e);
        } finally {
            setLoading(false);
        }
    };

    return (
        <View style={styles.container}>
            <SegmentedButtons
                value={mode}
                onValueChange={setMode}
                buttons={[
                    { value: 'EMPL', label: 'Empl' },
                    { value: 'MVT', label: 'Mvt' },
                    { value: 'PROD', label: 'Prod' },
                    { value: 'PHOTO', label: 'Photo' },
                ]}
                style={styles.tabs}
            />

            <TextInput
                label={`Recherche ${mode}...`}
                value={query}
                onChangeText={setQuery}
                right={<TextInput.Icon icon="magnify" onPress={handleSearch} />}
                onSubmitEditing={handleSearch}
                style={styles.input}
            />

            {loading && <ActivityIndicator style={styles.loader} />}

            <FlatList
                data={results}
                keyExtractor={(item) => item.id.toString()}
                renderItem={({ item }) => (
                    <Card style={styles.card}>
                        <Card.Title title={`RN: ${item.rn}`} subtitle={item.reference} />
                        <Card.Content>
                            <View style={styles.chips}>
                                <Chip icon="map-marker">{item.location_id || 'N/A'}</Chip>
                                <Chip icon="star" style={{ marginLeft: 5 }}>{item.status || 'Unknown'}</Chip>
                            </View>
                            <Text style={{ marginTop: 5 }}>Updated: {new Date(item.updated_at).toLocaleDateString()}</Text>
                        </Card.Content>
                    </Card>
                )}
                ListEmptyComponent={!loading ? <Text style={styles.empty}>Aucun résultat (Local DB)</Text> : null}
            />
        </View>
    );
};

const styles = StyleSheet.create({
    container: { flex: 1, padding: 10 },
    tabs: { marginBottom: 15 },
    input: { marginBottom: 10 },
    loader: { margin: 20 },
    card: { marginBottom: 10 },
    chips: { flexDirection: 'row' },
    empty: { textAlign: 'center', marginTop: 20, color: '#888' }
});
