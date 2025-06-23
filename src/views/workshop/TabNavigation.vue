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
        background-color: #d4eff8;
    }

    .tab {
        padding: 12px 20px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;

        &:hover {
            background-color: #e5e5e5;
        }
        &:first-child {
            margin-left: 2rem;
        }
        &.active {
            background-color: white;
            border-bottom: none;
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
