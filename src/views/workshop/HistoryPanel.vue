<script setup lang="ts">
    import { ref } from 'vue';

    // Sample history data
    const historyEvents = ref([
        { id: 1, event: 'Réception tapis', date: '24-10-2021' },
        { id: 2, event: 'Commande atelier', date: '24-10-2021' },
        { id: 3, event: 'Acompte payé', date: '24-10-2021' }
    ]);

    const expandedEvents = ref<number[]>([]);

    const toggleEvent = (eventId: number) => {
        const index = expandedEvents.value.indexOf(eventId);
        if (index === -1) {
            expandedEvents.value.push(eventId);
        } else {
            expandedEvents.value.splice(index, 1);
        }
    };

    const addNewEvent = () => {
        // Logic to add a new event would go here
        console.log('Adding new event');
    };
</script>

<template>
    <div class="history-panel">
        <h3>Historique</h3>

        <div class="history-header">
            <div class="event-col">Événement</div>
            <div class="date-col">Date</div>
            <div class="action-col"></div>
        </div>

        <div class="history-content">
            <div v-for="event in historyEvents" :key="event.id" class="history-item">
                <div class="event-info">
                    <div class="event-col">
                        <div class="expand-icon" @click="toggleEvent(event.id)">
                            ▶
                        </div>
                        {{ event.event }}
                    </div>
                    <div class="date-col">{{ event.date }}</div>
                    <div class="action-col">
                        <button class="close-btn">×</button>
                    </div>
                </div>
            </div>
        </div>

        <button class="add-event-btn" @click="addNewEvent">
            <span>+</span> Ajouter
        </button>
    </div>
</template>

<style scoped lang="scss">
    .history-panel {
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 15px;

        h3 {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: 600;
        }
    }

    .history-header {
        display: flex;
        background: #000;
        color: white;
        padding: 8px;
        font-size: 14px;
        font-weight: 500;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }

    .history-content {
        border: 1px solid #ddd;
        border-top: none;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        margin-bottom: 15px;
    }

    .history-item {
        border-bottom: 1px solid #eee;

        &:last-child {
            border-bottom: none;
        }
    }

    .event-info {
        display: flex;
        padding: 10px 8px;
        font-size: 14px;
        align-items: center;
    }

    .event-col {
        flex: 2;
        display: flex;
        align-items: center;

        .expand-icon {
            font-size: 10px;
            margin-right: 8px;
            cursor: pointer;
        }
    }

    .date-col {
        flex: 1;
        text-align: center;
    }

    .action-col {
        width: 24px;

        .close-btn {
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: #666;
            padding: 0;

            &:hover {
                color: #000;
            }
        }
    }

    .add-event-btn {
        background: none;
        border: none;
        color: #333;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: 0;

        span {
            font-size: 16px;
            margin-right: 5px;
        }

        &:hover {
            text-decoration: underline;
        }
    }

    @media (max-width: 768px) {
        .history-panel {
            width: 100%;
        }
    }
</style>
