/* Global declarations for the project (small, non-invasive)
   Declares window.showMessage used across components and $Helper helper exposed on global Vue instance.
   This file reduces TypeScript/editor noise; it doesn't change runtime behavior.
*/

declare interface Window {
    showMessage?(message: string, type?: string): void;
}

declare module '*.vue' {
    import type { DefineComponent } from 'vue';
    const component: DefineComponent<{}, {}, any>;
    export default component;
}

declare const $Helper: any;

