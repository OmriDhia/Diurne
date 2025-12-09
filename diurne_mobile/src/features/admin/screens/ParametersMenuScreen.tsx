import React from 'react';
import { View, StyleSheet } from 'react-native';
import { List, Divider } from 'react-native-paper';
import { useNavigation } from '@react-navigation/native';

export const ParametersMenuScreen = () => {
    const navigation = useNavigation<any>();

    return (
        <View style={styles.container}>
            <List.Section>
                <List.Subheader>Administration</List.Subheader>
                <List.Item
                    title="Utilisateurs"
                    description="Gérer les accès et rôles"
                    left={props => <List.Icon {...props} icon="account-group" />}
                    right={props => <List.Icon {...props} icon="chevron-right" />}
                    onPress={() => navigation.navigate('AdminUser')}
                />
                <Divider />
                <List.Item
                    title="Ateliers"
                    description="Gérer les ateliers et codes"
                    left={props => <List.Icon {...props} icon="domain" />}
                    right={props => <List.Icon {...props} icon="chevron-right" />}
                    onPress={() => navigation.navigate('AdminWorkshop')}
                />
                <Divider />
                <List.Item
                    title="Types & Statuts"
                    description="Gérer statuts PR et types photo"
                    left={props => <List.Icon {...props} icon="format-list-bulleted-type" />}
                    right={props => <List.Icon {...props} icon="chevron-right" />}
                    onPress={() => navigation.navigate('AdminType')}
                />
            </List.Section>
        </View>
    );
};

const styles = StyleSheet.create({
    container: { flex: 1, backgroundColor: 'white' }
});
