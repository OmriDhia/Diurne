
// Add this at the top of the file, before any other imports if possible
const originalConsoleError = console.error;
console.error = (...args: any[]) => {
    // Capture console.errors as they might be what's showing up in the "blue screen" equivalent
    // You might want to store these in a global variable or custom logger to display in your ErrorBoundary
    originalConsoleError(...args);
};

// Global error handler for uncaught exceptions
const globalHandler = (error: Error, isFatal: boolean) => {
    console.log('Global error handler caught:', error);
    // Optional: Trigger a native alert or toast here if needed
};

// Use type assertion to avoid TypeScript errors with React Native globals
if ((global as any).ErrorUtils) {
    (global as any).ErrorUtils.setGlobalHandler(globalHandler);
}

// Ensure promises are handled if possible (RN specific)
// modern RN might need tracking libraries, but this is a start.
