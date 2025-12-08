<template>
  <div class="form-group">
    
    <div :class="{ 'col-12': !showDetails, 'col-7': showDetails && selectedQuote }">
      <multiselect
        :class="{ 'is-invalid': error }"
        v-model="selectedQuote"
        :options="filteredQuotes"
        :multiple="false"
        :placeholder="placeholder"
        track-by="id"
        label="reference"
        :searchable="true"
        selected-label=""
        select-label=""
        deselect-label=""
        :disabled="disabled"
        @update:model-value="handleChange($event)"
        @search-change="handleSearch"
      >
        <template v-slot:singlelabel="{ value }">
          <div class="multiselect-single-label">
            {{ value.reference }} - {{ formatDate(value.createdAt) }} -
            {{ formatCurrency(value.totalAmountTtc) }}
          </div>
        </template>
        <template v-slot:option="{ option }">
          <div>
            <strong>{{ option.reference }}</strong
            ><br />
            <small class="text-muted">
              {{ formatDate(option.createdAt) }} |
              {{ formatCurrency(option.totalAmountTtc) }} |
              {{ option.customerName }}
            </small>
          </div>
        </template>
        <template v-slot:afterList>
          <div class="row justify-content-between align-items-center p-1">
            <div class="col-6 text-start">
              <a
                href="#"
                @click="prevPage"
                class="w-100 font-size-0-9"
                v-if="currentPage > 1"
                >« précédent</a
              >
            </div>
            <div class="col-6 text-end">
              <a
                href="#"
                @click="nextPage"
                class="w-100 font-size-0-9"
                v-if="currentPage < totalPages"
                >suivant »</a
              >
            </div>
          </div>
        </template>
      </multiselect>
      <div v-if="error" class="invalid-feedback">{{ error }}</div>
    </div>
    <div class="col-1 ps-0" v-if="showDetails && selectedQuote">
      <router-link :to="'/quotes/manage/' + selectedQuote.id">
        <vue-feather type="eye" stroke-width="1" class="cursor-pointer"></vue-feather>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from "vue";
import axiosInstance from "../../config/http";
import { Helper } from "../../composables/global-methods";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.css";
import VueFeather from "vue-feather";

const props = defineProps({
  modelValue: [String, Number],
  label: {
    type: String,
    default: "Devis",
  },
  customerId: [String, Number],
  error: String,
  required: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  showDetails: {
    type: Boolean,
    default: false,
  },
  placeholder: {
    type: String,
    default: "Sélectionner un devis",
  },
});

const emit = defineEmits(["update:modelValue"]);

const selectedQuote = ref(null);
const quotes = ref([]);
const currentPage = ref(1);
const itemsPerPage = ref(20);
const totalQuotes = ref(0);
const searchQuery = ref("");
const loading = ref(false);

const formatDate = (dateString) => {
  return Helper.FormatDate(dateString, "DD/MM/YYYY");
};

const formatCurrency = (amount) => {
  return Helper.FormatNumber(amount) + " €";
};

const filteredQuotes = computed(() => {
  return quotes.value.map((quote) => ({
    ...quote,
    reference: quote.reference,
    createdAt: quote.createdAt,
    totalAmountTtc: quote.totalAmountTtc,
    customerName: quote.customer?.customerName || "N/A",
  }));
});

const totalPages = computed(() => {
  return Math.ceil(totalQuotes.value / itemsPerPage.value);
});

const fetchQuotes = async () => {
  try {
    loading.value = true;
    let url = `/api/quotes?page=${currentPage.value}&itemsPerPage=${itemsPerPage.value}&orderBy=createdAt&orderWay=desc&status=validated`;

    if (props.customerId) {
      url += `&customerId=${props.customerId}`;
    }

    if (searchQuery.value) {
      url += `&search=${searchQuery.value}`;
    }

    const response = await axiosInstance.get(url);
    quotes.value = response.data.quotes;
    totalQuotes.value = response.data.quotes.count;

    // Match with modelValue if provided
    if (props.modelValue) {
      selectedQuote.value = quotes.value.find((q) => q.id === props.modelValue.quote_id);
      if (!selectedQuote.value) {
        const specificQuote = await axiosInstance.get(
          `/api/quote/${props.modelValue.quote_id}`
        );
        if (specificQuote.data.response.quoteData) {
          quotes.value.unshift(specificQuote.data.response.quoteData);
          selectedQuote.value = specificQuote.data.response.quoteData;
        }
      }
    }
  } catch (error) {
    console.error("Error fetching quotes:", error);
  } finally {
    loading.value = false;
  }
};

const handleChange = (value) => {
  console.log(value);
  selectedQuote.value = value;
  emit("update:modelValue", value ? value.quote_id : null);
  emit("selected", value); // Add this line to emit the full quote object
};

const handleSearch = (query) => {
  searchQuery.value = query;
  currentPage.value = 1;
  fetchQuotes();
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    fetchQuotes();
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchQuotes();
  }
};

watch(
  () => props.customerId,
  (newVal) => {
    currentPage.value = 1;
    fetchQuotes();
  }
);

watch(
  () => props.modelValue,
  (newVal) => {
    if (newVal && (!selectedQuote.value || selectedQuote.value.id !== newVal)) {
      const existing = quotes.value.find((q) => q.id === newVal);
      if (existing) {
        selectedQuote.value = existing;
      } else {
        fetchQuotes();
      }
    } else if (!newVal) {
      selectedQuote.value = null;
    }
  }
);

onMounted(() => {
  fetchQuotes();
});
</script>

<style scoped>
.multiselect-single-label {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
