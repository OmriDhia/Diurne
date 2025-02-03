<template>
    <d-data-grid
        :fetchData="fetchData"
        :saveData="saveData"
        :addData="addData"
        :columns="columns"
        title="Model"
        rowKey="id"
        
    />
</template>

<script setup>
    import dDataGrid from "../base/d-data-grid.vue";

    const fetchData = async () => {
        return [
            { id: 1, name: "John Doe", email: "john.doe@example.com", role: "Admin", active: true },
            { id: 2, name: "Jane Smith", email: "jane.smith@example.com", role: "User", active: false },
        ];
    };

    const saveData = async (row) => {
        console.log("Enregistré :", row);
    };

    const addData = async (row) => {
        console.log("Ajouté :", row);
        return { ...row, id: Date.now() };
    };

    const columns = [
        { key: "name", label: "Nom", type: "text" },
        { key: "email", label: "Email", type: "text" },
        { key: "role", label: "Rôle", type: "dropdown", options: ["Admin", "User"] },
        { key: "active", label: "Actif", type: "boolean" },
    ];
</script>
