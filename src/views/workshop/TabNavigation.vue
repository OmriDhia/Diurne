<script setup lang="ts">
    defineProps<{
        tabs: Array<{ id: string, label: string }>;
        activeTab: string;
    }>();

    const emit = defineEmits(['change-tab']);

    const setActiveTab = (tabId: string) => {
        emit('change-tab', tabId);
    };
</script>

<template>
    <div class="tab-navigation">
        <div
            v-for="tab in tabs"
            :key="tab.id"
            class="tab"
            :class="{ active: activeTab === tab.id }"
            @click="setActiveTab(tab.id)"
        >
            {{ tab.label }}
        </div>
    </div>
</template>

<style scoped lang="scss">
    .tab-navigation {
        display: flex;
        background-color: #a7a7a7;
        border-bottom: 1px solid #ddd;
    }

    .tab {
        padding: 12px 20px;
        cursor: pointer;
        transition: background-color 0.2s ease;

        &:hover {
            background-color: #e5e5e5;
        }

        &.active {
            background-color: white;
            border-bottom: 2px solid #000;
            font-weight: 600;
        }
    }

    @media (max-width: 576px) {
        .tab-navigation {
            flex-direction: column;
        }

        .tab {
            padding: 10px;
            text-align: center;
        }
    }
</style>
