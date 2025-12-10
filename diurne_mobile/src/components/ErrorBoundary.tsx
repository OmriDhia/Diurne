import React, { Component, ErrorInfo, ReactNode } from 'react';
import { View, Text, StyleSheet, ScrollView, TouchableOpacity, SafeAreaView } from 'react-native';

interface Props {
    children: ReactNode;
}

interface State {
    hasError: boolean;
    error: Error | null;
    errorInfo: ErrorInfo | null;
}

export class ErrorBoundary extends Component<Props, State> {
    constructor(props: Props) {
        super(props);
        this.state = {
            hasError: false,
            error: null,
            errorInfo: null,
        };
    }

    // Method to manually set error from async catch blocks
    public handleError = (error: Error) => {
        this.setState({ hasError: true, error, errorInfo: null });
    };

    static getDerivedStateFromError(error: Error): State {
        return { hasError: true, error, errorInfo: null };
    }

    componentDidCatch(error: Error, errorInfo: ErrorInfo) {
        this.setState({
            error,
            errorInfo,
        });
        console.error("ErrorBoundary caught an error", error, errorInfo);
    }

    resetError = () => {
        this.setState({ hasError: false, error: null, errorInfo: null });
    };

    render() {
        if (this.state.hasError) {
            return (
                <SafeAreaView style={styles.container}>
                    <ScrollView style={styles.content}>
                        <Text style={styles.title}>Something went wrong</Text>
                        <Text style={styles.subtitle}>
                            An error occurred in the application. Please examine the log below.
                        </Text>
                        <View style={styles.errorContainer}>
                            <Text style={styles.errorTitle}>Error:</Text>
                            <Text style={styles.errorText}>{this.state.error?.toString()}</Text>
                        </View>
                        <View style={styles.stackContainer}>
                            <Text style={styles.errorTitle}>Stack Trace:</Text>
                            <Text style={styles.errorText}>
                                {this.state.errorInfo?.componentStack || 'No stack trace available'}
                            </Text>
                        </View>
                        <TouchableOpacity style={styles.button} onPress={this.resetError}>
                            <Text style={styles.buttonText}>Try Again</Text>
                        </TouchableOpacity>
                    </ScrollView>
                </SafeAreaView>
            );
        }

        return this.props.children;
    }
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#fff',
    },
    content: {
        padding: 20,
    },
    title: {
        fontSize: 24,
        fontWeight: 'bold',
        color: '#B00020',
        marginBottom: 10,
    },
    subtitle: {
        fontSize: 16,
        color: '#333',
        marginBottom: 20,
    },
    errorContainer: {
        marginBottom: 20,
        backgroundColor: '#FFE5E5',
        padding: 10,
        borderRadius: 5,
    },
    stackContainer: {
        marginBottom: 20,
        backgroundColor: '#F5F5F5',
        padding: 10,
        borderRadius: 5,
    },
    errorTitle: {
        fontWeight: 'bold',
        marginBottom: 5,
    },
    errorText: {
        fontFamily: 'monospace',
        fontSize: 12,
    },
    button: {
        backgroundColor: '#2196F3',
        padding: 15,
        borderRadius: 5,
        alignItems: 'center',
        marginBottom: 40,
    },
    buttonText: {
        color: '#fff',
        fontWeight: 'bold',
        fontSize: 16,
    },
});
