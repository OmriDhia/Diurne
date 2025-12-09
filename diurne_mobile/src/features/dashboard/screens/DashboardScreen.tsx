import React from 'react';
import { View, StyleSheet, ScrollView } from 'react-native';
import { Card, Text, Avatar } from 'react-native-paper';
import { useNavigation } from '@react-navigation/native';

export const DashboardScreen = () => {
    const navigation = useNavigation<any>();

    const menuItems = [
        { title: 'Inventaire', icon: 'clipboard-list', route: 'Inventory' },
        { title: 'Entrée Stock', icon: 'arrow-right-bold-box', route: 'StockMovement', params: { type: 'IN' } },
        { title: 'Sortie Stock', icon: 'arrow-left-bold-box', route: 'StockMovement', params: { type: 'OUT' } },
        { title: 'Progress Report', icon: 'list-status', route: 'ProgressReport' },
        { title: 'Recherche', icon: 'magnify', route: 'ProductSearch' },
        { title: 'Saisie Photo', icon: 'camera', route: 'PhotoUpload' },
        { title: 'Paramètres', icon: 'cog', route: 'Parameters' },
    ];

    return (
        <ScrollView contentContainerStyle={styles.container}>
            <Text variant="headlineSmall" style={styles.header}>Menu Principal</Text>
            <View style={styles.grid}>
                {menuItems.map((item, index) => (
                    <Card key={index} style={styles.card} onPress={() => navigation.navigate(item.route, item.params)}>
                        <Card.Content style={styles.cardContent}>
                            <Avatar.Icon size={50} icon={item.icon} />
                            <Text variant="titleMedium" style={styles.cardTitle}>{item.title}</Text>
                        </Card.Content>
                    </Card>
                ))}
            </View>
        </ScrollView>
    );
};

const styles = StyleSheet.create({
    container: { padding: 16 },
    header: { marginBottom: 20 },
    grid: { flexDirection: 'row', flexWrap: 'wrap', justifyContent: 'space-between' },
    card: { width: '48%', marginBottom: 16 },
    cardContent: { alignItems: 'center', padding: 10 },
    cardTitle: { marginTop: 10, textAlign: 'center' },
});
